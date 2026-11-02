GREEN URBAN FARMS — RECREATED WEBSITE

START HERE
- Open index.html in a browser for a static preview.
- For XAMPP, copy the WEBSITE_RECREATED folder to C:\\xampp\\htdocs and visit http://localhost/WEBSITE_RECREATED/.

STRUCTURE
- index.html: root homepage, using the same shared visual system as the other pages.
- home/, about/, programs/, resources/, events/, get-involved/, contact/, login/: the eight main page folders. Each contains index.html and assets/images and assets/scripts subfolders.
- programs/, resources/, and events/ also contain their related subpages.
- css/style.css: all website CSS is kept in this one stylesheet. No inline or embedded CSS is used in the HTML pages.
- js/script.js: responsive navigation and footer year.
- images/: shared logo and background image.
- php_backend/: original PHP and SQL reference files copied from the supplied ZIP. They are preserved separately and are not wired into these new static pages automatically.

DESIGN
The shared layout follows the homepage style across all pages. Green and blue are the main colours, with responsive navigation, reusable cards, page headers, forms and a consistent footer.

IMPORTANT
The contact, login/register and volunteer forms are front-end layouts at this stage. To save submissions or authenticate users, connect them to the existing PHP handlers and database. Do not import a schema into an existing database without checking it first.
