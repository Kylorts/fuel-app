<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApproveOrderAction
{
    /**
     * Approve an order: transition status to approved and record approver.
     * Uses a DB transaction to guarantee atomicity.
     */
    public function execute(Order $order, User $approver): Order
    {
        DB::transaction(function () use ($order, $approver) {
            $order->update([
                'status'      => OrderStatus::APPROVED,
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);
        });

        return $order->fresh();
    }
}
