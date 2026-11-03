document.addEventListener("DOMContentLoaded", function () {
  var script = document.querySelector('script[src*="access-control.js"]');
  var scriptUrl =
    script && script.src
      ? script.src
      : new URL("js/access-control.js", window.location.href).href;
  var backendUrl = new URL("../php_backend/", scriptUrl);
  var statusUrl = new URL("session-status.php", backendUrl).toString();
  var registerUrl = new URL("login.php?action=register", backendUrl).toString();
  var loginUrl = new URL("login.php", backendUrl).toString();
  var pathname = window.location.pathname.replace(/\\/g, "/");
  if (pathname.endsWith("/")) pathname += "index.html";

  var publicLabels = ["home", "about us", "programs", "login", "log in"];
  var allowedPaths = [
    "/index.html",
    "/about/index.html",
    "/programs/index.html",
    "/login/index.html",
  ];
  var isAuthPage =
    /\/php_backend\/(login|forgot-password|reset-password)\.php$/.test(
      pathname,
    );

  function restrictHeaderNavigation() {
    var nav = document.querySelector(".site-header nav");
    if (!nav) return;

    Array.prototype.slice
      .call(nav.querySelectorAll("a"))
      .forEach(function (link) {
        var label = (link.textContent || "")
          .trim()
          .toLowerCase()
          .replace(/\s+/g, " ");
        if (publicLabels.indexOf(label) === -1) {
          var item = link.closest("li");
          if (item) item.remove();
          else link.remove();
        }
      });

    var list = nav.querySelector(".nav-links");
    if (list) {
      var order = {
        home: 0,
        "about us": 1,
        programs: 2,
        login: 3,
        "log in": 3,
      };
      Array.prototype.slice
        .call(list.querySelectorAll("a"))
        .sort(function (a, b) {
          var aLabel = (a.textContent || "").trim().toLowerCase();
          var bLabel = (b.textContent || "").trim().toLowerCase();
          return (
            (order[aLabel] === undefined ? 99 : order[aLabel]) -
            (order[bLabel] === undefined ? 99 : order[bLabel])
          );
        })
        .forEach(function (link) {
          var item = link.closest("li");
          if (item) list.appendChild(item);
        });
    }
  }

  function sendCallsToActionToRegistration() {
    var isPublicPage = allowedPaths.some(function (path) {
      return pathname.endsWith(path);
    });
    if (!isPublicPage) return;

    document
      .querySelectorAll(
        'main a.btn, main a[class*="btn-"], main a.cta, main button[data-register-cta]',
      )
      .forEach(function (cta) {
        if (cta.tagName.toLowerCase() === "a") {
          cta.setAttribute("href", registerUrl);
          cta.addEventListener("click", function (event) {
            event.preventDefault();
            window.location.href = registerUrl;
          });
        } else {
          cta.addEventListener("click", function (event) {
            event.preventDefault();
            window.location.href = registerUrl;
          });
        }
      });

    document
      .querySelectorAll('main button:not(.menu-btn):not([type="submit"])')
      .forEach(function (button) {
        button.addEventListener("click", function (event) {
          event.preventDefault();
          window.location.href = registerUrl;
        });
      });
  }

  fetch(statusUrl, { credentials: "same-origin", cache: "no-store" })
    .then(function (response) {
      if (!response.ok) throw new Error("Session status unavailable");
      return response.json();
    })
    .then(function (state) {
      if (state && state.registerUrl) registerUrl = state.registerUrl;
      if (state && state.loginUrl) loginUrl = state.loginUrl;

      if (!state || !state.loggedIn) {
        restrictHeaderNavigation();
        sendCallsToActionToRegistration();
        document
          .querySelectorAll('a[href*="login"], a[href*="Account login"]')
          .forEach(function (link) {
            if (!link.closest("nav") && !link.closest("form")) {
              var href = link.getAttribute("href") || "";
              if (
                href.indexOf("forgot-password") === -1 &&
                href.indexOf("action=register") === -1
              ) {
                link.setAttribute("href", loginUrl);
              }
            }
          });

        var isAllowed = allowedPaths.some(function (path) {
          return pathname.endsWith(path);
        });
        if (!isAllowed && !isAuthPage && /\.html?$/i.test(pathname)) {
          window.location.replace(registerUrl);
        }
      }
    })
    .catch(function () {
      /* Fail closed for navigation when PHP/session status is unavailable. */
      restrictHeaderNavigation();
      sendCallsToActionToRegistration();
      var isAllowed = allowedPaths.some(function (path) {
        return pathname.endsWith(path);
      });
      if (!isAllowed && !isAuthPage && /\.html?$/i.test(pathname)) {
        window.location.replace(registerUrl);
      }
    });
});
