<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Repair production schema drift where imported CMS tables can have an
     * integer primary key without AUTO_INCREMENT, causing create actions to fail.
     */
    public function up(): void
    {
        foreach ([
            'cms_faq_categories',
            'cms_faqs',
            'cms_features',
        ] as $table) {
            $this->ensureAutoIncrementId($table);
        }
    }

    public function down(): void
    {
        // No-op by design. Removing AUTO_INCREMENT from production primary keys
        // would break create operations and may damage existing relationships.
    }

    private function ensureAutoIncrementId(string $table): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $column = DB::selectOne("
            SELECT COLUMN_TYPE, EXTRA
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = 'id'
            LIMIT 1
        ", [$table]);

        if (!$column) {
            return;
        }

        if (str_contains(strtolower((string) $column->EXTRA), 'auto_increment')) {
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
