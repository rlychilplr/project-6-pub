window.addEventListener("storage", function (e) {
  if (e.key === "logout" || e.key === "login") {
    window.location.reload();
  }
});
