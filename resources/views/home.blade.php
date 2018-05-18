<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Challenge</title>
    <link rel="stylesheet" href="{{asset("css/main.css")}}">
</head>
<body>
    <h1>Download</h1>

    <form action="{{url("/enqueue")}}" method="post">
        {{ csrf_field() }}
        <div class="row">
            <label class="link">
                Url: <input type="url" name="url" id="url" />
                as <input type="text" name="download_name" />
                <button>Download</button>
            </label>
        </div>
    </form>

    @include("errors")

    <h1>Current Jobs</h1>
    @section("content")
        <div id="current-jobs">
        @each("resources.item", $resources, "item", "resources.empty")
        </div>

        @include("popup")
    @show

    <script src="{{asset("/js/main.js")}}"></script>
</body>
</html>