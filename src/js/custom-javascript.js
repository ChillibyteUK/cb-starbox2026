// Add your custom JS here.
// AOS.init({
//   easing: "ease-out",
//   once: true,
//   duration: 600,
// });

// Add background to navbar on scroll
(function () {
  var navbar = document.getElementById("wrapperNavbar");

  var addNavbarBackground = function () {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  };

  window.addEventListener("scroll", addNavbarBackground);
})();
