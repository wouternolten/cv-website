<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('shared.head')

<body>
    <div id="app">
        @include('shared.navbar')
        @include('shared.messages')
        @yield('content')
    </div>
</body>

</html>
