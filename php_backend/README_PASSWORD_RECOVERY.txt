GREEN URBAN FARMS - PASSWORD RECOVERY SETUP

1. Back up the green_urban database.
2. In phpMyAdmin, select green_urban > SQL and run password_recovery_migration.sql ONCE.
   If phone already exists, remove the ALTER TABLE and CREATE INDEX lines before running.
3. Edit recovery-config.php. For sandbox testing, create a free Africa's Talking Sandbox app,
   generate its API key, and replace PASTE_SANDBOX_API_KEY_HERE. Keep AT_USERNAME as sandbox.
4. Email uses PHP mail(). Confirm that your hosting provider supports outgoing PHP mail and
   configure a valid sender address. On local XAMPP, mail normally needs SMTP configuration;
   the page still shows a generic response even when delivery is not configured.
5. SMS Sandbox messages appear in the Africa's Talking simulator/dashboard inbox, not on a
   real handset. Live SMS requires a production app, approved sender configuration and paid
   messaging balance. Change the endpoint to the live API only after production credentials
   and sender approval are ready.
6. Existing users need a phone number saved in users.phone before SMS recovery can work.
   New registrations can supply an optional mobile number.
7. Upload the PHP files and migration/config to the site. Use HTTPS in production. Do not
   commit real API keys; keep them in a private server-side config or environment variables.

Files added: forgot-password.php, verify-reset-code.php, reset-password.php,
recovery-common.php, recovery-config.php, password_recovery_migration.sql.
