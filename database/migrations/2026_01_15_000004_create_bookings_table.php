<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['daily', 'monthly']);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['PENDING', 'PAID', 'CANCELED', 'EXPIRED'])->default('PENDING');
            $table->string('booking_code', 20)->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->unsignedBigInteger('checked_in_by')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'branch_id', 'type', 'start_date'], 'bookings_unique_user_branch_type_start');
            $table->index(['branch_id', 'status', 'type', 'start_date']);
            $table->index(['user_id', 'status', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

