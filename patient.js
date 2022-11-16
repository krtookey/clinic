//Event listener to expand/contract side menu buttons
document.querySelectorAll(".patientSideMenuBtn").forEach(function (element) {
    element.addEventListener("click", function () {
        this.classList.toggle("expandedWidth");
    });
});

let patientMenuButtons = document.querySelectorAll(".patientSideMenuBtn");
let patientMenuBoxes = document.querySelectorAll(".patientMenuBox");

//Event listeners to expand/contract side menu content boxes
patientMenuButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        let divID = "#" + this.dataset.buttonName + "Box"; //Unique ID
        let contentBox = document.querySelector(divID);
        //Contracting content
        if (!button.classList.contains("expandedWidth")) {
            contentBox.classList.remove("expandedWidth");
            setTimeout(() => {
                contentBox.classList.add("hideContent");
                contentBox.classList.remove("show", "expandedHeight");
            }, 100);

            //Expanding content
        } else if (button.classList.contains("expandedWidth")) {
            contentBox.classList.remove("hideContent");
            contentBox.classList.add("show");
            setTimeout(() => {
                contentBox.classList.add("expandedWidth", "expandedHeight");
            }, 1);
        }
    });
});

// Old way of expanding and contracting side menu items. Didn't transition, kept here for easy access incase we want to revert
// patientMenuButtons.forEach(function (element) {
//     element.addEventListener("click", function () {
//         let divID = "#" + this.dataset.buttonName + "Box"; //Unique ID
//         let contentBox = document.querySelector(divID);
//         contentBox.classList.add("hideContent");
//         contentBox.classList.remove("show", "expandedWidth", "expandedHeight");
//         if (element.classList.contains("expandedWidth")) {
//             contentBox.classList.remove("hideContent");
//             contentBox.classList.add("show", "expandedWidth", "expandedHeight");
//         }
//     });
// });
