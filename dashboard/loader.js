let loaderEl = document.querySelector("#loader-container");

document.addEventListener("DOMContentLoaded", () => {
  // Show loader when the page is being refreshed
  window.addEventListener("beforeunload", () => {
    loaderEl.style.display = "flex";
  });

  setTimeout(() => {
    loaderEl.style.display = "none";
  }, 500);
});
