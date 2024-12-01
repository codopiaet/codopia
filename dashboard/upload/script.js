document.getElementById("upload-form").addEventListener("submit", function (event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // // Log the contents of formData
    // for (const pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }

    const xhr = new XMLHttpRequest();
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.response);
            if (response.ERROR_CODE) {
                Swal.fire({
                    icon: "error",
                    title: `ERROR: ${response.ERROR_CODE}`,
                    text: response.ERROR_MESSAGE,
                    footer: '<a href="../feedback/">Why do I have this issue?</a>'
                });
            } else {
                Swal.fire({
                    icon: "success",
                    title: "Uploaded!",
                });
                form.reset();
                document.getElementById('file-name').textContent = "No file chosen";
            }
        } else {
            Swal.fire({
                icon: "error",
                title: "REQUEST FAILED",
                text: xhr.response,
                footer: '<a href="../feedback/">Why do I have this issue?</a>'
            });
        }
    };
    xhr.open("POST", "./toProjects.php", true);
    xhr.send(formData);
});
