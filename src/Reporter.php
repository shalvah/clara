<?php

namespace Shalvah\Reporter;

use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * See https://symfony.com/doc/current/console/coloring.html
 */
class Reporter
{
    public static $REPORTER_ON = false;

    public static function success($output)
    {
        return static::output("👍 success <info>$output </info>");
    }

    public static function info($output)
    {
        return static::output("<info>🔊 info</info> {$output}");
    }

    public static function debug($output)
    {
        return static::output("<fg=blue>🐛 debug</> {$output}");
    }

    public static function warn($output)
    {
        return static::output("<bg=yellow>⚠ warning</> {$output}");
    }

    public static function error($output)
    {
        return static::output("<fg=red>🚫 error</> {$output}");
    }

    /**
     * Output the given text to the console.
     */
    public static function output($output = "")
    {
        static::$REPORTER_ON && (new ConsoleOutput)->writeln($output);
        return $output;
    }

    public static function mute()
    {
        static::$REPORTER_ON = false;
    }

    public static function unmute()
    {
        static::$REPORTER_ON = true;
    }

}