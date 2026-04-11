<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_callbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_account_id')->constrained()->cascadeOnDelete();
            $table->string('bank_reference', 100);
            $table->decimal('amount_received', 14, 2);
            $table->timestamp('received_at');
            $table->boolean('is_valid')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_callbacks');
    }
};
