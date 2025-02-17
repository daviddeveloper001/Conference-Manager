<?php

use App\Models\Venue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_published')->default(false);
            $table->string('status');
            $table->string('region');
            $table->foreignId('venue_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};
