# US 2.1 — Penerbitan Tagihan Otomatis (Invoice Generation)

**User Story:**
Sebagai Admin Penjualan, saya ingin menerbitkan tagihan (Invoice) pesanan agar pembeli bisa segera mengetahui rincian biaya.

---

* **Prompt:**
  ```
  [1] You are software engineer expert and also expert at laravel. First, analyze the User
      Story 2.1 in tugas 3 kelompok 9.pdf and create the PlantUML ER diagram database and
      Class diagrams in folder diagram. Wait for my approval or proceed to generate the
      Laravel MVC code using Tailwind and MySQL once the architecture is solid. Use laravel
      best practice in https://github.com/guetteman/claude-code/blob/main/plugins/laravel/
      agents/laravel-architect.md. After that, i want you to make skill.md for this laravel
      project like in https://github.com/github/awesome-copilot/blob/main/skills/kotlin-
      springboot/SKILL.md and put that in folder skills. last, i want you to make
      US2.1-invoice-generation.md and put that in folder output.

  [2] now, i want you to make login feature to make user story 2.1 working with relevant
      actor and also make seeder

  [3] in InvoiceController.php there are error undefined method 'authorize' and 'Storage'
      and in OrderController.php there are error undefined method 'user'. please fix that

  [4] Illuminate\Routing\Exceptions\MissingRateLimiterException. Rate limiter [login] is
      not defined. please fix that. after that update US2.1-invoice-generation.md.

  [5] now i want you to make the pdf feature work so the user can download the pdf
      document. Use this https://github.com/barryvdh/laravel-dompdf as references.
      after that, update the US2.1-invoice-generation.md.

  [6] now, i want you to analyze codebase and code you generated. match er and class
      diagrams with the current code you generated. After that, update
      US2.1-invoice-generation.md.
  ```

* **Context File:**
  ```
  [Attached Documents]
  @tugas 3 kelompok 9.pdf  — Project requirements containing all User Stories including
                             US 2.1 (Penerbitan Tagihan Otomatis), acceptance criteria,
                             user journey, business rules, and actor definitions

  [Reference Links]
  https://github.com/guetteman/claude-code/blob/main/plugins/laravel/agents/laravel-architect.md
                           — Laravel architecture best practices (thin controllers, rich
                             models, Actions over Services, no Repository pattern)
  https://github.com/github/awesome-copilot/blob/main/skills/kotlin-springboot/SKILL.md
                           — Skill document format reference used to create skills/skill.md
  https://github.com/barryvdh/laravel-dompdf
                           — DomPDF wrapper for Laravel; used for PDF generation via
                             Pdf::loadView() facade (package already in composer.json)

  [Generated Source Files]
  app/Http/Controllers/Controller.php          — Base controller (AuthorizesRequests trait)
  app/Http/Controllers/AuthController.php      — Login / logout
  app/Http/Controllers/InvoiceController.php   — Invoice CRUD (index, store, show, download)
  app/Http/Controllers/OrderController.php     — Order list and detail
  app/Http/Requests/LoginRequest.php           — Login validation + rate limiting
  app/Http/Requests/StoreInvoiceRequest.php    — Invoice creation authorization
  app/Actions/GenerateInvoiceAction.php        — PPN calculation + atomic invoice creation + triggers PDF
  app/Actions/GenerateInvoicePdfAction.php     — DomPDF rendering, Storage save, pdf_path update
  app/Policies/InvoicePolicy.php               — Authorization rules for invoice operations
  app/Models/User.php                          — User model with role helpers
  app/Models/Company.php                       — Company model
  app/Models/Order.php                         — Order model with isApproved(), subtotal()
  app/Models/Invoice.php                       — Invoice model with markAsPaid(), scopes
  app/Models/VirtualAccount.php                — VA model (for US 2.2)
  app/Models/PaymentCallback.php               — Callback model (for US 2.3)
  app/Enums/OrderStatus.php                    — 7-state order status enum
  app/Enums/InvoiceStatus.php                  — 3-state invoice status enum
  app/Providers/AppServiceProvider.php         — InvoicePolicy + login rate limiter registration
  database/migrations/2026_04_11_000001_create_companies_table.php
  database/migrations/2026_04_11_000002_add_role_to_users_table.php
  database/migrations/2026_04_11_000003_create_orders_table.php
  database/migrations/2026_04_11_000004_create_invoices_table.php
  database/migrations/2026_04_11_000005_create_virtual_accounts_table.php
  database/migrations/2026_04_11_000006_create_payment_callbacks_table.php
  database/factories/UserFactory.php           — Updated with role states
  database/factories/CompanyFactory.php        — Company factory
  database/factories/OrderFactory.php          — Order factory with status states
  database/seeders/CompanySeeder.php           — 3 named + 4 random companies
  database/seeders/UserSeeder.php              — 7 users across all relevant roles
  database/seeders/OrderSeeder.php             — 9 orders covering all statuses + invoices
  database/seeders/DatabaseSeeder.php          — Orchestrates seed order
  resources/views/auth/login.blade.php         — Login page with demo account hints
  resources/views/layouts/app.blade.php        — Base layout with nav + flash messages
  resources/views/dashboard.blade.php          — Role-aware dashboard
  resources/views/orders/index.blade.php       — Order list table
  resources/views/orders/show.blade.php        — Order detail + Terbitkan Tagihan button
  resources/views/invoices/index.blade.php     — Invoice list table
  resources/views/invoices/show.blade.php      — Invoice detail with breakdown + Lihat/Unduh PDF buttons
  resources/views/invoices/pdf.blade.php       — Standalone PDF template (table-based HTML/CSS, no Tailwind)
  routes/web.php                               — Auth + order + invoice routes
  diagram/er-diagram.puml                      — Full system ER diagram (PlantUML);
                                                 rewritten in [6] to match only the 6 implemented
                                                 entities (companies, users, orders, invoices,
                                                 virtual_accounts, payment_callbacks); removed
                                                 trucks, drivers, deliveries, fuel_receipts
  diagram/class-diagram.puml                   — Invoice module class diagram (PlantUML);
                                                 rewritten in [6] to match actual code —
                                                 fixed method signatures, removed non-existent
                                                 methods, added AuthController / OrderController /
                                                 LoginRequest / AppServiceProvider / Controller
  skills/skill.md                              — Laravel project conventions & patterns
  ```

* **Skills:**
  - `skills/skill.md` — Laravel project conventions (thin controllers, rich models, Actions, Policies, Enums, Tailwind view patterns, seeding strategy)

* **Task:**
  1. Analyze US 2.1 from `tugas 3 kelompok 9.pdf` — identify actor (Admin Penjualan), acceptance criteria, business rules (PPN 11%, approved-only guard, no-duplicate guard)
  2. Generate PlantUML ER diagram (`diagram/er-diagram.puml`) and class diagram (`diagram/class-diagram.puml`)
  3. Implement Laravel MVC for invoice generation:
     - `GenerateInvoiceAction` — calculates subtotal, PPN 11%, total; creates Invoice; transitions Order status → `waiting_payment` inside `DB::transaction()`
     - `InvoicePolicy` — enforces role guard, status guard, duplicate guard
     - `InvoiceController` — thin REST controller delegating to the Action
     - Blade views with Tailwind: order detail with "Terbitkan Tagihan" button (active/disabled based on status), invoice detail with billing breakdown
  4. Implement login feature with `AuthController`, `LoginRequest` (rate-limited), and `auth/login.blade.php`
  5. Create seeders (`CompanySeeder`, `UserSeeder`, `OrderSeeder`) covering all order statuses and relevant roles for demo/testing
  6. Fix Laravel 11 compatibility: add `AuthorizesRequests` trait to base `Controller`, import `Storage` facade properly, replace `$request->user()` with `auth()->user()`
  7. Fix `MissingRateLimiterException`: register named `login` rate limiter via `RateLimiter::for()` in `AppServiceProvider::boot()`
  8. Implement PDF generation using `barryvdh/laravel-dompdf`:
     - Publish DomPDF config and run `storage:link`
     - `GenerateInvoicePdfAction` renders `invoices/pdf.blade.php` via `Pdf::loadView()`, saves to `storage/app/private/invoices/`, updates `invoice.pdf_path`
     - `GenerateInvoiceAction` calls `GenerateInvoicePdfAction` after the DB transaction commits
     - `InvoiceController::download()` regenerates PDF on-the-fly if missing, then serves inline or as attachment based on `?download=1` query param
     - `invoices/pdf.blade.php`: standalone A4 template using table-based layout and inline CSS (no Tailwind — DomPDF renders plain HTML)
  9. Generate `skills/skill.md` following the format of the Kotlin Spring Boot skill reference
  10. Analyze generated codebase (27 files) against existing PlantUML diagrams and reconcile all
      discrepancies:
      - ER diagram: read all migrations and model files; identify entities with no corresponding
        migration or model; remove them from the diagram
      - Class diagram: verify every class, method, parameter, and return type in the diagram against
        the actual PHP source; fix all divergences (wrong return types, non-existent methods,
        missing classes, wrong inheritance hierarchy)

* **Input:**
  - `@param Order $order` — Approved fuel order resolved via route model binding from `POST /orders/{order}/invoice`
  - `@param StoreInvoiceRequest $request` — Authenticated HTTP request; carries `auth()->user()` as the invoice issuer

* **Output:**
  - `@return RedirectResponse` — Redirect to `route('invoices.show', $invoice)` with `session('success')` flash on success
  - `@return Invoice` (from `GenerateInvoiceAction::execute`) — Persisted Invoice Eloquent model with calculated amounts
  - `@return View` (from `InvoiceController::show`) — Rendered `invoices/show.blade.php` with full invoice data
  - `@return Response` (from `InvoiceController::download`) — PDF bytes with `Content-Type: application/pdf`;
    disposition `inline` (opens in browser) by default, or `attachment` (force download) when `?download=1`
  - `@return string` (from `GenerateInvoicePdfAction::execute`) — Storage path `invoices/INV-YYYYMM-NNNN.pdf`
  - //@return Boolean true — `orders.status` column in DB changed to `waiting_payment` atomically with invoice creation
  - //@return Boolean true — `invoices.pdf_path` updated after PDF is written to Storage

* **Rules:**
  ```
  // [R1] Status Guard — "Terbitkan Tagihan" button only active when order.status = 'approved'
  // Enforced by: InvoicePolicy::create() → $order->isApproved()
  // UI: @can('create', [Invoice::class, $order]) in orders/show.blade.php

  // [R2] Duplicate Guard — One invoice per order, no re-issuance allowed
  // Enforced by: InvoicePolicy::create() → !$order->hasInvoice()

  // [R3] Role Guard — Only role='admin_penjualan' may issue invoices
  // Enforced by: InvoicePolicy::create() → $user->isAdminPenjualan()

  // [R4] PPN Rate — Fixed at 11% (Pajak Pertambahan Nilai, Indonesia)
  // Enforced by: GenerateInvoiceAction::PPN_RATE = 0.11 (private constant, not configurable via request)

  // [R5] Atomicity — Invoice creation and order status change are one atomic operation
  // Enforced by: DB::transaction() wrapping both writes in GenerateInvoiceAction::execute()

  // [R6] Invoice Number Format — INV/YYYYMM/NNNN (sequential per calendar month)
  // Enforced by: GenerateInvoiceAction::buildInvoiceNumber()

  // [R7] Monetary Precision — All currency amounts stored as DECIMAL(14,2), never FLOAT
  // Enforced by: migration column definitions + Eloquent model casts

  // [R8] Login Rate Limit — Max 5 attempts per minute per email+IP combination
  // Enforced by: RateLimiter::for('login', ...) in AppServiceProvider (route middleware throttle:login)
  //              + LoginRequest::ensureIsNotRateLimited() as a secondary application-layer check

  // [R9] Authentication Guard — All order/invoice routes require authenticated session
  // Enforced by: Route::middleware(['auth']) group in routes/web.php

  // [R10] PDF Isolation — PDF generation runs outside DB transaction
  // Enforced by: GenerateInvoiceAction calls GenerateInvoicePdfAction after DB::transaction() returns
  //              so a PDF failure does not roll back the invoice record

  // [R11] PDF On-Demand Fallback — Seeded or legacy invoices without a stored PDF are generated live
  // Enforced by: InvoiceController::download() checks Storage::exists(); regenerates if missing

  // [R12] PDF Template — Must use table-based HTML with inline CSS only (no Tailwind, no external fonts)
  // Enforced by: resources/views/invoices/pdf.blade.php uses only browser-safe CSS properties
  //              that DomPDF's HTML renderer supports
  ```

* **What changed:**
  ```
  [Prompt 1 — US 2.1 MVC + Diagrams]
  NEW  diagram/er-diagram.puml                          — Full 9-entity ER diagram (PlantUML)
  NEW  diagram/class-diagram.puml                       — Invoice module class diagram (PlantUML)
  NEW  app/Enums/OrderStatus.php                        — 7-state backed enum with label(), badgeColor()
  NEW  app/Enums/InvoiceStatus.php                      — 3-state backed enum with label(), badgeClass()
  NEW  app/Models/Company.php                           — Company model (users/orders relations)
  NEW  app/Models/Order.php                             — isApproved(), hasInvoice(), subtotal(), scopes
  NEW  app/Models/Invoice.php                           — markAsPaid(), activeVirtualAccount(), scopes
  NEW  app/Models/VirtualAccount.php                    — VA model for US 2.2
  NEW  app/Models/PaymentCallback.php                   — Callback model for US 2.3
  MOD  app/Models/User.php                              — role, company_id, role helper methods, relations
  NEW  app/Actions/GenerateInvoiceAction.php            — PPN calc, invoice number gen, DB transaction
  NEW  app/Policies/InvoicePolicy.php                   — viewAny, view, create, download
  NEW  app/Http/Controllers/InvoiceController.php       — index, store, show, download
  NEW  app/Http/Requests/StoreInvoiceRequest.php        — Policy-based authorization
  MOD  app/Providers/AppServiceProvider.php             — Registered InvoicePolicy for Invoice::class
  NEW  database/migrations/2026_04_11_000001_create_companies_table.php
  NEW  database/migrations/2026_04_11_000002_add_role_to_users_table.php
  NEW  database/migrations/2026_04_11_000003_create_orders_table.php
  NEW  database/migrations/2026_04_11_000004_create_invoices_table.php
  NEW  database/migrations/2026_04_11_000005_create_virtual_accounts_table.php
  NEW  database/migrations/2026_04_11_000006_create_payment_callbacks_table.php
  NEW  resources/views/layouts/app.blade.php            — Base layout, nav, flash messages
  NEW  resources/views/invoices/index.blade.php         — Invoice list (Tailwind table)
  NEW  resources/views/invoices/show.blade.php          — Invoice detail, VA info, download button
  MOD  routes/web.php                                   — auth-guarded invoice routes
  NEW  skills/skill.md                                  — Laravel project skill document

  [Prompt 2 — Login Feature + Seeders]
  NEW  app/Http/Controllers/AuthController.php          — showLogin, login, logout
  NEW  app/Http/Controllers/OrderController.php         — index (role-filtered), show
  NEW  app/Http/Requests/LoginRequest.php               — Validation + 5-attempt rate limiting
  NEW  resources/views/auth/login.blade.php             — Login page with demo account panel
  NEW  resources/views/dashboard.blade.php              — Role-aware dashboard with quick links
  NEW  resources/views/orders/index.blade.php           — Order list with status badges
  NEW  resources/views/orders/show.blade.php            — Order detail + Terbitkan Tagihan flow
  MOD  resources/views/layouts/app.blade.php            — Added nav links (Pesanan, Invoice)
  MOD  routes/web.php                                   — Added login/logout/dashboard/order routes
  MOD  database/factories/UserFactory.php               — Added role states (adminPenjualan, etc.)
  NEW  database/factories/CompanyFactory.php            — Company factory
  NEW  database/factories/OrderFactory.php              — Order factory with approved/pending states
  NEW  database/seeders/CompanySeeder.php               — 3 named + 4 random companies
  NEW  database/seeders/UserSeeder.php                  — 7 users: 1 admin_penjualan, 3 manajer, 3 staf
  NEW  database/seeders/OrderSeeder.php                 — 9 orders (approved×3, waiting_payment×2,
                                                           paid×1, pending×1, draft×1, cancelled×1)
                                                           + 3 pre-seeded invoices
  MOD  database/seeders/DatabaseSeeder.php              — Orchestrated: Company→User→Order

  [Prompt 3 — Bug Fixes: undefined authorize, Storage, user()]
  MOD  app/Http/Controllers/Controller.php              — Added AuthorizesRequests trait (Laravel 11
                                                           no longer includes it by default)
  MOD  app/Http/Controllers/InvoiceController.php       — Added Storage + StreamedResponse imports;
                                                           removed \Storage root-namespace prefix
  MOD  app/Http/Controllers/OrderController.php         — Replaced $request->user() with auth()->user();
                                                           removed unused OrderStatus import
  MOD  app/Http/Requests/StoreInvoiceRequest.php        — Fixed can() call: Order::class → Invoice::class

  [Prompt 4 — Bug Fix: MissingRateLimiterException]
  MOD  app/Providers/AppServiceProvider.php             — Added configureRateLimiters() method;
                                                           registered RateLimiter::for('login', ...)
                                                           keyed by email|IP, limit 5/minute;
                                                           imported Limit, Request, RateLimiter facades

  [Prompt 5 — PDF Download Feature (barryvdh/laravel-dompdf)]
  NEW  app/Actions/GenerateInvoicePdfAction.php         — Loads invoices/pdf.blade.php via Pdf::loadView(),
                                                           sets A4 portrait paper, saves output to
                                                           storage/app/private/invoices/{number}.pdf,
                                                           updates invoice.pdf_path column
  MOD  app/Actions/GenerateInvoiceAction.php            — Calls GenerateInvoicePdfAction after DB transaction
                                                           commits (PDF failure does not roll back invoice)
  MOD  app/Http/Controllers/InvoiceController.php       — Rewrote download(): regenerates PDF on-the-fly if
                                                           missing; serves inline (default) or attachment
                                                           (?download=1); returns Illuminate\Http\Response;
                                                           removed StreamedResponse import, added
                                                           GenerateInvoicePdfAction import
  NEW  resources/views/invoices/pdf.blade.php           — A4 standalone invoice PDF template;
                                                           table-based layout + inline CSS only;
                                                           includes: header bar, from/to parties,
                                                           line items table, PPN breakdown, totals,
                                                           payment info box, footer
  MOD  resources/views/invoices/show.blade.php          — Replaced single disabled button with two buttons:
                                                           "Lihat PDF" (inline, target="_blank") and
                                                           "Unduh PDF" (?download=1 attachment)

  [Prompt 6 — Codebase Analysis: Diagram Reconciliation]
  MOD  diagram/er-diagram.puml                          — Rewritten to match only implemented entities:
                                                           REMOVED entities: trucks, drivers, deliveries,
                                                           fuel_receipts (no migrations or models exist)
                                                           REMOVED all dangling FK relationships tied to
                                                           those entities; kept exactly 6 entities that
                                                           have corresponding migrations and models
  MOD  diagram/class-diagram.puml                       — Fully reconciled with actual PHP source code:
                                                           ADDED classes: AuthController, OrderController,
                                                           LoginRequest, AppServiceProvider,
                                                           Controller <<Abstract>>
                                                           ADDED inheritance: Controller <|-- AuthController,
                                                           Controller <|-- OrderController,
                                                           Controller <|-- InvoiceController
                                                           ADDED methods: User::isStafPembeli(),
                                                           User::isPetugasSpbu(),
                                                           Invoice::activeVirtualAccount()
                                                           ADDED dependency: GenerateInvoiceAction ..>
                                                           GenerateInvoicePdfAction : calls after tx
                                                           REMOVED non-existent methods:
                                                           User::fuelReceipts() (no model/relation),
                                                           Invoice::generateInvoiceNumber() (lives in Action),
                                                           Order::delivery() (Delivery model doesn't exist),
                                                           GenerateInvoiceAction::calculateAmounts()
                                                           FIXED InvoiceController::download() return type:
                                                           StreamedResponse → Response; added Request param
                                                           FIXED AuthController method signatures to match
                                                           actual showLogin/login/logout implementations
  ```

* **Commit Message:**
  ```
  feat(us2.1): implement invoice generation, login, and seeders

  - Analyze US 2.1 from requirements PDF and implement full Penerbitan Tagihan Otomatis flow
  - Add GenerateInvoiceAction: PPN 11% calculation, INV/YYYYMM/NNNN numbering, DB transaction
  - Add InvoicePolicy: admin_penjualan role guard, approved-status guard, duplicate guard
  - Add InvoiceController (thin), StoreInvoiceRequest, Order/Invoice/Company models with enums
  - Add login feature: AuthController, LoginRequest (rate-limited), login view with demo hints
  - Add OrderController with role-filtered index and show with Terbitkan Tagihan button logic
  - Add CompanySeeder, UserSeeder, OrderSeeder covering all statuses and actor roles
  - Add PlantUML ER diagram (6 implemented entities) and invoice module class diagram
  - Add skills/skill.md with full Laravel project conventions
  - Fix Laravel 11 compat: AuthorizesRequests trait, Storage facade import, auth()->user()
  - Fix MissingRateLimiterException: register login rate limiter (5/min per email|IP) in AppServiceProvider
  - Add PDF generation: GenerateInvoicePdfAction (DomPDF), A4 invoice PDF template, on-demand fallback
  - Update download(): inline/attachment serving, on-the-fly regeneration for legacy invoices
  - Reconcile ER diagram: remove 4 unimplemented entities (trucks, drivers, deliveries, fuel_receipts)
  - Reconcile class diagram: fix 10+ method/signature discrepancies, add 5 missing classes,
    fix inheritance hierarchy, remove non-existent methods, correct return types
  ```
