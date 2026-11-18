# MZOUTI Files

Laravel 12 application shell with Supabase Auth, PostgreSQL, Storage and RLS.

## Local setup
1. PHP 8.2+ and Composer 2.
2. `composer install`
3. `cp .env.example .env`
4. Generate an application key: `php artisan key:generate`
5. `php artisan serve`

## Supabase
Project ref: `gmisfyvjuifptwvwsuax`. The initial schema, RLS policies, private bucket and RPCs have been provisioned. The publishable key is public by design; never expose a service_role or secret key.

## Google OAuth
The sign-in button calls Supabase Auth with provider `google`. In Supabase Dashboard > Authentication > Sign In / Providers > Google, enable Google and enter the Google OAuth Web Client ID and Client Secret. In Google Cloud, add this callback:
`https://gmisfyvjuifptwvwsuax.supabase.co/auth/v1/callback`
In Supabase URL Configuration, add the deployed Render URL and local `http://localhost:8000` to the redirect allow list. Google OAuth cannot complete until real Google credentials are supplied.

## Render
Deploy this repository as a Docker web service. Set APP_KEY to a generated Laravel key and APP_URL to the Render URL. Add MonCash/NatCash recipient name and numbers as private environment variables before accepting payments.

## Admin
The database RPC `approve_order_and_issue_code` checks trusted `app_metadata.role=admin`, approves a pending order, stores only a SHA-256 hash, and sends the raw code to the customer's notification. Never use editable user_metadata for admin authorization.

## Important
This is an initial working web foundation, not a production-complete admin CMS. Test OAuth, storage, proof uploads, payment approval and access-code flows before accepting payments.
