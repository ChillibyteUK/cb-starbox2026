// Add your custom JS here.
// AOS.init({
//   easing: "ease-out",
//   once: true,
//   duration: 600,
// });

// Add background to navbar on scroll
(function () {
  var navbar = document.getElementById("wrapperNavbar");

  if (!navbar) {
    return;
  }

  var addNavbarBackground = function () {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  };

  window.addEventListener("scroll", addNavbarBackground);
})();

(function () {
  var doc = document.documentElement;
  var w = window;
  var header = document.getElementById("wrapperNavbar");
  var offcanvasMenu = document.getElementById("rightOffcanvas");

  if (!header) {
    return;
  }

  var prevScroll = w.scrollY || doc.scrollTop;
  var curScroll;
  var direction = 0;
  var prevDirection = 0;

  var toggleHeader = function (nextDirection, nextScroll) {
    if (nextDirection === 2 && nextScroll > 125) {
      if (!offcanvasMenu || !offcanvasMenu.classList.contains("show")) {
        header.classList.add("hide");
        prevDirection = nextDirection;
      }
    } else if (nextDirection === 1) {
      header.classList.remove("hide");
      prevDirection = nextDirection;
    }
  };

  var checkScroll = function () {
    curScroll = w.scrollY || doc.scrollTop;

    if (curScroll > prevScroll) {
      direction = 2;
    } else if (curScroll < prevScroll) {
      direction = 1;
    }

    if (direction !== prevDirection) {
      toggleHeader(direction, curScroll);
    }

    prevScroll = curScroll;
  };

  window.addEventListener("scroll", checkScroll);
})();
