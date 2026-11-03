GREEN URBAN FARMS - PHP / DATABASE PAGES

Included pages and purpose:
- index.php: public homepage; should show Home, Programs, About Us to visitors.
- login.php: registration and login. New accounts are always role=user.
- logout.php: clears login session.
- auth.php: shared login/admin access guards and HTML escaping helper.
- admin.php: admin-only dashboard; lists users/messages/applications and lets admins publish programs/events.
- contact.php: stores contact form messages in contact_messages.
- get-involved.php: stores participation/volunteer applications in volunteer_applications.
- programs.php: reads published programs from programs table.
- events.php: reads published future events from events table.
- resources.php: reads published resources from resources table.
- db.php.example: sample connection only. Since you already have db.php connected, KEEP YOUR WORKING db.php. Make sure it defines a PDO variable named $pdo and points to your existing database.
- schema_green_urban.sql: schema based on the uploaded guf_database.sql. Import only if tables are missing; it uses CREATE TABLE IF NOT EXISTS and does not drop tables.

IMPORTANT BEFORE USE:
1. Back up your current GUF folder and database.
2. Copy these PHP files into the same XAMPP htdocs folder as the website. Do not overwrite your working db.php or existing CSS/assets.
3. Check the database name in db.php. The uploaded SQL used green_urban_farms, but if your existing database is green_urban, keep that name in db.php.
4. The users table must contain: id, name, email, password_hash, role, created_at. The included login code uses password_hash (not a column named password).
5. Contact page uses contact_messages(name,email,subject,message,submitted_at).
6. Get involved uses volunteer_applications(user_id,full_name,email,phone,participation_type,interests,availability,message,status,submitted_at).
7. Import schema only after confirming table/column compatibility. Existing tables with different columns are NOT automatically changed by CREATE TABLE IF NOT EXISTS.
8. Create the first administrator safely: register a normal account at login.php, then in phpMyAdmin select the correct users row and change role from user to admin. Do not make a public admin registration form.
9. Open via http://localhost/YOUR_FOLDER/index.php, never by double-clicking a PHP file.
10. Change links from old .html pages to the new .php pages where dynamic database content is wanted (programs.php, events.php, resources.php, contact.php, get-involved.php).

Security notes: public registration cannot choose admin role; SQL uses prepared statements; passwords are hashed. This is a development starter, not a substitute for full production hardening (CSRF protection, rate limiting, email verification, backups, HTTPS).
