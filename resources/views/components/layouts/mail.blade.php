<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? '' }}</title>
</head>
<body style="background-color: #212425; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">

    <div style="max-width: 540px; margin: 0 auto; padding: 40px 20px;">
        <div style="text-align: center;">
            <img src="{{ $configs->logomarca }}" style="width: 200px;margin: auto;">
        </div>

        <div style="background-color: #ffffff; color: #000000; border-radius: 6px; padding: 20px; margin: 30px 0 36px; font-size: 17px; line-height: 1.5em;">
            {{ $content }}
        </div>

        {{ $footer ?? '' }}
    </div>

</body>
</html>
