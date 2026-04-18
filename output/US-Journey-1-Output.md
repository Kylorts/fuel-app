# US Journey 1 — Pemesanan & Approval Kuota Bahan Bakar oleh Klien

**User Journey:**
Staf Pembeli login → mengisi form pesanan bahan bakar → Manajer Pembeli menyetujui/menolak → Sistem meneruskan ke Admin Depo.

---

* **Prompt:**
  1. ```
     You are software engineer expert and also expert at laravel. First, analyze the User Journey 1
     and all of user story 1.x in tugas 3 kelompok 9.pdf. Make ER database diagram and class
     diagram, visualize to me first. Don't overcomplicate things, make things simple, and don't add
     unnecessary in diagram. Once i accept diagram, create the PlantUML ER diagram database and
     Class diagrams in folder diagram and name it US-Journey-1-(ER/Class diagram). Wait for my
     approval or proceed to generate the Laravel MVC code using Tailwind and MySQL once the
     architecture is solid. Code you generated must follow two diagram you created and don't add
     things that are not in the diagram. Follow laravel best practice in skill.md. last, i want you
     to US-Journey-1-Output.md like US2.1-invoice-generation.md and put that in folder output.
     ```
  2. ```
     There are Undefined constant App\Enums\OrderStatus::REJECTED, fix that. After that, add fuel
     type in order and also make input number accept only number. Add the order history in invoice
     list so that staff can see the progress of their order.
     ```
  3. ```
     There error SQLSTATE[01000]: Warning: 1265 Data truncated for column 'status' at row update
     orders set status = rejected, rejection_reason = jelek, orders. change in code migration for
     new status.
     ```

* **Context File:**
  ```
  [Attached Documents]
  @tugas 3 kelompok 9.pdf  — Project requirements containing User Journey 1, US 1.1–1.4:
                             Autentikasi Klien, Pengajuan Kuota Bahan Bakar, Persetujuan
                             Pesanan oleh Manager, Pantauan Status Pesanan Klien

  [Generated Source Files]
  app/Enums/OrderStatus.php                       — Added SENT_TO_DEPOT + REJECTED cases + label/badge
  app/Models/Order.php                            — Added isPendingApproval(), scopePendingApproval()
  app/Policies/OrderPolicy.php                    — create, approve, reject authorization
  app/Http/Controllers/OrderController.php        — Added create, store, approve, reject, buildOrderNumber()
  app/Http/Controllers/InvoiceController.php      — Added company scoping to index()
  app/Http/Requests/StoreOrderRequest.php         — fuel_type, volume_liters, unit_price, delivery_location, scheduled_at
  app/Http/Requests/RejectOrderRequest.php        — rejection_reason validation
  app/Actions/ApproveOrderAction.php              — DB transaction: status → approved + approved_by/at
  app/Providers/AppServiceProvider.php            — Registered OrderPolicy for Order::class
  resources/views/orders/create.blade.php         — Order creation form with fuel type dropdown + number-only inputs
  resources/views/orders/index.blade.php          — Added "Buat Pesanan" button for staf_pembeli
  resources/views/orders/show.blade.php           — Added approve/reject action area for manajer_pembeli
  resources/views/invoices/index.blade.php        — Added "Status Pesanan" column showing order status badge
  routes/web.php                                  — Added orders.create, orders.store, orders.approve, orders.reject
  diagram/US-Journey-1-ER-diagram.puml            — ER diagram: companies, users, orders (3 entities)
  diagram/US-Journey-1-Class-diagram.puml         — Class diagram: controllers, models, policy, requests, action
  ```

* **Skills:**
  - `skills/skill.md` — Laravel project conventions (thin controllers, rich models, Actions, Policies, Enums, Tailwind)

* **Task:**
  1. Analyze User Journey 1 (US 1.1–1.4) from PDF: login, order submission, manager approval/rejection, order status monitoring
  2. Generate PlantUML ER diagram (3 entities: companies, users, orders) — no new tables needed
  3. Generate PlantUML Class diagram: AuthController, OrderController, User/Company/Order models, OrderStatus enum, OrderPolicy, StoreOrderRequest, RejectOrderRequest, ApproveOrderAction
  4. Implement Laravel MVC for Journey 1:
     - `OrderPolicy`: enforces role guard (staf_pembeli → create, manajer_pembeli → approve/reject), company scoping, status guards
     - `StoreOrderRequest`: validates fuel_type (in enum list), volume_liters > 0, delivery_location not empty, scheduled_at in future
     - `RejectOrderRequest`: requires rejection_reason
     - `ApproveOrderAction`: atomically sets status → approved, approved_by, approved_at in DB::transaction
     - `OrderController`: thin REST controller — create, store, approve, reject methods added
     - `orders/create.blade.php`: Tailwind form for staf_pembeli with fuel type dropdown and number-only inputs
     - `orders/show.blade.php`: approve/reject action area for manajer_pembeli (US 1.3)
     - `orders/index.blade.php`: "Buat Pesanan" button visible only to staf_pembeli (US 1.4 empty-state shortcut)
  5. Bug fix: Add missing `REJECTED` case to `OrderStatus` enum (caused `Undefined constant` runtime error)
  6. Enhancement: Add `fuel_type` dropdown to order creation form (Solar, Solar B30, Solar Industri, Pertamax, Pertalite)
  7. Enhancement: Enforce number-only input on `volume_liters` and `unit_price` fields via JS + regex
  8. Enhancement: Add "Status Pesanan" column to invoice list and scope invoices to user's company for non-admin roles

* **Input:**
  - `@param StoreOrderRequest $request` — Authenticated HTTP request from staf_pembeli; carries fuel_type, volume_liters, unit_price, delivery_location, scheduled_at
  - `@param Order $order` — Route model bound order resolved from `{order}` segment
  - `@param RejectOrderRequest $request` — Carries rejection_reason string (required, max 500)

* **Output:**
  - `@return RedirectResponse` — `orders.store`: redirect to `route('orders.show', $order)` with session('success') on creation
  - `@return RedirectResponse` — `orders.approve`: redirect to `route('orders.show', $order)` with session('success') on approval
  - `@return RedirectResponse` — `orders.reject`: redirect to `route('orders.show', $order)` with session('error') on rejection
  - `@return View` — `orders.create`: renders `orders/create.blade.php` with fuel type dropdown and number-only inputs
  - `@return View` — `orders.show`: renders `orders/show.blade.php` with approval/rejection area visible only to eligible manager
  - `@return View` — `invoices.index`: renders invoice list scoped to user's company; includes "Status Pesanan" column
  - `@return Order` (from `ApproveOrderAction::execute`) — Freshly loaded Order model with updated status, approved_by, approved_at
  - //@return Boolean true — `orders.status` column changed to `pending_approval` atomically on store
  - //@return Boolean true — `orders.status` changed to `approved` + `approved_by` + `approved_at` set atomically in ApproveOrderAction

* **Rules:**
  ```
  // [R1] Volume Guard — Jumlah liter bahan bakar harus > 0
  // Enforced by: StoreOrderRequest::rules() → 'volume_liters' => ['required', 'numeric', 'gt:0']
  // Error message: "Jumlah liter bahan bakar harus lebih dari 0"

  // [R2] Location Guard — Tujuan pengiriman tidak boleh kosong
  // Enforced by: StoreOrderRequest::rules() → 'delivery_location' => ['required', 'string', 'max:255']

  // [R3] Schedule Guard — Jadwal pengiriman harus di masa mendatang
  // Enforced by: StoreOrderRequest::rules() → 'scheduled_at' => ['required', 'date', 'after:now']

  // [R4] Create Role Guard — Hanya staf_pembeli dengan company_id yang bisa submit pesanan
  // Enforced by: OrderPolicy::create() → $user->isStafPembeli() && $user->company_id !== null
  // UI: route 'orders.create' returns 403 for non staf_pembeli

  // [R5] Approve Role Guard — Hanya manajer_pembeli dari perusahaan yang sama bisa menyetujui
  // Enforced by: OrderPolicy::approve() → $user->isManajerPembeli() && $user->company_id === $order->company_id
  // UI: approve button shown only when $canApprove = true in orders/show.blade.php

  // [R6] Approve Status Guard — Pesanan harus berstatus pending_approval untuk dapat disetujui
  // Enforced by: OrderPolicy::approve() → $order->isPendingApproval()

  // [R7] Reject Role Guard — Hanya manajer_pembeli dari perusahaan yang sama bisa menolak
  // Enforced by: OrderPolicy::reject() → $user->isManajerPembeli() && $user->company_id === $order->company_id

  // [R8] Reject Reason Guard — Alasan penolakan wajib diisi
  // Enforced by: RejectOrderRequest::rules() → 'rejection_reason' => ['required', 'string', 'max:500']

  // [R9] Atomicity — Status transition to approved is wrapped in DB::transaction
  // Enforced by: ApproveOrderAction::execute() uses DB::transaction()

  // [R10] Company Scoping — Non-admin users can only view their own company's orders and invoices
  // Enforced by: OrderController::show() → abort_unless($order->company_id === $user->company_id, 403)
  //              OrderController::index() → filters by company_id for non-admin
  //              InvoiceController::index() → whereHas('order', company_id filter) for non-admin

  // [R11] Authentication Guard — All order routes require authenticated session
  // Enforced by: Route::middleware(['auth']) group in routes/web.php

  // [R12] Order Number Format — ORD/YYYYMM/NNNN (sequential per calendar month)
  // Enforced by: OrderController::buildOrderNumber() — private method using ORDER BY + substr

  // [R13] Fuel Type Guard — Jenis bahan bakar harus salah satu dari daftar yang valid
  // Enforced by: StoreOrderRequest::rules() → 'fuel_type' => ['required', 'string', 'in:Solar,Solar B30,Solar Industri,Pertamax,Pertalite']
  // UI: <select> dropdown with fixed options in orders/create.blade.php

  // [R14] Numeric Input Guard — Input volume_liters dan unit_price hanya menerima angka
  // Enforced by: JS onkeydown="return allowNumbersOnly(event)" + oninput regex replacement
  // Allows: digits 0–9, Backspace, Delete, Tab, Escape, Enter, Arrow keys, Ctrl+A/C/V/X
  ```

* **What changed:**
  ```
  [Journey 1 — Diagrams]
  NEW  diagram/US-Journey-1-ER-diagram.puml         — 3-entity ER diagram (companies, users, orders);
                                                       scoped to Journey 1 only; no new tables needed
  NEW  diagram/US-Journey-1-Class-diagram.puml      — Full class diagram for Journey 1 MVC:
                                                       AuthController, OrderController, User/Company/Order
                                                       models, OrderStatus enum, OrderPolicy,
                                                       StoreOrderRequest, RejectOrderRequest, ApproveOrderAction

  [Journey 1 — Laravel MVC]
  MOD  app/Enums/OrderStatus.php                    — Added SENT_TO_DEPOT = 'sent_to_depot' case (label/badge);
                                                       Added REJECTED = 'rejected' case (label/badge) — bug fix
                                                       for Undefined constant runtime error
  MOD  database/migrations/                         — create_orders_table: added 'rejected' and 'sent_to_depot'
  2026_04_11_000003_create_orders_table.php           to status ENUM — fixes SQLSTATE[01000] Data truncated
                                                       error when saving rejected status to MySQL
  MOD  app/Models/Order.php                         — Added isPendingApproval(): bool;
                                                       added scopePendingApproval(Builder): Builder
  NEW  app/Policies/OrderPolicy.php                 — create(User), approve(User, Order), reject(User, Order)
                                                       role + company + status guards
  NEW  app/Http/Requests/StoreOrderRequest.php      — Validates: fuel_type (in enum list), volume_liters (gt:0),
                                                       unit_price (gt:0), delivery_location (required),
                                                       scheduled_at (after:now)
  NEW  app/Http/Requests/RejectOrderRequest.php     — Validates: rejection_reason (required, max:500)
  NEW  app/Actions/ApproveOrderAction.php           — execute(Order, User): Order — DB::transaction wraps
                                                       status update + approved_by + approved_at
  MOD  app/Http/Controllers/OrderController.php     — Added create(), store(StoreOrderRequest),
                                                       approve(Order), reject(RejectOrderRequest, Order),
                                                       buildOrderNumber() private helper
  MOD  app/Http/Controllers/InvoiceController.php   — index(): added when(!isAdminPenjualan()) company scope
                                                       via whereHas('order', company_id filter)
  MOD  app/Providers/AppServiceProvider.php         — Added Gate::policy(Order::class, OrderPolicy::class)
  MOD  routes/web.php                               — Added: GET /orders/create (orders.create),
                                                       POST /orders (orders.store),
                                                       POST /orders/{order}/approve (orders.approve),
                                                       POST /orders/{order}/reject (orders.reject)
  NEW  resources/views/orders/create.blade.php      — Order creation form: fuel_type <select> dropdown
                                                       (Solar/Solar B30/Solar Industri/Pertamax/Pertalite);
                                                       volume_liters + unit_price with number-only enforcement
                                                       (type="text" + inputmode="numeric" + JS allowNumbersOnly);
                                                       delivery_location; scheduled_at; inline validation errors;
                                                       "Ajukan Pesanan" submit button
  MOD  resources/views/orders/index.blade.php       — Added "+ Buat Pesanan" button in header,
                                                       visible only to staf_pembeli (US 1.4 shortcut)
  MOD  resources/views/orders/show.blade.php        — Added approve/reject action area above US 2.1 area;
                                                       visible only when $canApprove || $canReject;
                                                       reject includes inline input for rejection_reason;
                                                       added 'sent_to_depot' and 'rejected' to colorMap
  MOD  resources/views/invoices/index.blade.php     — Added "Status Pesanan" column before "Status Invoice";
                                                       displays order status badge using OrderStatus::label()
                                                       and badgeColor() color mapping; colspan updated to 8
  ```

* **Commit Message:**
  ```
  feat(us1.x): implement order submission, approval flow, and invoice order history (Journey 1)

  - Add OrderPolicy: create guard (staf_pembeli + company_id), approve/reject guards
    (manajer_pembeli + same company + pending_approval status)
  - Add StoreOrderRequest: validates fuel_type (enum list), volume_liters > 0, unit_price,
    delivery_location, scheduled_at after:now
  - Add RejectOrderRequest: requires rejection_reason (max 500 chars)
  - Add ApproveOrderAction: atomically transitions order status to approved with approved_by + approved_at
  - Extend OrderController: create, store (ORD/YYYYMM/NNNN numbering), approve, reject methods
  - Register OrderPolicy in AppServiceProvider
  - Add routes: orders.create, orders.store, orders.approve, orders.reject
  - Add orders/create.blade.php: fuel_type <select> dropdown; number-only inputs for volume/price
    via type="text" + inputmode="numeric" + JS allowNumbersOnly() function
  - Update orders/show.blade.php: approve/reject panel for manajer_pembeli (US 1.3)
  - Update orders/index.blade.php: "Buat Pesanan" button for staf_pembeli (US 1.4)
  - Fix OrderStatus enum: add missing REJECTED case (fixes Undefined constant runtime error)
  - Extend OrderStatus enum: add SENT_TO_DEPOT case with label + badgeColor
  - Fix migration create_orders_table: add 'rejected' and 'sent_to_depot' to status ENUM
    (fixes SQLSTATE[01000] Data truncated warning on reject update)
  - Extend Order model: add isPendingApproval(), scopePendingApproval()
  - Update InvoiceController::index(): scope invoices to user's company for non-admin roles
  - Update invoices/index.blade.php: add "Status Pesanan" column showing order status badge
  - Add PlantUML ER diagram (3 entities) and Class diagram for Journey 1
  ```
