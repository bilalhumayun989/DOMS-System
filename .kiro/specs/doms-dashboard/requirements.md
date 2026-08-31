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

---

### Requirement 13: Deliverymen — Create Modal

**User Story:** As an Owner/Admin, I want an "Add Deliveryman" button on the Deliverymen list page, so that I can simulate adding a new driver through the UI without leaving the page.

#### Acceptance Criteria

1. THE Deliverymen_List_Page SHALL display an "Add Deliveryman" button in the page header, styled with a blue primary background (`#3b82f6`) consistent with the existing design system.
2. WHEN a user clicks the "Add Deliveryman" button, THE Deliverymen_List_Page SHALL open a modal overlay containing a form with the following fields: Name (text, required), Employee ID (text, required), Phone (text, required), Vehicle (text, optional), and Join Date (date picker, required).
3. THE Create_Deliveryman_Modal SHALL display a "Save" button and a "Cancel" button; the Save button SHALL use the blue primary style and the Cancel button SHALL use a grey neutral style.
4. WHEN a user clicks "Save" or "Cancel" on THE Create_Deliveryman_Modal, THE Create_Deliveryman_Modal SHALL close without performing any data mutation (dummy action).
5. THE Create_Deliveryman_Modal SHALL open and close with a smooth transition using Alpine.js or vanilla JavaScript.
6. WHEN THE Create_Deliveryman_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.

---

### Requirement 14: Deliverymen — Edit and Delete Actions

**User Story:** As an Owner/Admin, I want Edit and Delete action buttons on each deliveryman row, so that I can simulate updating or removing a driver record from the UI.

#### Acceptance Criteria

1. THE Deliverymen_List_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row, alongside the existing "View →" link.
2. WHEN a user clicks the "Edit" button for a deliveryman row, THE Deliverymen_List_Page SHALL open an Edit modal pre-filled with that deliveryman's existing data in the same fields defined in Requirement 13.2: Name, Employee ID, Phone, Vehicle, and Join Date.
3. THE Edit_Deliveryman_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. WHEN a user clicks the "Delete" button for a deliveryman row, THE Deliverymen_List_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete [Deliveryman Name]?" with a "Confirm" button (red destructive style, `#ef4444`) and a "Cancel" button (grey style).
5. WHEN a user clicks "Confirm" or "Cancel" on THE Delete_Deliveryman_Modal, THE Delete_Deliveryman_Modal SHALL close without performing any data mutation.
6. THE "Delete" action button in each row SHALL use a red destructive style consistent with `#ef4444`.

---

### Requirement 15: Trips — Create Modal

**User Story:** As an Owner/Admin, I want a "New Trip" button on the Trips list page, so that I can simulate creating a new trip dispatch record through the UI.

#### Acceptance Criteria

1. THE Trips_List_Page SHALL display a "New Trip" button in the page header, styled with a blue primary background consistent with the existing design system.
2. WHEN a user clicks the "New Trip" button, THE Trips_List_Page SHALL open a modal containing a form with the following fields: Trip ID (text, read-only, auto-generated in the format TR-YYYY-MM-DD-NNN), Date (date picker, defaulting to today's date), Deliveryman (dropdown populated with the names of existing deliverymen in the fixture data), Vehicle (text, optional), Market/Area (text, required), and Source DLF (text, optional).
3. THE Create_Trip_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. WHEN THE Create_Trip_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.
5. THE Create_Trip_Modal SHALL open and close with a smooth transition using Alpine.js or vanilla JavaScript.

---

### Requirement 16: Trips — Edit and Delete Actions

**User Story:** As an Owner/Admin, I want Edit and Delete action buttons on each trip row, so that I can simulate modifying or removing a trip record from the UI.

#### Acceptance Criteria

1. THE Trips_List_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row, alongside the existing "View →" link.
2. WHEN a user clicks the "Edit" button for a trip row, THE Trips_List_Page SHALL open an Edit modal pre-filled with that trip's existing data in the same fields defined in Requirement 15.2: Trip ID (read-only), Date, Deliveryman (dropdown), Vehicle, Market/Area, and Source DLF.
3. THE Edit_Trip_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. WHEN a user clicks the "Delete" button for a trip row, THE Trips_List_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete Trip [Trip ID]?" with a "Confirm" button (red destructive style) and a "Cancel" button (grey style).
5. WHEN a user clicks "Confirm" or "Cancel" on THE Delete_Trip_Modal, THE Delete_Trip_Modal SHALL close without performing any data mutation.
6. THE "Delete" action button in each row SHALL use a red destructive style consistent with `#ef4444`.

---

### Requirement 17: Markets — Create, Edit, and Delete Actions

**User Story:** As an Owner/Admin, I want an "Add Market" button on the Markets list page and Edit/Delete actions per row, so that I can simulate managing market records through the UI.

#### Acceptance Criteria

1. THE Markets_List_Page SHALL display an "Add Market" button in the page header, styled with a blue primary background consistent with the existing design system.
2. WHEN a user clicks the "Add Market" button, THE Markets_List_Page SHALL open a modal containing a form with the following fields: Market Name (text, required), Area/Region (text, required), Contact Person (text, optional), Contact Phone (text, optional), and Outstanding Balance (number input, defaulting to 0).
3. THE Create_Market_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. THE Markets_List_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row, alongside the existing "View →" link.
5. WHEN a user clicks the "Edit" button for a market row, THE Markets_List_Page SHALL open an Edit modal pre-filled with that market's existing data in the same fields defined in Requirement 17.2.
6. THE Edit_Market_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
7. WHEN a user clicks the "Delete" button for a market row, THE Markets_List_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete [Market Name]?" with a "Confirm" button (red destructive style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
8. WHEN THE Create_Market_Modal or Edit_Market_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.

---

### Requirement 18: Invoices — Create, Edit, and Delete Actions

**User Story:** As an Owner/Admin, I want an "Add Invoice" button on the Invoices list page and Edit/Delete actions per row, so that I can simulate managing invoice records through the UI.

#### Acceptance Criteria

1. THE Invoices_List_Page SHALL display an "Add Invoice" button in the page header, styled with a blue primary background consistent with the existing design system.
2. WHEN a user clicks the "Add Invoice" button, THE Invoices_List_Page SHALL open a modal containing a form with the following fields: Invoice Number (text, required), Customer/Market (text, required), Trip ID (text, required), Date (date picker, required), Total Value (number input, required), and Status (dropdown with options: DELIVERED, PARTIAL, NOT DELIVERED, RESERVICE).
3. THE Create_Invoice_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. THE Invoices_List_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row, alongside the existing "View →" link.
5. WHEN a user clicks the "Edit" button for an invoice row, THE Invoices_List_Page SHALL open an Edit modal pre-filled with that invoice's existing data in the same fields defined in Requirement 18.2.
6. THE Edit_Invoice_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
7. WHEN a user clicks the "Delete" button for an invoice row, THE Invoices_List_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete Invoice [Invoice Number]?" with a "Confirm" button (red destructive style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
8. WHEN THE Create_Invoice_Modal or Edit_Invoice_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.

---

### Requirement 19: Stock — Create, Edit, and Delete Actions

**User Story:** As an Owner/Admin, I want an "Add SKU" button on the Stock list page and Edit/Delete actions per row, so that I can simulate managing inventory records through the UI.

#### Acceptance Criteria

1. THE Stock_Overview_Page SHALL display an "Add SKU" button in the page header, styled with a blue primary background consistent with the existing design system.
2. WHEN a user clicks the "Add SKU" button, THE Stock_Overview_Page SHALL open a modal containing a form with the following fields: SKU Code (text, required), Product Name (text, required), Category (text, required), Current Stock (number input, required, minimum 0), and Reorder Point (number input, required, minimum 0).
3. THE Create_SKU_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. THE Stock_Overview_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row, alongside the existing "View →" link.
5. WHEN a user clicks the "Edit" button for a SKU row, THE Stock_Overview_Page SHALL open an Edit modal pre-filled with that SKU's existing data in the same fields defined in Requirement 19.2.
6. THE Edit_SKU_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
7. WHEN a user clicks the "Delete" button for a SKU row, THE Stock_Overview_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete SKU [SKU Code]?" with a "Confirm" button (red destructive style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
8. WHEN THE Create_SKU_Modal or Edit_SKU_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.

---

### Requirement 20: Returns — Edit and Delete Actions

**User Story:** As a Warehouse Supervisor, I want Edit and Delete action buttons on each return row, so that I can simulate correcting or removing a return record through the UI.

#### Acceptance Criteria

1. THE Returns_List_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row.
2. WHEN a user clicks the "Edit" button for a return row, THE Returns_List_Page SHALL open an Edit modal pre-filled with that return's existing data containing the following fields: Return ID (text, read-only), Trip ID (text), Deliveryman (text), SKU (text), Product Name (text), Qty Returned (number input), Reason Code (dropdown with options: REFUSED, DAMAGED, EXPIRED, EXCESS), and Status (dropdown with options: Pending, Restocked).
3. THE Edit_Return_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. WHEN a user clicks the "Delete" button for a return row, THE Returns_List_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete Return [Return ID]?" with a "Confirm" button (red destructive style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
5. THE "Delete" action button in each row SHALL use a red destructive style consistent with `#ef4444`.
6. WHEN THE Edit_Return_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.

---

### Requirement 21: Collections — Create, Edit, and Delete Actions

**User Story:** As an Owner/Admin, I want an "Add Collection" button on the Collections list page and Edit/Delete actions per row, so that I can simulate managing collection records through the UI.

#### Acceptance Criteria

1. THE Collections_List_Page SHALL display an "Add Collection" button in the page header, styled with a blue primary background consistent with the existing design system.
2. WHEN a user clicks the "Add Collection" button, THE Collections_List_Page SHALL open a modal containing a form with the following fields: Collection ID (text, read-only, auto-generated), Date (date picker, defaulting to today), Customer/Market (text, required), Invoice Number (text, required), Trip ID (text, required), Amount (number input, required), Method (dropdown with options: Cash, Cheque, Transfer), and Deliveryman (text, required).
3. THE Create_Collection_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. THE Collections_List_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row.
5. WHEN a user clicks the "Edit" button for a collection row, THE Collections_List_Page SHALL open an Edit modal pre-filled with that collection's existing data in the same fields defined in Requirement 21.2.
6. THE Edit_Collection_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
7. WHEN a user clicks the "Delete" button for a collection row, THE Collections_List_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete Collection [Collection ID]?" with a "Confirm" button (red destructive style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
8. WHEN THE Create_Collection_Modal or Edit_Collection_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.

---

### Requirement 22: Settlements — Edit and Delete Actions

**User Story:** As an Owner/Admin, I want Edit and Delete action buttons on each settlement row, so that I can simulate correcting a settlement record or marking one for removal through the UI.

#### Acceptance Criteria

1. THE Settlements_List_Page SHALL display an "Edit" button and a "Delete" button in the actions column of each row.
2. WHEN a user clicks the "Edit" button for a settlement row, THE Settlements_List_Page SHALL open an Edit modal pre-filled with that settlement's existing data containing the following fields: Settlement ID (text, read-only), Trip ID (text, read-only), Deliveryman (text), Date (date picker), Expected Cash (number input), Collected Amount (number input), Shortage Amount (number input), Shortage Classification (dropdown with options: Market Short, Deliveryman Short, Approved Write-Off, Pending Investigation), and Settlement Status (dropdown with options: Pending, Settled, Closed).
3. THE Edit_Settlement_Modal SHALL display a "Save" button (blue primary style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
4. WHEN a user clicks the "Delete" button for a settlement row, THE Settlements_List_Page SHALL open a confirmation modal displaying the message: "Are you sure you want to delete Settlement [Settlement ID]?" with a "Confirm" button (red destructive style) and a "Cancel" button (grey style); clicking either SHALL close the modal without data mutation.
5. THE "Delete" action button in each row SHALL use a red destructive style consistent with `#ef4444`.
6. WHEN THE Edit_Settlement_Modal is open, clicking the backdrop overlay outside the modal SHALL close the modal.

---

### Requirement 23: Modal Interaction — Shared Behaviour

**User Story:** As an Owner/Admin, I want all modals across the dashboard to behave consistently, so that the UI feels predictable and professional throughout.

#### Acceptance Criteria

1. THE Dashboard_Layout SHALL ensure that WHEN any modal is open, the page body scroll SHALL be locked to prevent background scrolling.
2. WHEN any modal is open, THE Dashboard_Layout SHALL render a semi-transparent dark backdrop behind the modal panel.
3. ALL modal panels SHALL be centred both horizontally and vertically on the viewport, with a white background, rounded corners, and a drop shadow consistent with the existing card design system.
4. ALL form fields inside modals SHALL use consistent styling: white background, `#e2e8f0` border, `0.375rem` border-radius, and `0.75rem` padding, matching the existing input patterns in the application.
5. ALL "Save" buttons in create and edit modals SHALL use blue primary style (`background: #3b82f6`, white text). ALL "Cancel" buttons SHALL use a grey neutral style (`background: #f1f5f9`, `color: #64748b`). ALL "Confirm Delete" buttons SHALL use red destructive style (`background: #ef4444`, white text).
6. WHEN a user presses the Escape key while any modal is open, THE modal SHALL close without performing any data mutation.
7. THE Dashboard_Layout SHALL implement all modal open/close behaviour using Alpine.js `x-data`, `x-show`, and `x-on` directives, or equivalent vanilla JavaScript, without requiring a full page reload.
