document.addEventListener('DOMContentLoaded', function () {
    // Update the footer year when a matching element is present.
    var year = document.querySelector('[data-year]');

    if (year) {
        year.textContent = new Date().getFullYear();
    }

    // Toggle between the login and registration forms.
    var formSwitches = document.querySelectorAll('[data-form-target]');
    var formBoxes = document.querySelectorAll('.auth-forms .form-box');
    var authTabs = document.querySelectorAll('.auth-tab');

    function showForm(formId) {
        var targetForm = document.getElementById(formId);

        if (!targetForm || !targetForm.classList.contains('form-box')) {
            return;
        }

        formBoxes.forEach(function (form) {
            form.classList.toggle('active', form.id === formId);
        });

        authTabs.forEach(function (tab) {
            var selected = tab.getAttribute('data-form-target') === formId;
            tab.classList.toggle('is-selected', selected);
            tab.setAttribute('aria-pressed', String(selected));
        });
    }

    formSwitches.forEach(function (control) {
        control.addEventListener('click', function (event) {
            var formId = control.getAttribute('data-form-target');

            if (formId) {
                event.preventDefault();
                showForm(formId);
            }
        });
    });

    // Responsive navigation menu: use delegation so included PHP headers work too.
    function setupMenu(button) {
        if (button.dataset.menuReady === 'true') return;
        var header = button.closest('.site-header') || button.closest('header');
        var nav = header ? header.querySelector('nav') : null;
        if (!nav) return;
        button.dataset.menuReady = 'true';
        button.setAttribute('aria-controls', nav.id || 'primary-navigation');
        if (!nav.id) nav.id = 'primary-navigation';
        button.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            button.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
            button.textContent = isOpen ? '✕' : '☰';
        });
        nav.addEventListener('click', function (event) {
            if (event.target.closest('a') && window.matchMedia('(max-width: 1050px)').matches) {
                nav.classList.remove('is-open');
                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-label', 'Open menu');
                button.textContent = '☰';
            }
        });
    }
    document.querySelectorAll('.menu-btn').forEach(setupMenu);

});
