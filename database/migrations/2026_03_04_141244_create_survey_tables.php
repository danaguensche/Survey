<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->text('question_text');
            $table->enum('type', ['rating', 'text']);
            $table->unsignedTinyInteger('scale_max')->default(6);
            $table->unsignedTinyInteger('order_index');
            $table->timestamps();
        });

        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('ausbildungsberuf')->nullable();
            $table->unsignedTinyInteger('ausbildungsjahr')->nullable();
            $table->date('datum')->nullable();
            $table->boolean('consent')->default(false);
            $table->timestamps();
        });

        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('survey_submissions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('survey_questions');
            $table->unsignedTinyInteger('rating_value')->nullable();
            $table->text('text_value')->nullable();
            $table->timestamps();
        });
    }
};
