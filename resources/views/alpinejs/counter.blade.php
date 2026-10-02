<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Counter</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<?php
    $title = "Harry Potter";
    $content = "Once up on a time...."
?>

<body class="bg-slate-900 text-white">
    <div x-data="{ count: 0 }" class="bg-slate-800 p-5 m-5">
        <h2 x-text="count" class="bg-slate-700 p-2 rounded-md"></h2>

        <button x-on:click="count++" class="bg-blue-500 px-2 mt-2 rounded-md cursor-pointer hover:bg-blue-400">+</button>
    </div>

    <div class="bg-slate-800 p-5 m-5">
        <h1><span class="font-semibold">Title:</span> {{ $title }}</h1>

        <div x-data="{ expanded: false }">
            <button type="button" x-on:click="expanded = ! expanded" class="bg-blue-500 px-2 mt-2 rounded-md cursor-pointer hover:bg-blue-400">
                <span x-show="! expanded">Show post content...</span>
                <span x-show="expanded">Hide post content...</span>
            </button>

            <div x-show="expanded">
                <span class="font-semibold">Content:</span>
                {{ $content }}
            </div>
        </div>
    </div>
</body>
</html>