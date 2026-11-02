GREEN URBAN FARMS — CONNECTED PHP PAGES

IMPORTANT: This is an update pack, not a replacement for your entire website.
1. Back up your existing GUF website folder.
2. Extract the pack into the SAME folder as your existing index.html, style1.css, Logo.png, and db.php. Allow the included HTML pages to update their links if Windows asks about replacing files. The pack does not contain db.php or your image/CSS/JS assets.
3. Do NOT overwrite or delete your existing db.php. It already works. Make sure it connects to your actual database `green_urban` and defines `$pdo`.
4. Open `http://localhost/YOUR_FOLDER/index.php` (replace YOUR_FOLDER with the folder name under C:\xampp\htdocs).
5. Use index.php as the homepage. The homepage has a registration form that inserts into `users`; passwords are hashed.
6. Navigation on PHP pages changes based on login status. Guests see Home, Programs, About Us, and Log in. Signed-in users see the extra pages. Admins also see Admin.
7. The dynamic pages use the existing tables: users, programs, events, resources, contact_messages, volunteer_applications, event_registrations.
8. To make the first admin: register a normal account on the site, then in phpMyAdmin select database `green_urban`, open `users`, click Browse, edit only your own account's `role` from `user` to `admin`, and save. Never expose admin registration publicly.
9. Sign in with that account and open Admin to publish programs/events and view user accounts, contact messages, applications, and event registrations.
10. For event registration, publish an event first, then visitors can click Register for this event on events.php.
11. Test contact/application/event registration using test submissions. Check the matching table in phpMyAdmin.
12. Delete any temporary database test scripts when finished.

NOTES:
- The database connection is intentionally not included as db.php, to protect your working credentials. `db.php.example` is reference only.
- Keep your current assets (style1.css, script.js, Logo.png, images) in the website folder.
- Static content such as about.html can remain HTML.
- Use Apache through http://localhost, not by opening PHP files directly from File Explorer.
