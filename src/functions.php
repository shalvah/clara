<?php

namespace Shalvah\Reporter;

use Symfony\Component\Console\Output\ConsoleOutput;

// See https://symfony.com/doc/current/console/coloring.html

function success($output)
{
    return output("👍 success <info>$output </info>");
}

function info($output)
{
    return output("<info>🔊 info</info> {$output}");
}

function debug($output)
{
    return output("<fg=blue>🐛 debug</> {$output}");
}

function warn($output)
{
    return output("<bg=yellow>⚠ warning</> {$output}");
}

function error($output)
{
    return output("<fg=red>🚫 error</> {$output}");
}

/**
 * Output the given text to the console.
 */
function output(string $output = "")
{
    if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] == 'testing') {
        return $output;
    }
    return (new ConsoleOutput)->writeln($output);
}