<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subject_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->text('front_text');
            $table->text('back_text');
            $table->text('memo')->nullable();

            $table->unsignedTinyInteger('mastery_level')
                ->default(1);

            $table->timestamp('last_studied_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'subject_id',
                'category_id',
                'mastery_level',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};