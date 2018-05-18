(function() {
  function init() {
    const popup = document.querySelector("#popup");
    const popupCloseHandler = document.querySelector("#close-handler");

    function bind(selector, event, method) {
      const domEl = document.querySelector(selector);
      if(!domEl) return;
      domEl.addEventListener(event, method);
    }
    function prevent(event) {
      if(!event || typeof event.preventDefault != "function") return;
      event.preventDefault();
    }

    function closePopup(event) {
      prevent(event);
      popup.classList.remove("show");
    }

    bind("#close-handler", "click", closePopup);
    bind(".overlay","click", closePopup);
  }

  init();
})();
