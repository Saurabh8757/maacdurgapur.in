<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Models\SettingAuditLog;
use App\Models\SettingDefinition;
use App\Models\SettingsPublication;
use App\Models\SettingValue;
use App\Models\User;
use LogicException;
use Tests\TestCase;

class SettingAuditLogTest extends TestCase
{
    public function test_model_uses_append_only_timestamp_contract(): void
    {
        $auditLog = new SettingAuditLog();

        $this->assertFalse($auditLog->usesTimestamps());
        $this->assertNull($auditLog->getUpdatedAtColumn());
    }

    public function test_existing_audit_log_cannot_be_updated(): void
    {
        $auditLog = new SettingAuditLog([
            'scope_key' => 'global',
            'locale' => 'en',
            'event' => 'draft.created',
        ]);
        $auditLog->id = 1;
        $auditLog->exists = true;

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('append-only');

        $auditLog->event = 'draft.updated';
        $auditLog->save();
    }

    public function test_existing_audit_log_cannot_be_deleted(): void
    {
        $auditLog = new SettingAuditLog();
        $auditLog->id = 1;
        $auditLog->exists = true;

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('append-only');

        $auditLog->delete();
    }

    public function test_relationships_target_expected_models(): void
    {
        $auditLog = new SettingAuditLog();

        $this->assertInstanceOf(User::class, $auditLog->user()->getRelated());
        $this->assertInstanceOf(Brand::class, $auditLog->brand()->getRelated());
        $this->assertInstanceOf(
            SettingDefinition::class,
            $auditLog->definition()->getRelated()
        );
        $this->assertInstanceOf(
            SettingValue::class,
            $auditLog->settingValue()->getRelated()
        );
        $this->assertInstanceOf(
            SettingsPublication::class,
            $auditLog->publication()->getRelated()
        );
    }
}
