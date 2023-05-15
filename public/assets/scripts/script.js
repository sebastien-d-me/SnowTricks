/** Get the URL **/
const currentURL = window.location.pathname;


/** Home page **/
if (currentURL === "/") {
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
}