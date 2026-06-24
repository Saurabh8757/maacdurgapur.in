<?php
    $activities = \App\Models\LeadActivity::all();
    $duplicates = \App\Models\LeadActivity::where('activity_type', 'LEAD_CREATED')->select('lead_id')->groupBy('lead_id')->havingRaw('COUNT(*) > 1')->get();
    echo 'Total Activities: ' . $activities->count() . "\n";
    echo 'Duplicates: ' . $duplicates->count() . "\n";
    $first = \App\Models\LeadActivity::first();
    echo 'First Activity Created At: ' . $first->created_at . "\n";
    $lead = \App\Models\Lead::find($first->lead_id);
    echo 'Lead Created At: ' . $lead->created_at . "\n";
    echo 'Metadata Type: ' . gettype($first->metadata) . "\n";
    print_r($first->metadata);
