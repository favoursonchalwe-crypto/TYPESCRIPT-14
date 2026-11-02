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

    // Toggle the responsive navigation menu.
    var button = document.querySelector('.menu-btn');
    var header = button ? button.closest('header') : null;
    var nav = header ? header.querySelector('nav') : document.querySelector('.site-header nav');

    if (button && nav) {
        button.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');

            button.setAttribute('aria-expanded', String(open));
            button.setAttribute(
                'aria-label',
                open ? 'Close menu' : 'Open menu'
            );
        });
    }
});
