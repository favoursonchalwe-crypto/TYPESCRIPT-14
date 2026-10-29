GREEN URBAN FARMS — CONNECT TO YOUR EXISTING DATABASE

This package is configured for your existing MySQL database named green_urban. Do NOT import schema_green_urban.sql or schema_reference_do_not_reimport.sql: those are reference files and do not match every column in your existing database.

INSTALL
1. Back up your current website folder.
2. Extract the WEBSITE_RECREATED folder into C:\xampp\htdocs\ so the final path is C:\xampp\htdocs\WEBSITE_RECREATED\.
3. Start Apache and MySQL in the XAMPP Control Panel.
4. Confirm phpMyAdmin opens at http://localhost/phpmyadmin/ and that green_urban exists.
5. Open http://localhost/WEBSITE_RECREATED/ . The root index.php forwards to the PHP-backed homepage.
6. DB.php is in WEBSITE_RECREATED\php_backend\ and connects to database green_urban using the XAMPP default user root with a blank password. If your MySQL root password is not blank, update $password in DB.php only.

CONNECTED FEATURES
- Account registration and login use the existing users table columns id, name, email, password, role, created_at. Passwords are stored with password_hash(). New registrations are always role=user.
- Contact messages insert into contact_messages.
- Volunteer applications insert into volunteer_applications using the existing name, email, phone, interests, experience, availability, status columns.
- Programs, events, and resources read from the existing tables using the previously shared column names.
- Admin dashboard uses the existing tables and requires a logged-in account whose users.role is admin.

TEST
1. Open the website URL above.
2. Register a test account, then verify a new row appears in phpMyAdmin > green_urban > users.
3. Log in with the test account.
4. Submit a test contact message and volunteer application; verify rows appear in contact_messages and volunteer_applications.
5. To enable admin access, change only your own account's role to admin in phpMyAdmin > green_urban > users. Do not expose a public admin-role selector.

If a database error appears, confirm that the existing tables/columns match the schemas you previously shared and check the Apache/PHP error log. Do not drop or recreate the database.
