<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Challenge</title>
</head>
<body>
    <h1>Download</h1>
    <form action="{{url("/enqueue")}}" method="post">
        {{ csrf_field() }}
        <div class="row">
            <label>
                Url: <input type="url" name="url" /> <button>Download</button>
            </label>
        </div>
    </form>
</body>
</html>