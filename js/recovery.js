document.addEventListener("DOMContentLoaded", function () {
  var m = document.getElementById("recovery-method"),
    i = document.getElementById("identifier"),
    l = document.getElementById("identifier-label");
  function sync() {
    var p = m.value === "phone";
    i.type = p ? "tel" : "email";
    i.autocomplete = p ? "tel" : "email";
    i.placeholder = p ? "+260..." : "you@example.com";
    l.textContent = p ? "Registered phone number" : "Registered email address";
  }
  m.addEventListener("change", sync);
  sync();
});
