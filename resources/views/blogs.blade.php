<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<style>
    .cardList {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 50px;
    }

    img {
        height: 300px;
        width: auto;
    }
</style>
<div class="cardList">
    <img src="" alt="">
    @foreach($data as $item)
        <div class="card">
            {!! $item->content !!}
        </div>
</div>

@endforeach
</body>
</html>

