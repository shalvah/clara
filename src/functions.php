<?php

namespace Shalvah\Reporter;

use Symfony\Component\Console\Output\ConsoleOutput;

// See https://symfony.com/doc/current/console/coloring.html

function success($output)
{
    output("👍 success <info>$output </info>");
}

function info($output)
{
    output("<info>🔊 info</info> {$output}");
}

function debug($output)
{
    output("<fg=blue>🐛 debug</> {$output}");
}

function warn($output)
{
    output("<bg=yellow>⚠ warning</> {$output}");
}

function error($output)
{
    output("<fg=red>🚫 error</> {$output}");
}

/**
 * Output the given text to the console.
 */
function output(string $output = ""): void
{
    if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] == 'testing') {
        return;
    }
    (new ConsoleOutput)->writeln($output);
}