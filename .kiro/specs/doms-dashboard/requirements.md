# Requirements Document

## Introduction

The DOMS (Delivery Order Management System) Interactive Dashboard is a client-presentable, professional web interface built in Laravel with Blade views. It demonstrates the complete DOMS business workflow — from data entry through trip dispatch, delivery results, returns, collections, settlement, and ledger management — using realistic seed/dummy data. Every major entity (trips, deliverymen, invoices, markets, stock) is navigable from the main dashboard through drill-down pages, connected by a single Trip ID that links all operations. The dashboard is for the Owner/Admin role and provides a live overview of today's operations plus full access to all sub-sections via a persistent sidebar.

## Glossary

- **Dashboard**: The main landing page showing summary KPI cards for today's operational snapshot.
- **Trip**: A dispatched delivery run identified by a unique Trip ID (format: TR-YYYY-MM-DD-NNN), carrying one or more invoices to a market area.
- **Trip_ID**: Unique identifier linking every order, load, delivery, return, collection, shortage, and ledger entry.
- **DLF**: Delivery Load Form — the source document listing customers, invoices, products, quantities, and prices.
- **Deliveryman**: A person assigned to execute a trip and deliver goods to customers.
- **Invoice**: A single customer order within a trip, with line items, quantities, and a monetary value.
- **Market**: A geographic customer area served by one or more trips.
- **SKU**: Stock Keeping Unit — a single distinct product variant tracked in the warehouse.
- **Shortage**: The difference between expected cash/stock and what was actually collected/returned, classified as Market Short, Deliveryman Short, Approved Write-Off, or Pending Investigation.
- **Settlement**: The reconciliation of a completed trip's expected vs. received values.
- **Ledger**: An append-only transaction log per customer (Market Ledger) or per deliveryman (Deliveryman Ledger).
- **Return**: Unsold, refused, or damaged goods brought back to the warehouse after a trip.
- **Collection**: Cash, cheque, or bank transfer received from a customer against an invoice.
- **Reconciler**: A staff role that reviews stock, cash, and ledger data and reports findings for management approval.
- **Sidebar**: The persistent left-hand navigation panel visible on all pages.
- **Seed_Data**: Pre-populated dummy records used to demonstrate system functionality.
- **KPI_Card**: A clickable summary card on the Dashboard showing a metric and linking to its detail list page.

---

## Requirements

### Requirement 1: Application Layout and Navigation

**User Story:** As an Owner/Admin, I want a consistent application shell with sidebar navigation, so that I can move between all major sections of DOMS without losing context.

#### Acceptance Criteria

1. THE Dashboard_Layout SHALL render a persistent left sidebar containing navigation links to: Dashboard, Trips, Deliverymen, Markets, Invoices, Stock, Returns, Collections, Settlements, Ledgers, and Reports.
2. THE Dashboard_Layout SHALL render a top header bar displaying the application name "DOMS", the current date, and the active page title.
3. THE Dashboard_Layout SHALL highlight the currently active sidebar link to indicate the user's location.
4. WHEN a user clicks a sidebar navigation link, THE Dashboard_Layout SHALL navigate to the corresponding section page without a full page reload where possible, or via standard Laravel route navigation.
5. THE Dashboard_Layout SHALL be responsive and render correctly on desktop viewport widths of 1280px and above.
6. THE Dashboard_Layout SHALL use a professional dark sidebar with light content area color scheme suitable for client presentation.

---

### Requirement 2: Main Dashboard — KPI Summary Cards

**User Story:** As an Owner, I want a main dashboard page showing today's key operational metrics in clickable summary cards, so that I can get a live snapshot of the business and drill into any area.

#### Acceptance Criteria

1. THE Dashboard_Page SHALL display the following KPI_Cards: Today's Active Trips, Total Sales Value (today), Total Collections (today), Outstanding Shortages, Pending Returns, Active Deliverymen, Stock Alerts, and Pending Settlements.
2. EACH KPI_Card SHALL display a metric label, a prominent numeric or currency value, a relevant icon, and a color-coded status indicator (e.g., green for healthy, amber for attention needed, red for critical).
3. WHEN a user clicks a KPI_Card, THE Dashboard_Page SHALL navigate to the corresponding list page for that metric.
4. THE Dashboard_Page SHALL display a "Today's Trips" summary table listing active trips with columns: Trip ID, Deliveryman, Market/Area, Status, Load Value, and a View link.
5. THE Dashboard_Page SHALL display a "Recent Collections" panel showing the last 5 collections with: Customer, Amount, Method, and Trip ID.
6. THE Dashboard_Page SHALL display a "Top Shortages" panel showing the top 5 open shortage items with: Deliveryman, Trip ID, Amount, and Classification.
7. THE Dashboard_Page SHALL load Seed_Data so that all cards and panels display realistic non-zero values on first access.

---

### Requirement 3: Trips Section — List and Detail Pages

**User Story:** As an Owner, I want to browse all trips and inspect the full details of any trip, so that I can track dispatch, delivery, collections, and settlement for each run.

#### Acceptance Criteria

1. THE Trips_List_Page SHALL display all trips in a paginated table with columns: Trip ID, Date, Deliveryman, Vehicle, Market/Area, Status badge, Load Value, Expected Cash, and an Actions column with a View link.
2. THE Trips_List_Page SHALL display status badges using distinct colors for each trip lifecycle state: DRAFT (grey), READY (blue), DISPATCHED (orange), COMPLETED (teal), SETTLEMENT PENDING (amber), SETTLED (green), CLOSED (dark grey).
3. WHEN a user clicks the View link for a trip, THE Trips_Detail_Page SHALL display the full trip record including: Trip ID, Date, Deliveryman name (linked to their detail page), Vehicle, Market/Area, Source DLF, all associated Invoices, Load Value, Expected Cash, and current Status.
4. THE Trips_Detail_Page SHALL display a delivery results panel listing each invoice with its delivery outcome: DELIVERED, PARTIAL, NOT DELIVERED, or RESERVICE.
5. THE Trips_Detail_Page SHALL display a collections panel listing all collections received for the trip, showing: Customer, Amount, Method (Cash/Cheque/Transfer), and date/time.
6. THE Trips_Detail_Page SHALL display a returns panel listing all returned items for the trip, showing: SKU, Product Name, Quantity Returned, Reason Code, and date.
7. THE Trips_Detail_Page SHALL display a settlement summary panel showing: Expected Cash, Collected Amount, Shortage Amount, and Shortage Classification.
8. EACH invoice listed on THE Trips_Detail_Page SHALL be a clickable link navigating to the Invoice_Detail_Page for that invoice.
9. THE Trips_Detail_Page SHALL display a breadcrumb navigation trail: Dashboard → Trips → Trip ID.

---

### Requirement 4: Deliverymen Section — List and Detail Pages

**User Story:** As an Owner, I want to see all deliverymen and drill into each person's trip history and performance, so that I can monitor reliability and outstanding shortages.

#### Acceptance Criteria

1. THE Deliverymen_List_Page SHALL display all deliverymen in a table with columns: Name, Employee ID, Phone, Total Trips (all time), Active Trips (today), Total Collected (today), Outstanding Shortages, and a View link.
2. WHEN a user clicks the View link for a deliveryman, THE Deliveryman_Detail_Page SHALL display the person's profile: Name, Employee ID, Phone, and join date.
3. THE Deliveryman_Detail_Page SHALL display a paginated trip history table with columns: Trip ID (linked to Trips_Detail_Page), Date, Market/Area, Status badge, Load Value, Collected, and Shortage.
4. THE Deliveryman_Detail_Page SHALL display a summary panel showing: Total Trips (all time), Total Value Delivered, Total Collected, Total Shortages, and a running ledger balance.
5. THE Deliveryman_Detail_Page SHALL display the deliveryman's ledger entries in a chronological table with columns: Date, Trip ID, Transaction Type, Debit, Credit, and Running Balance.
6. THE Deliveryman_Detail_Page SHALL display a breadcrumb navigation trail: Dashboard → Deliverymen → Deliveryman Name.

---

### Requirement 5: Markets Section — List and Detail Pages

**User Story:** As an Owner, I want to see all customer markets and inspect each market's invoice history and outstanding balance, so that I can manage customer debt.

#### Acceptance Criteria

1. THE Markets_List_Page SHALL display all markets in a table with columns: Market Name, Area/Region, Total Invoices, Total Value, Total Collected, Outstanding Balance, and a View link.
2. WHEN a user clicks the View link for a market, THE Market_Detail_Page SHALL display the market's profile: Name, area, contact, and outstanding balance.
3. THE Market_Detail_Page SHALL display a paginated invoice history table with columns: Invoice Number (linked to Invoice_Detail_Page), Date, Trip ID (linked to Trips_Detail_Page), Value, Collected, and Status.
4. THE Market_Detail_Page SHALL display the market ledger entries in a chronological table with columns: Date, Invoice/Trip reference, Transaction Type, Debit, Credit, and Running Balance.
5. WHEN the Market_Detail_Page is visible, THE Market_Detail_Page SHALL display a breadcrumb navigation trail: Dashboard → Markets → Market Name.

---

### Requirement 6: Invoices Section — List and Detail Pages

**User Story:** As an Owner, I want to browse all invoices and see the full detail of any invoice including its line items, so that I can verify order contents and delivery status.

#### Acceptance Criteria

1. THE Invoices_List_Page SHALL display all invoices in a paginated table with columns: Invoice Number, Customer/Market, Trip ID (linked), Date, Total Value, Status (DELIVERED / PARTIAL / NOT DELIVERED / RESERVICE), and a View link.
2. WHEN a user clicks the View link for an invoice, THE Invoice_Detail_Page SHALL display: Invoice Number, Customer name, Trip ID (linked to Trips_Detail_Page), Date, and overall delivery status.
3. THE Invoice_Detail_Page SHALL display a line items table with columns: SKU, Product Name, Ordered Quantity, Delivered Quantity, Unit Price, and Line Total.
4. THE Invoice_Detail_Page SHALL display a collections panel showing all payments received against this invoice: Date, Amount, Method, and Receipt reference.
5. THE Invoice_Detail_Page SHALL display a breadcrumb navigation trail: Dashboard → Invoices → Invoice Number.

---

### Requirement 7: Stock Section — Overview and SKU Detail Pages

**User Story:** As an Owner, I want to see current stock levels and review the movement history of any SKU, so that I can detect shortages and plan restocking.

#### Acceptance Criteria

1. THE Stock_Overview_Page SHALL display all SKUs in a paginated table with columns: SKU Code, Product Name, Category, Current Stock Level, Reorder Point, Status badge (In Stock / Low Stock / Out of Stock), and a View link.
2. THE Stock_Overview_Page SHALL display SKUs with stock below the Reorder Point with a "Low Stock" badge, SKUs with stock at zero (and Reorder Point above zero) with an "Out of Stock" badge, and SKUs where both stock level and Reorder Point are zero with an "In Stock" badge.
3. WHEN a user clicks the View link for a SKU, THE SKU_Detail_Page SHALL display: SKU Code, Product Name, Category, Current Stock Level, and Reorder Point.
4. THE SKU_Detail_Page SHALL display a stock movement history table with columns: Date, Movement Type (Dispatch / Return / Adjustment), Trip ID (linked where applicable), Quantity Change, and Balance After.
5. THE SKU_Detail_Page SHALL display a breadcrumb navigation trail: Dashboard → Stock → SKU Code.

---

### Requirement 8: Returns Section — List Page

**User Story:** As a Warehouse Supervisor, I want to see all returned items in one place, so that I can verify stock has been restocked and reason codes are recorded.

#### Acceptance Criteria

1. THE Returns_List_Page SHALL display all return records in a paginated table with columns: Return ID, Date, Trip ID (linked to Trips_Detail_Page), Deliveryman, SKU, Product Name, Quantity Returned, Reason Code, and Status (Pending / Restocked).
2. THE Returns_List_Page SHALL display returns with "Pending" status with a visually distinct amber badge; IF the amber badge cannot be rendered, THEN THE Returns_List_Page SHALL halt page rendering and display an error rather than showing pending returns without a badge.
3. THE Returns_List_Page SHALL support filtering by status (All / Pending / Restocked) using tab or dropdown controls rendered in the page.

---

### Requirement 9: Collections Section — List Page

**User Story:** As an Owner, I want to see all collections (cash, cheque, transfer) in one place with their audit trail, so that I can verify every payment is linked to a customer, invoice, and trip.

#### Acceptance Criteria

1. THE Collections_List_Page SHALL display all collection records in a paginated table with columns: Collection ID, Date, Customer/Market (linked to Market_Detail_Page), Invoice Number (linked to Invoice_Detail_Page), Trip ID (linked to Trips_Detail_Page), Amount, Method (Cash / Cheque / Transfer), and Deliveryman.
2. THE Collections_List_Page SHALL display the daily total collected amount as a summary figure above the table.
3. THE Collections_List_Page SHALL support filtering by payment method (All / Cash / Cheque / Transfer) using tab or dropdown controls rendered in the page; WHERE a show_all_methods flag is enabled, THE Collections_List_Page SHALL display all payment methods regardless of the selected filter.

---

### Requirement 10: Settlements Section — List Page

**User Story:** As an Owner, I want to see all trip settlements and their shortage classifications, so that I can monitor financial reconciliation.

#### Acceptance Criteria

1. THE Settlements_List_Page SHALL display all settlement records in a paginated table with columns: Settlement ID, Trip ID (linked to Trips_Detail_Page), Deliveryman, Date, Expected Cash, Collected Amount, Shortage Amount, Shortage Classification badge, and Settlement Status.
2. THE Settlements_List_Page SHALL display shortage classification using color-coded badges: Market Short (blue), Deliveryman Short (red), Approved Write-Off (grey), Pending Investigation (amber).
3. THE Settlements_List_Page SHALL display a summary row showing total Expected, total Collected, and total Shortage across all shown records.

---

### Requirement 11: Seed Data Population

**User Story:** As a developer demonstrating DOMS, I want realistic seed data pre-loaded into the system, so that every page shows meaningful values without requiring manual data entry.

#### Acceptance Criteria

1. THE Seeder SHALL create at least 5 Deliverymen records with distinct names, employee IDs, and contact details.
2. THE Seeder SHALL create at least 8 Markets with distinct names and geographic areas.
3. THE Seeder SHALL create at least 20 SKUs across at least 3 product categories with realistic names and stock levels.
4. THE Seeder SHALL create at least 15 Trips spanning the last 30 days with varied statuses covering all Trip lifecycle states.
5. THE Seeder SHALL create at least 50 Invoices distributed across trips and markets, each with 2–5 line items.
6. THE Seeder SHALL create Collections records corresponding to delivered and partially-delivered invoices.
7. THE Seeder SHALL create Returns records for at least 10 invoices with varied reason codes.
8. THE Seeder SHALL create Settlement records for all SETTLED and CLOSED trips with classified shortages.
9. THE Seeder SHALL create Ledger entries consistent with the collection and shortage data for both Market Ledgers and Deliveryman Ledgers.
10. WHEN a developer runs `php artisan db:seed`, THE Seeder SHALL populate all tables with the above data based on matching expected data counts to confirm idempotency; IF seeding fails at any point, THE Seeder SHALL leave any partially populated data in place without rolling back.

---

### Requirement 12: Reports Section — Summary Page

**User Story:** As an Owner, I want a reports overview page listing available report types, so that I know what analytical views are available even if they are not yet fully implemented.

#### Acceptance Criteria

1. THE Reports_Page SHALL display a grid of report cards including: Trip Report, Deliveryman Report, Market/Customer Report, Stock Report, SKU Movement Report, Financial Summary Report, and Audit Trail Report.
2. EACH report card SHALL display a title, a brief description of what the report contains, and a "View Report" button.
3. THE Trip_Report_View SHALL display a filterable table of all trips with totals for Load Value, Collected, and Shortage, grouped by date range (defaulting to the current month).
4. THE Deliveryman_Report_View SHALL display a per-deliveryman summary table showing: Total Trips, Total Value, Total Collected, Total Shortages, and Shortage Rate percentage.
5. THE Financial_Summary_Report_View SHALL display daily totals for Sales, Collections, Shortages, and Returns for the current month in a summary table.
