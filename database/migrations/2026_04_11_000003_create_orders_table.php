<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('fuel_type', 50)->default('Solar');
            $table->decimal('volume_liters', 10, 2);
            $table->decimal('unit_price', 12, 2);
            $table->string('delivery_location');
            $table->dateTime('scheduled_at');
            $table->enum('status', [
                'draft',
                'pending_approval',
                'approved',
                'waiting_payment',
                'paid',
                'cancelled',
                'expired',
            ])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
