# Implementation Plan: DOMS CRUD Modals — Requirements 13–23

## Overview

Add Alpine.js-powered CRUD action buttons and inline modals to all 8 existing list pages. No routes, controllers, or migrations are needed — all interactions are client-side only. The Trips page is implemented first to establish the shared modal pattern; the remaining 7 pages follow the identical structure.

The implementation language is **PHP / Blade** (Laravel). All modal state is managed by Alpine.js `x-data`.

---

## Tasks

- [ ] 1. Implement CRUD modal pattern on Trips list page (`trips/index.blade.php`)
  - [ ] 1.1 Wrap the entire `@section('content')` block in a single `x-data` div with `open`, `mode`, `selected` state and `openCreate()`, `openEdit(record)`, `openDelete(record)`, `close()` methods; add `x-effect` body-scroll lock and `@keydown.escape.window="close()"` on the wrapper
    - File: `resources/views/trips/index.blade.php`
    - _Requirements: 15.5, 16.5, 23.1, 23.6, 23.7_
  - [ ] 1.2 Add "New Trip" button to the page header that calls `openCreate()`; replace the lone "View →" link in each row's actions column with a three-button group: "View →", "Edit" (calls `openEdit({{ json_encode($trip) }})`), "Delete" (calls `openDelete({{ json_encode($trip) }})`)
    - Style the Edit button with `background:#f0fdf4;color:#16a34a;` and Delete button with `background:#fff1f2;color:#ef4444;`
    - _Requirements: 15.1, 16.1, 16.6_
  - [ ] 1.3 Add the semi-transparent backdrop div (z-index 50, `@click="close()"`, `x-show="open"`, 200 ms enter/leave transitions) and the centred modal panel div (z-index 51, `@click.stop`, same transitions)
    - _Requirements: 15.4, 16.4, 23.2, 23.3_
  - [ ] 1.4 Inside the modal panel, add `<template x-if="mode === 'create' || mode === 'edit'">` containing the Create/Edit form: Trip ID (`text`, `readonly`), Date (`date`, `required`), Deliveryman (`select`, `required`, options from `$trips` fixture), Vehicle (`text`), Market/Area (`text`, `required`), Source DLF (`text`); include Save + Cancel buttons
    - All required fields must carry the HTML `required` attribute; form fields use the shared input style (`border:1px solid #e2e8f0; border-radius:0.375rem; padding:0.75rem`)
    - _Requirements: 15.2, 16.2, 16.3, 23.4, 23.5_
  - [ ] 1.5 Inside the modal panel, add `<template x-if="mode === 'delete'">` containing the Delete confirmation: "Are you sure you want to delete Trip <span x-text="selected?.trip_id"></span>?", Confirm Delete button (red), Cancel button (grey); both call `close()`
    - _Requirements: 16.4, 16.5, 23.5_

- [ ] 2. Implement CRUD modal pattern on Deliverymen list page (`deliverymen/index.blade.php`)
  - [ ] 2.1 Wrap `@section('content')` in `x-data` wrapper with the same state/method pattern as task 1.1; add "Add Deliveryman" button to page header; add Edit + Delete buttons to each row alongside the existing "View →" link
    - _Requirements: 13.1, 13.5, 13.6, 14.1, 14.6, 23.1, 23.6, 23.7_
  - [ ] 2.2 Add backdrop + modal panel (identical structure to task 1.3)
    - _Requirements: 13.6, 23.2, 23.3_
  - [ ] 2.3 Add Create/Edit form template with fields: Name (`text`, `required`), Employee ID (`text`, `required`), Phone (`text`, `required`), Vehicle (`text`), Join Date (`date`, `required`); Save + Cancel buttons
    - Pre-fill edit fields via `:value="selected?.name"` etc. using Alpine bindings
    - _Requirements: 13.2, 13.3, 13.4, 14.2, 14.3, 23.4, 23.5_
  - [ ] 2.4 Add Delete confirmation template: "Are you sure you want to delete <span x-text="selected?.name"></span>?"; Confirm Delete (red) + Cancel (grey) buttons
    - _Requirements: 14.4, 14.5, 23.5_

- [ ] 3. Implement CRUD modal pattern on Markets list page (`markets/index.blade.php`)
  - [ ] 3.1 Wrap `@section('content')` in `x-data` wrapper; add "Add Market" button to page header; add Edit + Delete buttons to each row alongside existing "View →" link
    - _Requirements: 17.1, 17.4, 23.1, 23.7_
  - [ ] 3.2 Add backdrop + modal panel
    - _Requirements: 17.8, 23.2, 23.3_
  - [ ] 3.3 Add Create/Edit form template with fields: Market Name (`text`, `required`), Area/Region (`text`, `required`), Contact Person (`text`), Contact Phone (`text`), Outstanding Balance (`number`, default `0`); Save + Cancel buttons
    - _Requirements: 17.2, 17.3, 17.5, 17.6, 23.4, 23.5_
  - [ ] 3.4 Add Delete confirmation template: "Are you sure you want to delete <span x-text="selected?.name"></span>?"
    - _Requirements: 17.7, 23.5_

- [ ] 4. Implement CRUD modal pattern on Invoices list page (`invoices/index.blade.php`)
  - [ ] 4.1 Wrap `@section('content')` in `x-data` wrapper; add "Add Invoice" button to page header; add Edit + Delete buttons to each row alongside existing "View →" link
    - _Requirements: 18.1, 18.4, 23.1, 23.7_
  - [ ] 4.2 Add backdrop + modal panel
    - _Requirements: 18.8, 23.2, 23.3_
  - [ ] 4.3 Add Create/Edit form template with fields: Invoice Number (`text`, `required`), Customer/Market (`text`, `required`), Trip ID (`text`, `required`), Date (`date`, `required`), Total Value (`number`, `required`, `min="0"`), Status (`select`, options: DELIVERED / PARTIAL / NOT DELIVERED / RESERVICE); Save + Cancel buttons
    - _Requirements: 18.2, 18.3, 18.5, 18.6, 23.4, 23.5_
  - [ ] 4.4 Add Delete confirmation template: "Are you sure you want to delete Invoice <span x-text="selected?.invoice_number"></span>?"
    - _Requirements: 18.7, 23.5_

- [ ] 5. Implement CRUD modal pattern on Stock list page (`stock/index.blade.php`)
  - [ ] 5.1 Wrap `@section('content')` in `x-data` wrapper; add "Add SKU" button to page header (alongside the existing stock-alert badges); add Edit + Delete buttons to each row alongside existing "View →" link
    - _Requirements: 19.1, 19.4, 23.1, 23.7_
  - [ ] 5.2 Add backdrop + modal panel
    - _Requirements: 19.8, 23.2, 23.3_
  - [ ] 5.3 Add Create/Edit form template with fields: SKU Code (`text`, `required`), Product Name (`text`, `required`), Category (`text`, `required`), Current Stock (`number`, `required`, `min="0"`), Reorder Point (`number`, `required`, `min="0"`); Save + Cancel buttons
    - _Requirements: 19.2, 19.3, 19.5, 19.6, 23.4, 23.5_
  - [ ] 5.4 Add Delete confirmation template: "Are you sure you want to delete SKU <span x-text="selected?.sku_code"></span>?"
    - _Requirements: 19.7, 23.5_

- [ ] 6. Implement Edit + Delete modals on Returns list page (`returns/index.blade.php`)
  - [ ] 6.1 Wrap `@section('content')` in `x-data` wrapper (no create button for Returns); add Edit + Delete buttons to each row (Returns has no "View →" link to preserve)
    - _Requirements: 20.1, 23.1, 23.7_
  - [ ] 6.2 Add backdrop + modal panel
    - _Requirements: 20.6, 23.2, 23.3_
  - [ ] 6.3 Add Edit form template with fields: Return ID (`text`, `readonly`), Trip ID (`text`), Deliveryman (`text`), SKU (`text`), Product Name (`text`), Qty Returned (`number`, `min="1"`), Reason Code (`select`, options: REFUSED / DAMAGED / EXPIRED / EXCESS), Status (`select`, options: Pending / Restocked); Save + Cancel buttons
    - _Requirements: 20.2, 20.3, 23.4, 23.5_
  - [ ] 6.4 Add Delete confirmation template: "Are you sure you want to delete Return <span x-text="selected?.return_ref"></span>?"
    - _Requirements: 20.4, 20.5, 23.5_

- [ ] 7. Implement CRUD modal pattern on Collections list page (`collections/index.blade.php`)
  - [ ] 7.1 Wrap the entire `@section('content')` (including the daily-total banner and table) in a single `x-data` wrapper; add "Add Collection" button to the table header; add Edit + Delete buttons to each row
    - _Requirements: 21.1, 21.4, 23.1, 23.7_
  - [ ] 7.2 Add backdrop + modal panel
    - _Requirements: 21.8, 23.2, 23.3_
  - [ ] 7.3 Add Create/Edit form template with fields: Collection ID (`text`, `readonly`), Date (`date`, `required`, default today), Customer/Market (`text`, `required`), Invoice Number (`text`, `required`), Trip ID (`text`, `required`), Amount (`number`, `required`, `min="0"`), Method (`select`, `required`, options: Cash / Cheque / Transfer), Deliveryman (`text`, `required`); Save + Cancel buttons
    - _Requirements: 21.2, 21.3, 21.5, 21.6, 23.4, 23.5_
  - [ ] 7.4 Add Delete confirmation template: "Are you sure you want to delete Collection <span x-text="selected?.collection_ref"></span>?"
    - _Requirements: 21.7, 23.5_

- [ ] 8. Implement Edit + Delete modals on Settlements list page (`settlements/index.blade.php`)
  - [ ] 8.1 Wrap `@section('content')` in `x-data` wrapper (no create button for Settlements); add Edit + Delete buttons to each row (new actions column after the existing Status column)
    - _Requirements: 22.1, 23.1, 23.7_
  - [ ] 8.2 Add backdrop + modal panel
    - _Requirements: 22.6, 23.2, 23.3_
  - [ ] 8.3 Add Edit form template with fields: Settlement ID (`text`, `readonly`), Trip ID (`text`, `readonly`), Deliveryman (`text`), Date (`date`), Expected Cash (`number`, `min="0"`), Collected Amount (`number`, `min="0"`), Shortage Amount (`number`, `min="0"`), Shortage Classification (`select`, options: Market Short / Deliveryman Short / Approved Write-Off / Pending Investigation), Settlement Status (`select`, options: Pending / Settled / Closed); Save + Cancel buttons
    - _Requirements: 22.2, 22.3, 23.4, 23.5_
  - [ ] 8.4 Add Delete confirmation template: "Are you sure you want to delete Settlement <span x-text="selected?.settlement_ref"></span>?"
    - _Requirements: 22.4, 22.5, 23.5_

- [ ] 9. Checkpoint — verify all 8 pages render correctly
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 10. Write PHPUnit feature tests for Property 10 and Property 11
  - [ ] 10.1 Create `tests/Feature/CrudModalAlpineStateTest.php` — for each of the 8 list page URLs, assert HTTP 200 and that the response contains `x-data` with the substrings `open`, `mode`, and `selected`
    - **Property 10: Every CRUD-enabled list page initialises Alpine state without errors**
    - **Validates: Requirements 13.5, 14, 15.5, 16, 17.8, 18.8, 19.8, 20.6, 21.8, 22.6, 23.7**
  - [ ]* 10.2 Extend `CrudModalAlpineStateTest` with a data provider covering all 8 routes, asserting the `x-data` value does not contain unescaped double-quotes (i.e., PHP `json_encode` was used correctly for row data) — use a `#[DataProvider]` with all 8 route URLs
    - **Property 10: Alpine x-data is syntactically safe across all pages**
    - **Validates: Requirements 13.5, 15.5, 23.7**
  - [ ] 10.3 Create `tests/Feature/ModalRequiredFieldsTest.php` — for each page that has a create/edit modal, assert that the response HTML contains `required` on the expected field inputs; assert the required-field list against the per-page field tables in the design
    - **Property 11: Required modal fields carry the HTML `required` attribute**
    - **Validates: Requirements 13.2, 15.2, 17.2, 18.2, 19.2, 21.2, 23.5**
  - [ ]* 10.4 Extend `ModalRequiredFieldsTest` with negative assertions — verify that optional fields (e.g., Vehicle, Contact Person, Source DLF) do NOT carry the `required` attribute, ensuring no over-specification
    - **Property 11: Optional fields must not be incorrectly marked required**
    - **Validates: Requirements 13.2, 15.2, 17.2_

- [ ] 11. Final checkpoint — run the full test suite
  - Ensure all tests pass, ask the user if questions arise.

---

## Notes

- Tasks marked with `*` are optional and can be skipped for an faster MVP.
- Every modal is inlined directly in its list page view — no new Blade component files are introduced.
- No POST/PUT/DELETE routes are added; Save and Confirm Delete buttons call `close()` only.
- The shared Alpine.js pattern (tasks 1.1–1.5) is the reference implementation; tasks 2–8 follow the same structure with page-specific field lists.
- All inline styles use the design system palette: blue primary `#3b82f6`, green edit `#f0fdf4/#16a34a`, red delete `#fff1f2/#ef4444`, grey cancel `#f1f5f9/#64748b`.
- Run tests with: `php artisan test --compact`
- Run a single test file with: `php artisan test --compact --filter=CrudModalAlpineStateTest`

---

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1"] },
    { "id": 1, "tasks": ["1.2", "1.3"] },
    { "id": 2, "tasks": ["1.4", "1.5"] },
    { "id": 3, "tasks": ["2.1", "3.1", "4.1", "5.1", "6.1", "7.1", "8.1"] },
    { "id": 4, "tasks": ["2.2", "3.2", "4.2", "5.2", "6.2", "7.2", "8.2"] },
    { "id": 5, "tasks": ["2.3", "3.3", "4.3", "5.3", "6.3", "7.3", "8.3"] },
    { "id": 6, "tasks": ["2.4", "3.4", "4.4", "5.4", "6.4", "7.4", "8.4"] },
    { "id": 7, "tasks": ["10.1", "10.3"] },
    { "id": 8, "tasks": ["10.2", "10.4"] }
  ]
}
```
