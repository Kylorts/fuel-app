<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
            $table->decimal('subtotal', 14, 2);
            $table->decimal('ppn_rate', 5, 4)->default(0.1100);
            $table->decimal('ppn_amount', 14, 2);
            $table->decimal('total_amount', 14, 2);
            $table->enum('status', ['issued', 'paid', 'cancelled'])->default('issued');
            $table->string('pdf_path', 255)->nullable();
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['order_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
