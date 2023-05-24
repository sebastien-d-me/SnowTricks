/** Get the URL **/
const currentURL = window.location.pathname;


/** Home page **/
if (currentURL === "/") {
    // Manage the arrow
    const tricksArrow = document.querySelector(".tricks__arrow");
    let tricksArrowDisplayed = false;
    const btnTricks = document.querySelector(".btn__tricks");

    btnTricks.addEventListener("click", function () {
        const tricksItem = document.querySelectorAll(".tricks__item");

        if (tricksItem.length >= 15 && window.scrollY > 200) {
            tricksArrow.classList.add("show");
            tricksArrowDisplayed = true;
        }
    });

    window.addEventListener("scroll", function () {
        if (tricksArrowDisplayed === true && window.scrollY < 200) {
            tricksArrow.classList.remove("show");
        } else if (tricksArrowDisplayed === true && window.scrollY > 200) {
            tricksArrow.classList.add("show");
        }
    });

    // Manage the button
    const tricks = document.querySelectorAll(".tricks__item")
    const tricksNumber = tricks.length;
    const tricksButton = document.querySelector(".btn__tricks");
    let tricksLoaded = 15;

    tricksButton.addEventListener("click", function () {
        for (let tricksIndex = tricksLoaded; tricksIndex < tricksLoaded + 5 && tricksIndex < tricksNumber; tricksIndex++) {
            tricks[tricksIndex].classList.add("show");
        }

        tricksLoaded += 5;

        if (tricksLoaded >= tricksNumber) {
            tricksButton.classList.add("hide");
        }
    });
}