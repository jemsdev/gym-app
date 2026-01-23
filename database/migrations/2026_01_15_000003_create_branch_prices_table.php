<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_prices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->decimal('daily_price', 12, 2);
            $table->decimal('monthly_price', 12, 2);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['branch_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_prices');
    }
};

