const formElement = document.querySelector("form");

formElement.addEventListener("submit", async (event) => {
  event.preventDefault();
  var formData = new FormData(formElement);
  let response = await fetch("submit.php", {
    method: "POST",
    body: formData,
  });
  let data = await response.json();
  if (data.type === "success") {
    window.location.href = "/dashboard/";
  } else {
    document.querySelector(".message").textContent = data.message;
  }
});
