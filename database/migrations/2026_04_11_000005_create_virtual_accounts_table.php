<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('va_number', 20)->unique();
            $table->string('bank_code', 10);
            $table->decimal('amount', 14, 2);
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'paid', 'expired', 'cancelled'])->default('active');
            $table->timestamps();

            $table->index(['invoice_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
    }
};
