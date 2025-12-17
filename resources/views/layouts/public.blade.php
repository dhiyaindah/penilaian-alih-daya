<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penilaian Kinerja</title>

    @include('includes.style')
    @stack('css')
</head>
<body>

    

    <main class="container py-4">
        @yield('content')
    </main>

    @include('includes.script')
    @stack('js')

</body>
</html>
