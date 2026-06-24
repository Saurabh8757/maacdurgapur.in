<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->unsignedBigInteger('followup_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('provider')->default('twilio');
            $table->enum('direction', ['outbound', 'inbound'])->default('outbound');
            $table->string('phone');
            $table->enum('message_type', ['text', 'template', 'media'])->default('text');
            $table->text('message');
            $table->string('provider_message_id')->nullable();
            $table->enum('status', ['queued', 'sent', 'delivered', 'read', 'failed'])->default('queued');
            $table->json('metadata')->nullable();
            $table->integer('retry_count')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('lead_id');
            $table->index('followup_id');
            $table->index('status');
            $table->index('phone');
            $table->index('provider_message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
