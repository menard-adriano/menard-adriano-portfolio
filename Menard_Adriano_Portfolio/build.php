<?php
/**
 * Builds the static site for GitHub Pages.
 * Runs index.php and saves the HTML to dist/, then copies css/ and assets/.
 *
 *   php build.php
 */
error_reporting(E_ALL);

$dist = __DIR__ . '/dist';
if (!is_dir($dist)) {
    mkdir($dist, 0777, true);
}

ob_start();
require __DIR__ . '/index.php';
file_put_contents($dist . '/index.html', ob_get_clean());

function copy_dir(string $from, string $to): void
{
    if (!is_dir($from)) {
        return;
    }
    if (!is_dir($to)) {
        mkdir($to, 0777, true);
    }
    foreach (scandir($from) as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        is_dir("$from/$item") ? copy_dir("$from/$item", "$to/$item") : copy("$from/$item", "$to/$item");
    }
}

copy_dir(__DIR__ . '/css', $dist . '/css');
copy_dir(__DIR__ . '/assets', $dist . '/assets');

echo "Built dist/index.html\n";
