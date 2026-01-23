<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_checkins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->timestamp('checked_in_at');
            $table->unsignedBigInteger('checked_in_by')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_checkins');
    }
};

