# Laravel — Supply Chain Fuel App B2B

## Overview

This skill covers conventions, patterns, and best practices specific to **this project**:
a B2B fuel distribution supply chain system built with **Laravel 12**, **MySQL**, and **Tailwind CSS**.
It governs how to extend any feature — from new User Stories to bug fixes — consistently.

---

## Stack

| Layer        | Technology                             |
|--------------|----------------------------------------|
| Framework    | Laravel 12 (PHP 8.3+)                  |
| Database     | MySQL 8 (dev: SQLite)                  |
| Frontend     | Blade + Tailwind CSS v4 + Vite         |
| Auth         | Laravel built-in (`auth` middleware)   |
| Testing      | PHPUnit / Pest (feature tests)         |
| PDF          | barryvdh/laravel-dompdf                |
| Queues       | Laravel Jobs (database driver)         |

---

## Project Structure

```
app/
├── Actions/          # Single-responsibility business actions (not Services)
├── Enums/            # PHP 8.1+ backed enums for status fields
├── Http/
│   ├── Controllers/  # Thin controllers — REST verbs only
│   └── Requests/     # Form Request classes for all write operations
├── Models/           # Rich Eloquent models with scopes & helpers
├── Policies/         # Gate/Policy authorization
└── Providers/
database/
├── migrations/       # Timestamped, atomic schema changes
└── seeders/
resources/views/
├── layouts/app.blade.php
└── {feature}/        # index.blade.php, show.blade.php, etc.
routes/web.php        # All routes grouped by middleware
diagram/              # PlantUML ER & Class diagrams
output/               # US output spec documents
skills/               # This file
```

---

## Architecture Principles (Taylor Otwell / laravel-architect style)

### Controllers — STAY THIN
- Only contain: `index`, `store`, `show`, `update`, `destroy`, `create`, `edit`
- No business logic — delegate to **Actions**
- Authorize with `$this->authorize(...)` or **Form Requests**
- Return `View`, `RedirectResponse`, or `JsonResponse` only

```php
// GOOD
public function store(StoreInvoiceRequest $request, Order $order): RedirectResponse
{
    $invoice = app(GenerateInvoiceAction::class)->execute($order, $request->user());
    return redirect()->route('invoices.show', $invoice)->with('success', '...');
}

// BAD — business logic belongs in an Action
public function store(Request $request, Order $order): RedirectResponse
{
    $subtotal = $order->volume_liters * $order->unit_price;
    $ppn = $subtotal * 0.11;
    Invoice::create([...]);
}
```

### Models — STAY RICH
- Add named scopes: `scopeApproved`, `scopeWaitingPayment`
- Add state helpers: `isApproved()`, `hasInvoice()`, `subtotal()`
- Use Eloquent casts for Enums and decimals
- NO repository wrappers — use Eloquent directly

### Actions — FOR REUSABLE COMPLEX LOGIC ONLY
- One public method: `execute(...)`
- Use `DB::transaction()` when multiple writes are needed
- Named `{Verb}{Entity}Action`, e.g., `GenerateInvoiceAction`
- Do NOT create Actions for simple CRUD — write it in the controller

### Enums — FOR ALL STATUS FIELDS
- Use PHP 8.1 backed string enums in `app/Enums/`
- Add `label()` and `badgeClass()` / `badgeColor()` helpers
- Cast in model: `'status' => MyStatusEnum::class`

### Policies — FOR ALL AUTHORIZATION
- One policy per model: `InvoicePolicy`, `OrderPolicy`
- Register in `AppServiceProvider::boot()`
- Policy methods map 1:1 to controller actions

### Form Requests — FOR ALL WRITE OPERATIONS
- `authorize()` — call policy or gate check
- `rules()` — validation rules only
- No transformation logic inside Form Requests

---

## Database Conventions

- All tables use `$table->id()` (BIGINT UNSIGNED, AUTO_INCREMENT)
- Foreign keys use `foreignId('x_id')->constrained()` shorthand
- Status columns use `enum(...)` with PHP Enum cast on model
- All monetary columns use `DECIMAL(14,2)` — never `FLOAT`
- All volume columns use `DECIMAL(10,2)`
- Index on `[company_id, status]` for filtered list queries
- Use `$table->timestamps()` on every table

---

## Business Rules (US 2.1 — Invoice Generation)

| Rule | Implementation |
|------|----------------|
| Invoice button only active when order status = `approved` | `InvoicePolicy::create()` checks `$order->isApproved()` |
| PPN rate is fixed at 11% | `GenerateInvoiceAction::PPN_RATE = 0.11` constant |
| One invoice per order | Policy checks `!$order->hasInvoice()` |
| Order status transitions to `waiting_payment` on invoice creation | Done inside `GenerateInvoiceAction` within DB transaction |
| Invoice number format: `INV/YYYYMM/0001` | `buildInvoiceNumber()` in `GenerateInvoiceAction` |
| Only `admin_penjualan` can issue invoices | `InvoicePolicy::create()` checks `$user->isAdminPenjualan()` |

---

## Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Model | PascalCase singular | `Invoice`, `Order` |
| Migration | snake_case verb_noun | `create_invoices_table` |
| Controller | PascalCase plural | `InvoiceController` |
| Policy | PascalCase model + Policy | `InvoicePolicy` |
| Action | Verb + Entity + Action | `GenerateInvoiceAction` |
| View | snake_case in subdirectory | `invoices/show.blade.php` |
| Route name | dot notation | `invoices.show` |
| Enum | PascalCase | `OrderStatus`, `InvoiceStatus` |

---

## View Conventions (Tailwind)

- Layout: `@extends('layouts.app')`
- Section: `@section('content')` … `@endsection`
- Status badges: always use `$enum->badgeClass()` — never hardcode colors
- Tables: `min-w-full divide-y divide-gray-200` in `overflow-hidden rounded-xl border bg-white`
- Buttons: `rounded-lg px-4 py-2.5 text-sm font-semibold text-white` with color class
- Flash messages handled in `layouts/app.blade.php` via `session('success')` / `session('error')`

---

## Route Patterns

```php
// All authenticated routes inside middleware group
Route::middleware(['auth'])->group(function () {

    // List
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    // Create invoice from order (non-standard resource — uses order as parent)
    Route::post('/orders/{order}/invoice', [InvoiceController::class, 'store'])->name('invoices.store');

    // Show
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
});
```

---

## Testing Guidelines

- Write **Feature tests** (not Unit) for HTTP flows
- Test file: `tests/Feature/InvoiceGenerationTest.php`
- Use `actingAs($user)` with seeded users of correct roles
- Assert DB state after each action:
  ```php
  $this->assertDatabaseHas('invoices', ['order_id' => $order->id]);
  $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'waiting_payment']);
  ```
- Test both happy path AND guard clauses (wrong status, wrong role, duplicate invoice)

---

## Adding a New Feature Checklist

1. [ ] Migration — create table or alter existing
2. [ ] Model — fillable, casts, relationships, scopes
3. [ ] Enum — if the model has a status field
4. [ ] Policy — authorization logic
5. [ ] Register policy in `AppServiceProvider`
6. [ ] Form Request — validation + authorize
7. [ ] Controller — thin, delegates to Action if logic is complex
8. [ ] Blade views — index + show minimum
9. [ ] Routes — inside `auth` middleware group
10. [ ] Feature test
