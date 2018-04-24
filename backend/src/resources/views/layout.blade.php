<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Butterfly Effect</title>

    @section('styles')
        @foreach ($theme->getCssFiles() as $cssFile)
            <link href="{{ $cssFile }}" rel="stylesheet" type="text/css">
        @endforeach
    @show

    <script type="text/javascript">
        var apiUrl = '{{ $api_url }}';
    </script>
</head>
<body>
    @yield('body')

    @section('scripts')
        @foreach ($theme->getJsFiles() as $jsFile)
            <script src="{{ $jsFile }}" type="text/javascript"></script>
        @endforeach
    @show
</body>
</html>
