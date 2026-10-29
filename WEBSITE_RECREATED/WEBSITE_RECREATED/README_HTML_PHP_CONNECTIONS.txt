GREEN URBAN FARMS — UPDATED HTML/PHP CONNECTIONS

1. Copy the WEBSITE_RECREATED folder into C:\xampp\htdocs\ (or copy its contents into your existing WEBSITE_RECREATED folder).
2. Start Apache and MySQL in XAMPP.
3. Confirm php_backend/DB.php points to your existing green_urban database. Do not drop or recreate the database.
4. Open http://localhost/WEBSITE_RECREATED/ in your browser.
5. The Get Involved form posts to php_backend/get-involved.php and the Contact Us form posts to php_backend/contact.php. The handlers insert into volunteer_applications and contact_messages respectively.
6. The login/register forms post to php_backend/login.php. Ordinary users are redirected to the designed HTML homepage; administrators are redirected to php_backend/admin.php.
7. Programs, Resources and Events have PHP database-backed pages under php_backend/. Their designed HTML pages remain available as static visual pages, so static HTML content will not automatically update from the database. Use the PHP pages when you need live database content.

IMPORTANT: Back up your local website folder before replacing it. This ZIP does not contain or replace your MySQL data directory and does not recreate/drop your green_urban database.
