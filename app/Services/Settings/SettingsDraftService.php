<?php

namespace App\Services\Settings;

use App\Models\SettingDefinition;
use App\Models\SettingValue;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SettingsDraftService
{
    public function __construct(
        private SettingValidator $validator,
        private SettingValueCaster $caster,
        private SettingsAuditLogger $auditLogger
    ) {
    }

    public function updateDraft(
        User $user,
        SettingsScope $scope,
        SettingDefinition $definition,
        mixed $value,
        ?string $expectedUpdatedAt = null
    ): SettingValue {
        $validatedValue = $this->validator->validate($definition, $value)['value'];
        $castedValue = $this->caster->cast($definition, $validatedValue);

        return DB::transaction(function () use ($user, $scope, $definition, $castedValue, $expectedUpdatedAt) {
            $draft = SettingValue::query()
                ->where('setting_definition_id', $definition->getKey())
                ->where('scope_key', $scope->scopeKey())
                ->where('locale', $scope->locale())
                ->where('status', 'draft')
                ->lockForUpdate()
                ->first();

            $existingNonDraft = SettingValue::query()
                ->where('setting_definition_id', $definition->getKey())
                ->where('scope_key', $scope->scopeKey())
                ->where('locale', $scope->locale())
                ->where('status', '!=', 'draft')
                ->lockForUpdate()
                ->first();

            if ($existingNonDraft) {
                // Review-lock enforcement: cannot mutate if a non-draft is actively under review for the same scope
                throw new UnprocessableEntityHttpException('Cannot edit while a non-draft record exists for this setting.');
            }

            if ($draft) {
                if ($expectedUpdatedAt) {
                    try {
                        $expectedTime = \Carbon\Carbon::parse($expectedUpdatedAt);
                        if (!$draft->updated_at->equalTo($expectedTime)) {
                            throw new ConflictHttpException('The draft has been modified by someone else.');
                        }
                    } catch (\Exception $e) {
                        if ($e instanceof ConflictHttpException) {
                            throw $e;
                        }
                        throw new UnprocessableEntityHttpException('Invalid expected updated_at timestamp format.');
                    }
                }

                $beforeValue = $draft->value;
                $draft->value = $castedValue;
                $draft->updated_by = $user->getKey();
                $draft->save();

                $this->auditLogger->record(
                    event: 'draft_updated',
                    scopeKey: $scope->scopeKey(),
                    locale: $scope->locale(),
                    beforeValue: $beforeValue,
                    afterValue: $castedValue,
                    user: $user,
                    brand: $scope->brand(),
                    definition: $definition,
                    settingValue: $draft
                );

                return $draft;
            }

            $draft = new SettingValue([
                'setting_definition_id' => $definition->getKey(),
                'brand_id' => $scope->brand()?->getKey(),
                'scope_key' => $scope->scopeKey(),
                'locale' => $scope->locale(),
                'value' => $castedValue,
                'status' => 'draft',
            ]);

            $draft->created_by = $user->getKey();
            $draft->updated_by = $user->getKey();
            $draft->save();

            $this->auditLogger->record(
                event: 'draft_created',
                scopeKey: $scope->scopeKey(),
                locale: $scope->locale(),
                afterValue: $castedValue,
                user: $user,
                brand: $scope->brand(),
                definition: $definition,
                settingValue: $draft
            );

            return $draft;
        });
    }

    public function resetOverride(
        User $user,
        SettingsScope $scope,
        SettingDefinition $definition
    ): void {
        DB::transaction(function () use ($user, $scope, $definition) {
            $draft = SettingValue::query()
                ->where('setting_definition_id', $definition->getKey())
                ->where('scope_key', $scope->scopeKey())
                ->where('locale', $scope->locale())
                ->where('status', 'draft')
                ->lockForUpdate()
                ->first();

            if (!$draft) {
                return;
            }

            $beforeValue = $draft->value;
            $draft->delete();

            $this->auditLogger->record(
                event: 'override_reset',
                scopeKey: $scope->scopeKey(),
                locale: $scope->locale(),
                beforeValue: $beforeValue,
                user: $user,
                brand: $scope->brand(),
                definition: $definition
            );
        });
    }
}
