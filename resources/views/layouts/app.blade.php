<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('shared.head')
<body>
    <div id="app">
        @include('shared.navbar')
        @yield('content')
    </div>
</body>
</html>
