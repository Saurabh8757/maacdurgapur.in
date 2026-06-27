<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = 'cms_showcase_categories';

        if (!Schema::hasTable($table)) {
            return;
        }

        $column = DB::selectOne("
            SELECT EXTRA
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = 'id'
            LIMIT 1
        ", [$table]);

        if (!$column || str_contains(strtolower((string) $column->EXTRA), 'auto_increment')) {
            return;
        }

        $primaryKey = DB::selectOne("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND INDEX_NAME = 'PRIMARY'
            LIMIT 1
        ", [$table]);

        if (!$primaryKey) {
            DB::statement("ALTER TABLE `{$table}` ADD PRIMARY KEY (`id`)");
        }

        DB::statement("ALTER TABLE `{$table}` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
    }

    public function down(): void
    {
        // No-op: removing AUTO_INCREMENT from a production primary key is unsafe.
    }
};
