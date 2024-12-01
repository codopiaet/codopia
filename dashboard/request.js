document.addEventListener("DOMContentLoaded", () => {

    // MARK: LIKE XHR
    const likers = document.querySelectorAll(".liker");
    likers.forEach(btn => {
        btn.addEventListener("click", event => {
            const target = event.target;
            const projectData = JSON.stringify({
                project_id: target.getAttribute("project_id")
            });
    
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "./likeHandler.php", true);
            xhr.onload = function () {
                if (xhr.status == 200) {
                    // if (xhr.response == "LIKED") target.parentNode.parentNode.children[2].textContent++;
                    // else if (xhr.response == "UNLIKED") target.parentNode.parentNode.children[2].textContent--;
                    switch (xhr.response) {
                        case "LIKED":
                            target.parentNode.parentNode.children[2].textContent++;
                            break;
                        case "UNLIKED":
                            target.parentNode.parentNode.children[2].textContent--;
                            break;
                    }
                }
            }
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(projectData);
        });
    });

    // MARK: EVENT XHR
    const form = document.getElementById("event-form");
    const joinBtn = document.querySelector(".join-btn");
    const totalXp = document.getElementById("total-xp");
    const eventXp = document.getElementById("event-xp");
    
    form.addEventListener("submit", event => {
        event.preventDefault();
        const checked = document.querySelector(".condition").checked ? 1: -1;
        const target = event.target;
        const eventData = JSON.stringify({
            condition: checked,
            event_id: target.getAttribute("event_id"),
            xp: target.getAttribute("xp")
        })

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "./acceptHandler.php", true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                if (xhr.response == "ACCEPTED") {
                    joinBtn.style.backgroundColor = "#dc3545";
                    joinBtn.textContent = "Reject";
                    totalXp.textContent = Number(totalXp.textContent) + Number(eventXp.textContent);
                } else if (xhr.response == "REJECTED") {
                    joinBtn.style.backgroundColor = "#0d6efd";
                    joinBtn.textContent = "Join";
                    totalXp.textContent = Number(totalXp.textContent) - Number(eventXp.textContent);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                        // footer: '<a href="#">Why do I have this issue?</a>'
                    });
                }
            }
        }
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(eventData);
    })

});