/** Get the URL **/
const currentURL = window.location.pathname;
const windowWith = window.innerWidth;


/** Home page **/
if (currentURL === "/") {
    // Manage the arrow
    const tricksArrow = document.querySelector(".tricks__arrow");
    let tricksArrowDisplayed = false;
    const btnTricks = document.querySelector(".btn__tricks");

    if (btnTricks) {
        btnTricks.addEventListener("click", function () {
            const tricksItem = document.querySelectorAll(".tricks__item");

            if (tricksItem.length >= 15 && window.scrollY > 200) {
                tricksArrow.classList.add("show");
                tricksArrowDisplayed = true;
            }
        });
    }

    if (windowWith < 992) {
        tricksArrow.classList.add("show");
        tricksArrowDisplayed = true;
    }

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

    if (btnTricks) {
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
}


/** Trick page **/
if (currentURL.includes("/trick") && !currentURL.includes("/edit")) {
    // Modal
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

    // Delete medias
    const deleteMediasIcons = document.querySelectorAll(".media__delete");
    deleteMediasIcons.forEach(function (deleteMediaIcon) {
        deleteMediaIcon.addEventListener("click", function (event) {
            event.preventDefault();
            const deleteMediaURL = deleteMediaIcon.dataset.url;

            const deleteMediaChoice = confirm("Souhaitez-vous supprimer ce média ?");
            if (deleteMediaChoice === true) {
                window.location.replace(window.location.origin + deleteMediaURL);
            }
        });
    });

    // Show the medias
    const btnMedias = document.querySelector(".btn--show__media");
    const btnHideMedias = document.querySelector(".btn--hide__media");
    const listMedias = document.querySelector(".trick__medias");
    const mediaItem = document.querySelectorAll(".trick__media__item");
    if (windowWith < 1350 && mediaItem.length > 0) {
        btnMedias.classList.remove("hide");
    }

    btnMedias.addEventListener("click", function () {
        btnMedias.classList.add("hide");
        btnHideMedias.classList.remove("hide");
        listMedias.classList.add("show--flex");
    });

    btnHideMedias.addEventListener("click", function () {
        btnHideMedias.classList.add("hide");
        btnMedias.classList.remove("hide");
        listMedias.classList.remove("show--flex");
    });

    // Manage the button
    const trickComment = document.querySelectorAll(".trick__comment");
    const commentNumber = trickComment.length;
    const commentButton = document.querySelector(".btn__comments");
    let commentsLoaded = 10;

    if (commentButton) {
        if (commentNumber < 11) {
            commentButton.classList.add("hide");
        }

        commentButton.addEventListener("click", function () {
            for (let commentIndex = commentsLoaded; commentIndex < commentsLoaded + 10 && commentIndex < commentNumber; commentIndex++) {
                trickComment[commentIndex].classList.add("show--flex");
            }

            commentsLoaded += 10;

            if (commentsLoaded >= commentNumber) {
                commentButton.classList.add("hide");
            }
        });
    }
}


/** Home / Trick page **/
if (currentURL.includes("/") || currentURL.includes("/trick") && !currentURL.includes("/edit")) {
    const deleteTrickIcons = document.querySelectorAll(".trick__delete");
    deleteTrickIcons.forEach(function (deleteTrickIcon) {
        deleteTrickIcon.addEventListener("click", function (event) {
            event.preventDefault();
            const deleteTrickURL = deleteTrickIcon.dataset.url;

            const deleteTrickChoice = confirm("Souhaitez-vous supprimer ce trick ?");
            if (deleteTrickChoice === true) {
                window.location.replace(window.location.origin + deleteTrickURL);
            }
        });
    });
}


/** Edit page **/
if (currentURL.includes("/edit")) {
    const deleteFeaturedIcon = document.querySelector(".featured__delete");
    deleteFeaturedIcon.addEventListener("click", function (event) {
        event.preventDefault();
        const deleteFeaturedURL = deleteFeaturedIcon.dataset.url;

        const deleteFeaturedChoice = confirm("Souhaitez-vous supprimer cette image mise à la une ?");
        if (deleteFeaturedChoice === true) {
            window.location.replace(window.location.origin + deleteFeaturedURL);
        }
    });
}