let openModalButton = document.getElementById('modal-button');
let modal = document.getElementById('modal-container');
let closeModalButton = document.getElementById('modal-close-button');

openModalButton.onclick = function() {
  modal.style.display = "block";
}

closeModalButton.onclick = function() {
  modal.style.display = "none";
}

// STAR

const stars = document.querySelectorAll(".g-star");
let lastClickedStar = null;

for (let i = 0; i < stars.length; i++) {
    stars[i].addEventListener("mouseover", () => {
        stars.forEach(star => star.setAttribute("fill", ""));
        for (let j = 0; j <= i; j++) {
            stars[j].setAttribute("fill", "rgb(255, 193, 7)");
        }
    });

    stars[i].addEventListener("mouseout", () => {
        if (lastClickedStar === null) {
            stars.forEach(star => star.setAttribute("fill", ""));
        } else {
            stars.forEach((star, index) => {
                if (index <= lastClickedStar) {
                    star.setAttribute("fill", "rgb(255, 193, 7)");
                } else {
                    star.setAttribute("fill", "");
                }
            });
        }
    });
}

stars.forEach(function(star) {
    star.addEventListener('click', function() {
        // Update the hidden input field with the clicked star's value
        document.getElementById('starValue').value = this.getAttribute('data-value');
        // Remove the star-hover class from all stars
        stars.forEach(star => star.setAttribute("fill", ""));
        // Add the star-hover class to the clicked star and all stars before it
        for (let i = 0; i < stars.length; i++) {
            if (i <= this.getAttribute('data-value') - 1) {
                stars[i].setAttribute("fill", "rgb(255, 193, 7)");
            }
        }
        // Update the last clicked star
        lastClickedStar = this.getAttribute('data-value') - 1;
    });
});