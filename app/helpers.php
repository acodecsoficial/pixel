<?php

if (! function_exists('imgBaseUrl')) {
    function imgBaseUrl(string $url): string {
        if (str_starts_with($url, 'http')) return $url;
        if (!str_starts_with($url, '/')) $url = "/$url";
        return "/storage$url";
    }
}

if (! function_exists('convertHexToRGB')) {
    function convertHexToRGB($hex) {
        // Remove o '#' se existir
        $hex = ltrim($hex, '#');

        // Se for um HEX de 3 caracteres, converte para 6 caracteres
        if (strlen($hex) === 3) {
            $hex = implode('', array_map(fn($char) => str_repeat($char, 2), str_split($hex)));
        }

        // Converte o HEX em RGB
        $rgb = [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
        return implode(' ', $rgb);
    }
}

if (! function_exists('getContrastColor')) {
    function getContrastColor($hex) {
        // Remove o caractere '#' se estiver presente
        $hex = ltrim($hex, '#');

        // Converte a cor HEX para RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calcula o brilho relativo (fórmula YIQ)
        $yiq = (($r*299) + ($g*587) + ($b*114)) / 1000;

        // Retorna 'black' para cores claras e 'white' para cores escuras
        return ($yiq >= 128) ? '0 0 0' : '255 255 255';
    }
}
