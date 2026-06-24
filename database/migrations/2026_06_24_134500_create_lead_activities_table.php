<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLeadActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('activity_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('lead_id');
            $table->index('activity_type');
            $table->index('created_at');
            $table->index('user_id');
            
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });

        // Backfill LEAD_CREATED for existing leads
        $leads = DB::table('leads')->get();
        $now = now();
        $activities = [];
        foreach ($leads as $lead) {
            $activities[] = [
                'lead_id' => $lead->id,
                'user_id' => null,
                'activity_type' => 'LEAD_CREATED',
                'title' => 'Lead Created',
                'description' => 'Lead was captured/created.',
                'metadata' => json_encode(['source' => $lead->source_page]),
                'is_pinned' => false,
                'created_at' => $lead->created_at ?? $now,
                'updated_at' => $lead->created_at ?? $now,
            ];
        }

        // Insert in chunks to avoid memory issues if there are many leads
        foreach (array_chunk($activities, 500) as $chunk) {
            DB::table('lead_activities')->insert($chunk);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_activities');
    }
}
