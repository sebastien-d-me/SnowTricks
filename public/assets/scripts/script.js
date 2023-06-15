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
            tricks[tricksIndex].classList.add("show--flex");
        }

        tricksLoaded += 5;

        if (tricksLoaded >= tricksNumber) {
            tricksButton.classList.add("hide");
        }
    });
}

const url = window.location.toString().split("/");
if (url[3] === "trick") {
    const trickMediasModal = document.querySelectorAll(".trick__media__modal");
    const modal = document.querySelector(".trick__modal");
    const modalClose = document.querySelector(".trick__modal__close");
    const modalImage = document.querySelector(".trick__modal__image");
    const modalVideo = document.querySelector(".trick__modal__video");

    trickMediasModal.forEach(function (media) {
        media.addEventListener("click", function () {
            modalImage.classList.add("hide");
            modalVideo.classList.add("hide");
            if (media.tagName === "IMG") {
                modalImage.src = media.src;
                modalImage.classList.remove("hide");
            } else if (media.tagName === "VIDEO") {
                modalVideo.src = media.querySelector("source").src;
                modalVideo.classList.remove("hide");
            }
            modal.classList.remove("hide");
        });
    });

    modalClose.addEventListener("click", function () {
        modal.classList.add("hide");
        modalVideo.pause();
        modalVideo.currentTime = 0;
    });
}