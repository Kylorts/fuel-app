<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /** Only staf_pembeli (belonging to a company) can submit a new order. */
    public function create(User $user): bool
    {
        return $user->isStafPembeli() && $user->company_id !== null;
    }

    /** Only manajer_pembeli of the same company can approve. */
    public function approve(User $user, Order $order): bool
    {
        return $user->isManajerPembeli()
            && $user->company_id === $order->company_id
            && $order->isPendingApproval();
    }

    /** Only manajer_pembeli of the same company can reject. */
    public function reject(User $user, Order $order): bool
    {
        return $user->isManajerPembeli()
            && $user->company_id === $order->company_id
            && $order->isPendingApproval();
    }
}
