GREEN URBAN FARMS — XAMPP PAGE ROUTING

1. Extract the complete WEBSITE_RECREATED folder into C:\xampp\htdocs\.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Open http://localhost/WEBSITE_RECREATED/ in your browser.
   The root index.php now redirects to php_backend/index.php, the PHP-powered homepage.
4. Do not double-click .php files or open them using file:/// paths. PHP must be served through Apache at localhost.
5. Database-backed pages are served by PHP:
   - php_backend/index.php (homepage)
   - php_backend/programs.php
   - php_backend/events.php
   - php_backend/resources.php
   - php_backend/get-involved.php
   - php_backend/contact.php
   - php_backend/login.php
   - php_backend/admin.php (administrator account required)
6. Some informational pages are intentionally static HTML pages, including About Us,
   Growing Tips, Beginner Guides, FAQs, and individual program information pages.
   They are not PHP pages because they do not need to process database submissions.
7. Keep the php_backend folder, css folder, and images folder in their original locations.
   The PHP pages reference ../css and ../images.
8. If you use a different project folder name, change WEBSITE_RECREATED in the URL accordingly.

IMPORTANT: Keep your existing db.php/DB.php database connection configuration and verify
that its filename casing matches the require_once statement on your hosting environment.
