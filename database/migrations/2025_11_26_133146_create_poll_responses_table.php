<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('poll_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('poll_question_id')->constrained()->onDelete('cascade');
            $table->foreignId('poll_question_option_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('text_response')->nullable();
            $table->decimal('number_response', 10, 2)->nullable();
            $table->timestamps();

            $table->index(['poll_id', 'user_id']);
            $table->index('poll_question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_responses');
    }
};
