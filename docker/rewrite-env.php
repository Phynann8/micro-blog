<?php

// Rewrite keys in the Laravel .env file using the container's real environment.
// This guarantees DB/app settings (e.g. DB_HOST=db) reach Laravel regardless of
// whether PHP-FPM inherits environment variables, and lets dotenv convert
// literals like "false" into real booleans.
//
// Usage: php rewrite-env.php </absolute/path/to/.env>

$envPath = $argv[1] ?? __DIR__.'/../.env';

if (! is_file($envPath)) {
    fwrite(STDERR, "rewrite-env: .env not found at {$envPath}\n");
    exit(1);
}

$lines = file($envPath);
$out   = '';

foreach ($lines as $line) {
    if (preg_match('/^([A-Za-z0-9_]+)=(.*?)[\r\n]*$/', rtrim($line), $m)) {
        $key     = $m[1];
        $envVal  = getenv($key);

        if ($envVal !== false && $envVal !== '') {
            // Quote values that contain whitespace or other characters that
            // dotenv (vlucas/phpdotenv) would otherwise reject, e.g. spaces.
            if (! preg_match('/^[A-Za-z0-9_\/.:@-]+$/', $envVal)) {
                $envVal = '"'.addcslashes($envVal, "\\\"".PHP_EOL).'"';
            }

            $line = $key.'='.$envVal.PHP_EOL;
        }
    }

    $out .= $line;
}

file_put_contents($envPath, $out);