<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Repair production schema drift affecting CMS Course Create.
     *
     * Course Create stores an uploaded thumbnail in media_assets first, then
     * creates the cms_courses row. If either table's id column is missing
     * AUTO_INCREMENT, MySQL rejects the insert with:
     * "Field 'id' doesn't have a default value".
     */
    public function up(): void
    {
        foreach (['media_assets', 'cms_courses'] as $table) {
            $this->ensureAutoIncrementId($table);
        }
    }

    public function down(): void
    {
        // No-op by design. Removing AUTO_INCREMENT from production primary keys
        // would break course thumbnail uploads and course creation.
    }

    private function ensureAutoIncrementId(string $table): void
    {
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
};
