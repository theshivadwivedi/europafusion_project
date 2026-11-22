<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | EUROPA FUSION</title>
    <style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f7f5f0;color:#0e1b33;font:16px/1.6 system-ui,sans-serif;text-align:center;padding:2rem}h1{font:600 2.2rem Georgia,serif;margin:.5rem 0}a{color:#2b59d1;font-weight:700}.c{font:italic 500 5rem Georgia,serif;color:#15294d}</style>
</head>
<body><main><div class="c">@yield('code')</div><h1>@yield('message')</h1><p>Please try again in a moment, or call us on {{ config('site.phone') }}.</p><p><a href="{{ url('/') }}">Return to the home page</a></p></main></body>
</html>
