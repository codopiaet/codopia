// const form = document.querySelector(".editProject")
// form.addEventListener("submit", async e=>{
//   e.preventDefault()
//   let formData = new FormData(form);
//   formData.append("projId", e.target.id)
//   let data = await fetch("submit.php", {
//     method: "POST",
//     body: formData
//   }).then(resp=>resp.json())
//   if (data.success) {
//     alert("Successfully edited")
//   }
//   else {
//     alert(data.error)
//   }
// })