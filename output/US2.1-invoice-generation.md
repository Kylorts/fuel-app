# US 2.1 — Penerbitan Tagihan Otomatis (Invoice Generation)

**User Story:**
Sebagai Admin Penjualan, saya ingin menerbitkan tagihan (Invoice) pesanan agar pembeli bisa segera mengetahui rincian biaya.

---

* **Prompt:**
  "Generate a Laravel feature for US 2.1 — Invoice Generation. As admin_penjualan, when I click 'Terbitkan Tagihan' on an approved order, the system must calculate PPN 11%, create a unique invoice number (INV/YYYYMM/0001), change order status to waiting_payment, and render the invoice detail view. The button must be disabled/hidden for non-approved orders or orders that already have an invoice."

* **Context File:**
  - `diagram/er-diagram.puml` — Entity Relationship Diagram (tables: orders, invoices)
  - `diagram/class-diagram.puml` — Class Diagram (Invoice module)
  - `app/Models/Order.php` — Order model with `isApproved()`, `hasInvoice()`, `subtotal()`
  - `app/Models/Invoice.php` — Invoice model with casts and relationships
  - `app/Enums/OrderStatus.php` — OrderStatus enum
  - `app/Enums/InvoiceStatus.php` — InvoiceStatus enum
  - `app/Actions/GenerateInvoiceAction.php` — Business logic action
  - `app/Http/Controllers/InvoiceController.php` — Thin REST controller
  - `app/Policies/InvoicePolicy.php` — Authorization policy
  - `app/Http/Requests/StoreInvoiceRequest.php` — Form Request
  - `resources/views/invoices/show.blade.php` — Invoice detail view
  - `resources/views/invoices/index.blade.php` — Invoice list view
  - `routes/web.php` — Route definitions

* **Skills:**
  - `skills/skill.md` — Laravel project conventions (thin controllers, rich models, Actions, Policies, Enums, Tailwind view patterns)

* **Task:**
  Implement the full invoice generation flow for US 2.1:
  1. Admin navigates to Order Detail page (order must have status `approved`)
  2. Admin clicks "Terbitkan Tagihan" button
  3. System runs `GenerateInvoiceAction::execute(Order, User)`:
     - Calculates subtotal = `volume_liters × unit_price`
     - Calculates PPN = subtotal × 0.11
     - Calculates total = subtotal + PPN
     - Creates `Invoice` record with status `issued` and auto-generated `invoice_number`
     - Updates `Order.status` → `waiting_payment` (wrapped in DB transaction)
  4. System redirects to `invoices.show` with success flash message
  5. Invoice detail page displays full billing breakdown and PDF download button

* **Input:**
  - `@param Order $order` — The approved fuel order (route model binding from `/orders/{order}/invoice`)
  - `@param StoreInvoiceRequest $request` — Authenticated request (carries `$request->user()` as the issuer)

* **Output:**
  - `@return RedirectResponse` — Redirect to `route('invoices.show', $invoice)` with `session('success')` on success
  - `@return Invoice` (from `GenerateInvoiceAction::execute`) — Persisted Invoice Eloquent model
  - `@return View` (from `InvoiceController::show`) — Rendered `invoices/show.blade.php` with invoice data
  - `@return StreamedResponse` (from `InvoiceController::download`) — PDF file stream for download
  - //@return Boolean true — Order status successfully changed to `waiting_payment` in DB

* **Rules:**
  ```
  // [R1] Status Guard — Invoice button only active when order.status = 'approved'
  // Enforced by: InvoicePolicy::create() → $order->isApproved()
  // UI: button disabled/hidden in Blade via @can('create', [Invoice::class, $order])

  // [R2] Duplicate Guard — One invoice per order
  // Enforced by: InvoicePolicy::create() → !$order->hasInvoice()

  // [R3] Role Guard — Only admin_penjualan can issue invoices
  // Enforced by: InvoicePolicy::create() → $user->isAdminPenjualan()

  // [R4] PPN Rate — Fixed at 11% (PPN Indonesia)
  // Enforced by: GenerateInvoiceAction::PPN_RATE = 0.11 (private constant)

  // [R5] Atomicity — Invoice creation and order status change must be atomic
  // Enforced by: DB::transaction() wrapping both writes in GenerateInvoiceAction

  // [R6] Invoice Number Format — INV/YYYYMM/0001 (sequential per month)
  // Enforced by: GenerateInvoiceAction::buildInvoiceNumber()

  // [R7] Monetary precision — All amounts stored as DECIMAL(14,2), never FLOAT
  // Enforced by: migration column types + model casts

  // [R8] Draft/Pending Guard — Disable "Terbitkan Tagihan" for non-approved orders
  // Enforced by: InvoicePolicy (HTTP layer) + button hidden in Blade (UI layer)
  ```

* **What changed:**
  ```
  NEW  app/Actions/GenerateInvoiceAction.php     — Business logic: calculate amounts, create invoice, update order status
  NEW  app/Enums/InvoiceStatus.php               — InvoiceStatus backed enum (issued, paid, cancelled) with label() and badgeClass()
  NEW  app/Enums/OrderStatus.php                 — OrderStatus backed enum with all 7 states, label(), badgeColor()
  NEW  app/Http/Controllers/InvoiceController.php — Thin controller: index, store, show, download
  NEW  app/Http/Requests/StoreInvoiceRequest.php  — Form Request with policy-based authorization
  NEW  app/Models/Company.php                    — Company Eloquent model with users/orders relations
  NEW  app/Models/Invoice.php                    — Invoice model: casts, relations, markAsPaid(), scopes
  NEW  app/Models/Order.php                      — Order model: isApproved(), hasInvoice(), subtotal(), scopes
  NEW  app/Models/VirtualAccount.php             — VirtualAccount model for US 2.2
  NEW  app/Models/PaymentCallback.php            — PaymentCallback model for US 2.3 webhook
  NEW  app/Policies/InvoicePolicy.php            — Authorization: viewAny, view, create, download
  MOD  app/Models/User.php                       — Added role, company_id, relationships, role helper methods
  MOD  app/Providers/AppServiceProvider.php      — Registered InvoicePolicy gate
  NEW  database/migrations/2026_04_11_000001_add_role_to_users_table.php
  NEW  database/migrations/2026_04_11_000002_create_companies_table.php
  NEW  database/migrations/2026_04_11_000003_create_orders_table.php
  NEW  database/migrations/2026_04_11_000004_create_invoices_table.php
  NEW  database/migrations/2026_04_11_000005_create_virtual_accounts_table.php
  NEW  database/migrations/2026_04_11_000006_create_payment_callbacks_table.php
  NEW  resources/views/layouts/app.blade.php     — Base layout with nav, flash messages
  NEW  resources/views/invoices/index.blade.php  — Invoice list table (Tailwind)
  NEW  resources/views/invoices/show.blade.php   — Invoice detail: breakdown, VA info, download button
  MOD  routes/web.php                            — Added auth-guarded invoice routes
  NEW  diagram/er-diagram.puml                   — Full system ER diagram (PlantUML)
  NEW  diagram/class-diagram.puml                — Invoice module class diagram (PlantUML)
  NEW  skills/skill.md                           — Laravel project conventions & patterns
  ```

* **Commit Message:**
  ```
  feat(invoice): implement US 2.1 invoice generation (Penerbitan Tagihan Otomatis)

  - Add GenerateInvoiceAction with PPN 11% calculation and atomic DB transaction
  - Add InvoicePolicy enforcing admin_penjualan role, approved status, and no-duplicate guards
  - Add Order, Invoice, Company, VirtualAccount models with Eloquent casts and scopes
  - Add InvoiceStatus and OrderStatus backed enums with label/badge helpers
  - Add Tailwind views: invoices/index and invoices/show
  - Add migrations: companies, orders, invoices, virtual_accounts, payment_callbacks
  - Register InvoicePolicy in AppServiceProvider
  - Add PlantUML ER diagram and class diagram in diagram/
  - Add skills/skill.md with project conventions
  ```
