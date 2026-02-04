<?php

// Helper functions

/**
 * Dump and Die
 */
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Return current date and time
 */
function now()
{
    return date('Y-m-d H:i:s');
}

/**
 * Return json encoded data
 */
function packJson(array $data)
{
    if (is_array($data)) {
        return json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }
    return '{}';
}

/**
 * Return json encoded data
 */
function unpackJson(string $data)
{
    return json_decode($data);
}

/**
 * Return the length of the given data
 */
function getLength($data)
{
    if (is_array($data)) {
        return count($data);
    } else if (is_object($data)) {
        return count(get_object_vars($data));
    } else {
        return strlen((string) $data);
    }
}

/**
 * Validate given email
 */
function filterEmail(string $email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function dump($var)
{
    if (is_bool($var)) {
        $var = 'bool(' . ($var ? 'true' : 'false') . ')';
    }

    if (php_sapi_name() === 'cli') {
        print_r($var);
    } else {
        highlight_string("<?php\n" . var_export($var, true));
    }
}

function vite_asset($path) {
    // If development mode
    if (str_starts_with($_ENV['APP_ENV'], 'dev')) {
        // Check if Vite dev server is running inside Docker container on port 5173
        $devServer = @fsockopen('localhost', 5173);
        if (!$devServer) {
            throw new Exception('Dev server is not running. Please run `npm run dev`.');
        }
        // Dev mode - point to Vite dev server exposed on Windows host
        return "http://localhost:5173/" . $path;
    } else {
        // Prod mode - point to the built file in the public folder
        $manifestFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'build/.vite/manifest.json';

        if (!file_exists($manifestFile)) {
            throw new Exception('The Vite manifest file does not exist. Please run `npm run build`.');
        }

        $manifest = json_decode(file_get_contents($manifestFile), true);

        if (isset($manifest[$path])) {
            return "/build/" . ltrim($manifest[$path]['file'], '/');
        } else {
            throw new Exception('The Vite manifest file does not contain the path: ' . $path);
        }
    }
}

// Determine the suffix for the given year number
function formatStudentYear($year) {
    $suffix = match ($year % 10) {
        1 => ($year % 100 === 11) ? 'th' : 'st',
        2 => ($year % 100 === 12) ? 'th' : 'nd',
        3 => ($year % 100 === 13) ? 'th' : 'rd',
        default => 'th',
    };

    return $year . $suffix;
}
