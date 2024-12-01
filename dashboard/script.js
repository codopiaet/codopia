
// ---------------------------------------------------------------------


// Font

// const { error } = require("console");

const fontChangers = document.querySelectorAll(".font-changer");

if (localStorage.getItem('theme') === 'dark') {

}
for (let i = 0; i < fontChangers.length; i++) {
  fontChangers[i].addEventListener("click", (event) => {
    localStorage.setItem("font-family", `${event.target.textContent}`);
    document.querySelector("style").innerHTML = `*{font-family:${localStorage.getItem("font-family")};}`;
  })
}

// Share (I think the following method works only on chrome)

const share = () => {
  navigator.share({
    text: "Codopia",
    url: window.location.href, //we are going to change it,
    text: "share your icon"
  })
}

// Share (Copying the link)

function copyLinkToClipboard(project_id) {
  console.log(project_id);
  const url = `${window.location.href.split("#")[0]}#${project_id}`;
  navigator.clipboard.writeText(url).then(() => {
    alert("Link copied to clipboard!");
  }).catch(error => {
    alert(`Failed to copy link: ${error}`);
  });
}

// Search

const search = document.getElementById("search");
const items = document.querySelectorAll(".search-item");
search.addEventListener("keyup", () => {
  items.forEach(item => {

    if (item.textContent.toLowerCase().includes(search.value.toLowerCase())) {
      if (search.value != '') {
        item.style.backgroundColor = "yellow"
      }
      else {
        item.style.background = "none"
      }
    }
    // else{
    //  item.parentElement.parentElement.style.display="none"
    // }
  })
})

// const setDarkMode = () => {
//   localStorage.setItem("theme", "dark")
//   location.reload()
// }

// ----------------------------------------------------

const searchInput = document.getElementById('search');
const projectStyles = document.querySelectorAll('.project-style');

searchInput.addEventListener('input', function () {
  const searchQuery = this.value.toLowerCase();
  projectStyles.forEach(function (project) {
    const username = project.querySelector('.search-item.username').textContent.toLowerCase();
    const fullName = project.querySelector('.search-item.full_name').textContent.toLowerCase();
    const projectName = project.querySelector('.search-item.project_name').textContent.toLowerCase();
    const projectDetail = project.querySelector('.search-item.project_detail').textContent.toLowerCase();
    if (
      username.includes(searchQuery) ||
      fullName.includes(searchQuery) ||
      projectName.includes(searchQuery) ||
      projectDetail.includes(searchQuery)
    ) {
      project.style.display = 'block';
    } else {
      project.style.display = 'none';
    }
  });
});
