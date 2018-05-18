@if(empty($message) == false)
    <div class="popup show" id="popup">
        <div class="message flex-c">
            {{$message}}
            <a href="#" class="close-popup" id="close-handler">
                <span class="sr-only">Close</span>
            </a>
        </div>
        <div class="overlay"></div>
    </div>
    <script>
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
    </script>
@endif