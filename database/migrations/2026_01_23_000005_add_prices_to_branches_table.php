<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->decimal('daily_price', 12, 2)->default(0)->after('open_hours');
            $table->decimal('monthly_price', 12, 2)->default(0)->after('daily_price');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['daily_price', 'monthly_price']);
        });
    }
};

