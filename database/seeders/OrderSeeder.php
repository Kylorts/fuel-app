<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $admin    = User::where('email', 'admin@fuelapp.com')->first();
        $ptAbc    = Company::where('email', 'purchasing@ptabc.com')->first();
        $ptMbl    = Company::where('email', 'procurement@mbl.co.id')->first();
        $ptEnp    = Company::where('email', 'finance@enprima.com')->first();
        $manajerAbc = User::where('email', 'manajer@ptabc.com')->first();
        $manajerMbl = User::where('email', 'manajer@mbl.co.id')->first();
        $manajerEnp = User::where('email', 'manajer@enprima.com')->first();
        $stafAbc    = User::where('email', 'staf@ptabc.com')->first();
        $stafMbl    = User::where('email', 'staf@mbl.co.id')->first();
        $stafEnp    = User::where('email', 'staf@enprima.com')->first();

        // ── 1. APPROVED — Ready for invoice (main US 2.1 test case) ─────────
        $this->createOrder('ORD/202604/0001', $ptAbc, $stafAbc, OrderStatus::APPROVED,
            'Solar B30', 10000, 11500, 'Terminal BBM Plumpang, Jakarta Utara',
            $manajerAbc);

        $this->createOrder('ORD/202604/0002', $ptMbl, $stafMbl, OrderStatus::APPROVED,
            'Solar Industri', 20000, 12000, 'Depot PT MBL, Cilincing',
            $manajerMbl);

        $this->createOrder('ORD/202604/0003', $ptEnp, $stafEnp, OrderStatus::APPROVED,
            'Pertalite', 8000, 10500, 'Kawasan Industri MM2100, Bekasi',
            $manajerEnp);

        // ── 2. WAITING_PAYMENT — Invoice already issued ──────────────────────
        $order4 = $this->createOrder('ORD/202604/0004', $ptAbc, $stafAbc, OrderStatus::WAITING_PAYMENT,
            'Pertamax', 5000, 13500, 'SPBU 34-151, Tangerang',
            $manajerAbc);

        $this->issueInvoice($order4, $admin, 'INV/202604/0001');

        $order5 = $this->createOrder('ORD/202604/0005', $ptMbl, $stafMbl, OrderStatus::WAITING_PAYMENT,
            'Solar B30', 15000, 11000, 'Pelabuhan Tanjung Priok',
            $manajerMbl);

        $this->issueInvoice($order5, $admin, 'INV/202604/0002');

        // ── 3. PAID — Already settled ────────────────────────────────────────
        $order6 = $this->createOrder('ORD/202603/0001', $ptEnp, $stafEnp, OrderStatus::PAID,
            'Solar Industri', 12000, 11500, 'Kawasan Industri Jababeka',
            $manajerEnp);

        $inv = $this->issueInvoice($order6, $admin, 'INV/202603/0001');
        $inv->markAsPaid(now()->subDays(5));

        // ── 4. PENDING_APPROVAL — Not yet approved (button must be disabled) ─
        $this->createOrder('ORD/202604/0006', $ptAbc, $stafAbc, OrderStatus::PENDING_APPROVAL,
            'Pertamax', 6000, 13500, 'SPBU 34-301, Depok');

        // ── 5. DRAFT ─────────────────────────────────────────────────────────
        $this->createOrder('ORD/202604/0007', $ptMbl, $stafMbl, OrderStatus::DRAFT,
            'Solar B30', 9000, 11000, 'Gudang PT MBL, Bekasi');

        // ── 6. CANCELLED ─────────────────────────────────────────────────────
        $this->createOrder('ORD/202603/0002', $ptEnp, $stafEnp, OrderStatus::CANCELLED,
            'Pertalite', 4000, 10500, 'SPBU 34-550, Bogor');
    }

    private function createOrder(
        string      $orderNumber,
        \App\Models\Company $company,
        User        $creator,
        OrderStatus $status,
        string      $fuelType,
        float       $volume,
        float       $unitPrice,
        string      $location,
        ?User       $approver = null,
    ): Order {
        return Order::create([
            'order_number'      => $orderNumber,
            'company_id'        => $company->id,
            'created_by'        => $creator->id,
            'approved_by'       => $approver?->id,
            'fuel_type'         => $fuelType,
            'volume_liters'     => $volume,
            'unit_price'        => $unitPrice,
            'delivery_location' => $location,
            'scheduled_at'      => now()->addDays(7),
            'status'            => $status,
            'approved_at'       => $approver ? now()->subHours(3) : null,
        ]);
    }

    private function issueInvoice(Order $order, User $admin, string $invoiceNumber): Invoice
    {
        $subtotal  = (float) bcmul($order->volume_liters, $order->unit_price, 2);
        $ppnAmount = round($subtotal * 0.11, 2);
        $total     = round($subtotal + $ppnAmount, 2);

        return Invoice::create([
            'invoice_number' => $invoiceNumber,
            'order_id'       => $order->id,
            'issued_by'      => $admin->id,
            'subtotal'       => $subtotal,
            'ppn_rate'       => 0.11,
            'ppn_amount'     => $ppnAmount,
            'total_amount'   => $total,
            'status'         => 'issued',
            'issued_at'      => now()->subHours(2),
        ]);
    }
}
