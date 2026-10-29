GREEN URBAN FARMS - ALEFOX-INSPIRED STYLE UPDATE

This update adds a shared style1.css inspired by the clean organic-farming look of Alefox: the original Green Urban Farms blue palette (#7494ec primary and #6884d3 hover), agricultural accents, light backgrounds, strong headings, clean cards, rounded forms, and responsive layouts. It is an original adaptation, not a copy of Alefox's proprietary template files.

DATABASE
- The PHP configuration uses database green_urban. If your existing db.php already connects correctly, keep your working credentials/settings.
- Public registration always creates role=user. The homepage registration form saves users into the users table using password_hash().
- The database must have a users table with id, name, email, password, role, created_at (created_at may be automatic/defaulted).

FIRST ADMIN (Option C)
1. Register your own account through the homepage.
2. In phpMyAdmin, open green_urban > users and find that account by email.
3. Edit its role field from user to admin and save. Do not let the public choose the admin role on a form.
4. Log out and log in again. Admin accounts are redirected to admin.php.
5. Only grant admin role to a trusted person. Additional admins should be created/assigned by an existing trusted admin or by an authorized database administrator.

INSTALL
1. Back up your current project folder first.
2. Extract this ZIP into C:\xampp\htdocs\WEBSITE\ (or your existing project folder), merging the files.
3. Keep your current working db.php credentials if they differ from the included XAMPP defaults.
4. Rename the old index.html to index_backup.html so Apache opens index.php as the homepage.
5. Start Apache and MySQL in XAMPP.
6. Open http://localhost/WEBSITE/index.php in the browser. Do not open PHP files by double-clicking them.

NAVIGATION
Visitors see only Home, Programs, and About Us in the main menu. The homepage registration form is displayed while logged out. Other footer links are hidden for logged-out visitors. Note: any pages that should require login/admin access must also enforce session/role checks on the PHP server side; hiding links alone is not security.

ASSETS
This package contains a shared CSS and small JS file. Add any original farm photos/logo assets from your existing project if you have them. The layout works without them, using a blue hero treatment.

Brand colours: The original login page uses #7494ec for buttons and links and #6884d3 for hover states. These colours are now applied consistently to the site buttons, links, forms, and accents; deep navy is used for contrast. The layout is Alefox-inspired, not a copy of its proprietary files.
