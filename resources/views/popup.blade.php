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
@endif