<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Appetiser - Calendar</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
        <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}" />
    </head>
    <body>
        <div id="app">
            <br>
            <calendar-component></calendar-component>
        </div>

        <!-- <script src="{{ asset('js/app.js') }}"/> -->
        <script src="{{ asset(mix('js/app.js')) }}" defer></script>
    </body>
</html>
