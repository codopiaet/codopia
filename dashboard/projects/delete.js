const deleteBtns = document.querySelectorAll(".delete-btn");

deleteBtns.forEach(btn => {
    btn.addEventListener("click", event => {
        const targetBtn = event.target;
        const projectId = targetBtn.getAttribute("projectId");


        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, do it!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();

                    xhr.open('POST', './delete.php', true);

                    xhr.setRequestHeader('Content-Type', 'application/json');

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            resolve(response);
                            switch (response.CODE) {
                                case "2001":
                                    Swal.fire({
                                        title: `ERROR: ${response.CODE}`,
                                        text: response.MESSAGE,
                                        icon: "error"
                                    });
                                    break;
                                default:
                                    document.getElementById("project_table").children[1].removeChild(targetBtn.parentElement.parentElement);
                            }
                        } else {
                            reject('Something went wrong!');
                        }
                    };

                    // What to do when the request fails
                    xhr.onerror = function () {
                        // Reject the promise with an error message
                        reject('Something went wrong!');
                    };

                    xhr.send(projectId);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleting project...",
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                })
            }
        });


        // xhr.onload = function () {
        //     if (xhr.status == 200) {
        //         switch (xhr.response) {
        //             case "ERROR":
        //                 Swal.fire({
        //                     title: "ERROR!",
        //                     text: "Could not delete project!",
        //                     icon: "warning"
        //                 });
        //                 break;
        //             default:
        //                 document.getElementById("project_table").children[1].removeChild(targetBtn.parentElement.parentElement);
        //         }
        //     }
        // }
    });
});