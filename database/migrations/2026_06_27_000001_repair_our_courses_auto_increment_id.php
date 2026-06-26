<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('our_courses')) {
            return;
        }

        $column = DB::selectOne("
            SELECT COLUMN_TYPE, COLUMN_KEY, EXTRA
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'our_courses'
              AND COLUMN_NAME = 'id'
            LIMIT 1
        ");

        if (!$column) {
            return;
        }

        $extra = strtolower((string) ($column->EXTRA ?? ''));

        if (str_contains($extra, 'auto_increment')) {
            return;
        }

        $primaryKey = DB::selectOne("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'our_courses'
              AND INDEX_NAME = 'PRIMARY'
            LIMIT 1
        ");

        if (!$primaryKey) {
            DB::statement('ALTER TABLE `our_courses` ADD PRIMARY KEY (`id`)');
        }

        DB::statement('ALTER TABLE `our_courses` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
        // Intentionally left as a no-op.
        //
        // Removing AUTO_INCREMENT from a production primary key would be unsafe and
        // could break course creation, counselling foreign keys, and legacy content.
    }
};
