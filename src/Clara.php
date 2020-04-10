<?php

namespace Shalvah\Clara;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * See https://symfony.com/doc/current/console/coloring.html
 */
class Clara
{
    /**
     * @var string[]
     */
    private static $mutedAppsList = [];

    /**
     * @var bool
     */
    private static $isMutedGlobally = false;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var OutputInterface
     */
    protected $outputInterface;

    public function __construct(string $name, OutputInterface $outputInterface = null)
    {
        $this->name = $name;
        $this->outputInterface = $outputInterface ?: new ConsoleOutput;
    }

    public static function app(string $name)
    {
        return new static($name);
    }

    public function success($text)
    {
        return $this->line("👍 success <info>$text </info>");
    }

    public function info($text)
    {
        return $this->line("<info>🔊 info</info> {$text}");
    }

    public function debug($text)
    {
        return $this->line("<fg=blue>🐛 debug</> {$text}");
    }

    public function warn($text)
    {
        return $this->line("<fg=yellow>🚸 warning</> {$text}");
    }

    public function error($text)
    {
        return $this->line("<fg=red>🚫 error</> {$text}");
    }

    /**
     * Output the given text to the console.
     */
    public function line($text = "")
    {
        if (static::$isMutedGlobally) {
            return $text;
        }

        if (array_search($this->name, static::$mutedAppsList) !== false) {
            return $text;
        }

        $this->outputInterface->writeln($text);
        return $text;
    }

    public static function mute(string $app = null)
    {
        if (empty($app)) {
            // Mute all apps
            static::$isMutedGlobally = true;
        } else {
            // Add specified apps to mute list
            static::$mutedAppsList[] = $app;
        }
    }

    public static function unmute(string $app =null)
    {
        if (empty($app)) {
            // Unmute all apps
            static::$isMutedGlobally = false;
            static::$mutedAppsList = [];
        } else {
            $appIndexes = array_keys(static::$mutedAppsList, $app);
            if (!empty($appIndexes)) {
                foreach ($appIndexes as $index) {
                    array_splice(static::$mutedAppsList, $index, 1);
                }
            }
        }
    }

    public static function reset()
    {
        static::$isMutedGlobally = false;
        static::$mutedAppsList = [];
    }

}