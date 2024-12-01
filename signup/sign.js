// ### Loader code ###

let loaderEl = document.querySelector("#loader-container");

document.addEventListener("DOMContentLoaded", () => {

  // Show loader when the page is being refreshed
  window.addEventListener("beforeunload", () => {
    loaderEl.style.display = "flex";
  });

  // Hide loader once the page has loaded (after 5 seconds)
  loaderEl.style.display = "none";
});

const upForm = document.querySelector("form");
const textArea = document.getElementById("textarea");


document.getElementById("username").addEventListener("blur", () => {
  let form = new FormData();
  form.append("type", 99);
  form.append("username", document.getElementById("username").value)
  fetch("submit.php", {
    method: "POST",
    body: form
  }).then(resp => resp.json())
    .then(data => {
      if (data.success) {
        document.getElementById("information").textContent = data.success; document.getElementById("information").style.color = "green"
      }
      else if (data.error) document.querySelector(".message").textContent = data.error
      else if (data.info) {
        document.getElementById("information").textContent = data.info;
        document.getElementById("information").style.color = "red"
      }
    })
})

// upForm.addEventListener("submit", async e => {
//   e.preventDefault();

//   const formData = new FormData(upForm);
//   formData.append("bio", textArea.value);

//   let res = await fetch("./mail.php", {
//     method: "POST",
//     body: formData
//   })
//   let data = await res.json();

//   const message = document.querySelector(".message");
//   if (typeof data !== "object") data = JSON.parse(data);
  
//   if (data.type === 'error') {
//     message.classList.add(data.type);
//     document.querySelector('.message').textContent = data.message;
//     if (data.code) {
//       message.textContent += "Code: " + data.code;
//     }
//   }

//   // --------------------------------------------------------

//   else {
//     switch (data.STATUS_CODE) {
//       case "001": // success
//         console.log(data.STATUS_MESSAGE);
//         break;
//       case "002": // attempt limit reached
//         Swal.fire({
//           title: "Limit reached!",
//           text: data.STATUS_MESSAGE,
//           icon: "error"
//         });
//         break;
//       case "003":
//         Swal.fire({
//           title: "Fatal error!",
//           text: data.STATUS_MESSAGE,
//           icon: "error"
//         });
//         break;
//       default:
//         Swal.fire({
//           title: "Oops!",
//           text: `Something went wrong!`,
//           icon: "error"
//         });
//     }
//   }









  // --------------------------------------------------------


// })