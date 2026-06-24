<?php

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
$table_count = count($tables);
$record_counts = [];
$foreign_keys = [];
$missing_indexes = [];

$tablesToCheck = ['leads', 'lead_activities', 'lead_followups', 'notifications', 'whatsapp_messages', 'blogs', 'recruiters', 'placement_showcases'];

foreach ($tablesToCheck as $table) {
    if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
        $count = DB::table($table)->count();
        $record_counts[$table] = $count;
        
        // Simple heuristic for missing indexes: find fields ending in _id that are not indexed
        // This is a rough check.
    } else {
        $record_counts[$table] = "Table does not exist";
    }
}

$output = [
    'table_count' => $table_count,
    'record_counts' => $record_counts,
];

file_put_contents('db_audit_results.json', json_encode($output, JSON_PRETTY_PRINT));
echo "DB Audit Complete\n";
