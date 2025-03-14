<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unique_values', function (Blueprint $table): void {
            $table->id();

            $table->string('scope');
            $table->string('value');

            $table->timestamps();

            $table->unique(['scope', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unique_values');
    }
};
