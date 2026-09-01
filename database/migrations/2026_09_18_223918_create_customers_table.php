<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('billing_type')->default('hourly');
            $table->unsignedInteger('hourly_rate')->nullable();
            $table->unsignedInteger('monthly_rate')->nullable();
            $table->decimal('settlement_lower_hours', 5, 2)->nullable();
            $table->decimal('settlement_upper_hours', 5, 2)->nullable();
            $table->unsignedInteger('overtime_unit_price')->nullable();
            $table->unsignedInteger('deduction_unit_price')->nullable();
            $table->string('price_tax_type')->default('exclusive');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};