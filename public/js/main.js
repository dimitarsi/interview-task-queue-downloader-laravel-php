(function() {
  function init() {
    const popup = document.querySelector("#popup");

    function prevent(event) {
      if(!event || typeof event.preventDefault != "function") return;
      event.preventDefault();
    }

    function closePopup(event) {
      prevent(event);
      popup.classList.remove("show");
    }

    document.querySelector("#close-handler").addEventListener("click", closePopup);
    document.querySelector(".overlay").addEventListener("click", closePopup);
  }

  init();
})();
