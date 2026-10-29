GREEN URBAN FARMS - HOMEPAGE AND LOGIN SETUP

1. Copy the contents of this folder into C:\xampp\htdocs\WEBSITE\ (merge with your current project and back up first).
2. Ensure Apache and MySQL are running in XAMPP.
3. In phpMyAdmin, make sure database green_urban exists and its users table has columns: id, name, email, password, role, created_at.
4. If your existing db.php already connects successfully, keep your working copy if its settings differ; otherwise use the included db.php (XAMPP default root with blank password).
5. Back up the old index.html, then rename it to index_backup.html so it does not override index.php as the default homepage. Open http://localhost/WEBSITE/index.php . Do not open PHP files by double-clicking them.
6. Homepage main navigation has only Home, Programs, About Us. Visitors who are not logged in see a registration form on the homepage. Existing account holders can choose “Already have an account? Log in” to open login.php. Registration stores passwords using password_hash().
7. New public registrations are always role=user. To make an account an administrator, change that user's role to admin directly in phpMyAdmin. Never allow visitors to select the admin role themselves.
8. Existing users must have passwords stored with PHP password_hash(). If old accounts have plain-text passwords or a different hash format, those accounts need a secure password reset/migration before password_verify() can authenticate them.

IMPORTANT: The original GUF.zip did not contain style1.css, script.js, Logo.png, or other image assets referenced by its HTML files. Add those files from your existing working project folder so the original design and menu behavior appear correctly.
