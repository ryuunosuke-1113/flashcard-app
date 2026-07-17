<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table
                ->text('front_text')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        DB::table('cards')
            ->whereNull('front_text')
            ->update([
                'front_text' => '',
            ]);

        Schema::table('cards', function (Blueprint $table) {
            $table
                ->text('front_text')
                ->nullable(false)
                ->change();
        });
    }
};