<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unique_values', function (Blueprint $table): void {
            $table->string('subject')->after('value')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropColumns('unique_values', ['subject']);
    }
};
