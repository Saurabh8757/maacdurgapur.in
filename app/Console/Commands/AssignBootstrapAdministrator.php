<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AssignBootstrapAdministrator extends Command
{
    protected $signature = 'rbac:assign-bootstrap-admin
                            {--user-id= : Existing administrator user ID}
                            {--brand= : Required existing brand code}
                            {--reason= : Required assignment justification}
                            {--validate-only : Validate without creating an assignment}';

    protected $description = 'Validate and create the explicitly approved bootstrap Super Admin assignment';

    public function handle(): int
    {
        $userId = filter_var(
            $this->option('user-id'),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );
        $brandCode = trim((string) $this->option('brand'));
        $reason = trim((string) $this->option('reason'));

        if (!$userId || $brandCode === '' || $reason === '') {
            $this->error('The --user-id, --brand, and --reason options are required.');

            return self::INVALID;
        }

        try {
            $result = DB::transaction(function () use (
                $userId,
                $brandCode,
                $reason
            ): array {
                $user = User::query()
                    ->whereKey($userId)
                    ->lockForUpdate()
                    ->first();

                if (!$user || $user->deleted_at !== null) {
                    throw new RuntimeException('The approved user is unavailable.');
                }

                if ($user->user_type !== 'Admin') {
                    throw new RuntimeException('The approved user is not a legacy Admin.');
                }

                $brand = Brand::query()
                    ->where('code', $brandCode)
                    ->lockForUpdate()
                    ->first();

                if (!$brand || $brand->status !== 'active') {
                    throw new RuntimeException('The approved brand is unavailable or inactive.');
                }

                if (!$user->brands()->whereKey($brand->id)->exists()) {
                    throw new RuntimeException('The approved user lacks the required brand membership.');
                }

                $role = Role::query()
                    ->where('code', 'super_admin')
                    ->lockForUpdate()
                    ->first();

                if (!$role || $role->status !== 'active') {
                    throw new RuntimeException('The Super Admin role is unavailable or inactive.');
                }

                if ($role->scope_type !== 'global') {
                    throw new RuntimeException('The Super Admin role is not globally scoped.');
                }

                if ($role->permissions()->count() === 0) {
                    throw new RuntimeException('The Super Admin role has no permission catalogue.');
                }

                $existing = UserRole::query()
                    ->where('user_id', $user->id)
                    ->where('role_id', $role->id)
                    ->where('scope_key', 'global')
                    ->whereIn('status', ['pending', 'active'])
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    if (
                        $existing->status !== 'active'
                        || $existing->brand_id !== null
                    ) {
                        throw new RuntimeException(
                            'A conflicting bootstrap assignment already exists.'
                        );
                    }

                    return [
                        'created' => false,
                        'assignment' => $existing,
                        'user' => $user,
                        'brand' => $brand,
                        'role' => $role,
                    ];
                }

                if ($this->option('validate-only')) {
                    return [
                        'created' => false,
                        'assignment' => null,
                        'user' => $user,
                        'brand' => $brand,
                        'role' => $role,
                    ];
                }

                $assignment = UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                    'brand_id' => null,
                    'scope_key' => 'global',
                    'status' => 'active',
                    'starts_at' => now(),
                    'expires_at' => null,
                    'assigned_by' => $user->id,
                    'reason' => $reason,
                    'revoked_by' => null,
                    'revoked_at' => null,
                    'revocation_reason' => null,
                ]);

                $activeCount = UserRole::query()
                    ->where('user_id', $user->id)
                    ->where('role_id', $role->id)
                    ->where('scope_key', 'global')
                    ->where('status', 'active')
                    ->count();

                if ($activeCount !== 1) {
                    throw new RuntimeException(
                        'The bootstrap assignment did not resolve to exactly one active record.'
                    );
                }

                return [
                    'created' => true,
                    'assignment' => $assignment,
                    'user' => $user,
                    'brand' => $brand,
                    'role' => $role,
                ];
            });
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($this->option('validate-only') && !$result['assignment']) {
            $this->info('Bootstrap administrator prerequisites are valid.');

            return self::SUCCESS;
        }

        $this->info(
            $result['created']
                ? 'Bootstrap Super Admin assignment created.'
                : 'Bootstrap Super Admin assignment is already active.'
        );
        $this->line("User ID: {$result['user']->id}");
        $this->line("Role: {$result['role']->code}");
        $this->line('Assignment scope: global');
        $this->line("Required brand membership: {$result['brand']->code}");

        return self::SUCCESS;
    }
}
