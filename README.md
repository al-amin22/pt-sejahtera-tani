# PT Sejahtera Tani

Web application portfolio project built with Laravel for operational management, transaction tracking, staff workflows, and reporting.

## Highlights

- Role-based access for admin, staff, and finance users
- Dashboard views for monitoring operational data
- CRUD modules for employees, transactions, attendance, accounts, and production results
- PDF export support for reporting workflows
- Clean public landing page for portfolio presentation

## Tech Stack

- Laravel 11
- PHP 8.2+
- Blade templates
- MySQL or compatible database
- DomPDF for exports

## Main Modules

- Users and role management
- Karyawan and absensi tracking
- Transaksi and detail transaksi
- Mata uang and rekening management
- Hasil produksi handling
- Finance dashboard access

## Project Structure

- routes/web.php — application routes and role-based access groups
- app/Http/Controllers — business logic controllers
- resources/views — Blade views for dashboards and management pages
- database/migrations — database schema definitions

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

## Running Locally

```bash
php artisan serve
```

## Recommended Portfolio Improvements

1. Add screenshots of the dashboards and CRUD pages.
2. Add seed data for a clean demo environment.
3. Standardize validation and error handling across controllers.
4. Add automated tests for critical workflows.

## Notes

This repository contains an active business-style Laravel application. For a portfolio version, the public landing page and documentation have been improved to present the project more professionally.
