//Event listener to expand/contract side menu bottons
document.querySelectorAll(".patientSideMenuBtn").forEach(function (element) {
  element.addEventListener("click", function () {
    this.classList.toggle("expandedWidth");
  });
});

let patientMenuButtons = document.querySelectorAll(".patientSideMenuBtn");
let patientMenuBoxes = document.querySelectorAll(".patientMenuBox");
//Event listeners to expand/contract side menu content boxes
patientMenuButtons.forEach(function (element) {
  element.addEventListener("click", function () {
    let divID = "#" + this.dataset.buttonName + "Box"; //Unique ID
    let contentBox = document.querySelector(divID);
    contentBox.classList.add("hideContent");
    contentBox.classList.remove("show", "expandedWidth", "expandedHeight");
    if (element.classList.contains("expandedWidth")) {
      contentBox.classList.remove("hideContent");
      contentBox.classList.add("show", "expandedWidth", "expandedHeight");
    }
  });
});
