<?php
/**
 * Nutze diese Funktion um einfach eine Ausgabe
 * mit htmlspecialchars() zu erstellen.
 *
 * @param  string $value
 *
 * @return string
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

/**
 * Nutze diese Funktion um auf einen POST-Wert
 * zuzugreifen.
 *
 * @param  string $value
 *
 * @return mixed
 */
function post(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}

/**
 * Nutze diese Funktion um auf einen POST-Wert
 * zuzugreifen und die überflüssigen Leerzeichen zu entfernen.
 *
 * @param  string $value
 *
 * @return mixed
 */
function postAndTrim(string $key, $default = '')
{
    return trim(post($key, $default));
}