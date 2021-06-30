<?php

namespace Shalvah\Clara;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * See https://symfony.com/doc/current/console/coloring.html
 */
class Clara
{
    /** @var string[] */
    private static array $mutedAppsList = [];

    private static bool $isMutedGlobally = false;

    /** @var string[] */
    private static array $capturedOutput = [];

    /** @var string[] */
    private static array $globalMuteExceptions = [];

    /** @var string[] */
    private static array $appsBeingCaptured = [];

    protected string $name;

    protected OutputInterface $outputInterface;

    protected bool $showDebugOutput = true;

    /** Can be either "icons" or "labels" */
    protected string $mode;

    /** @var string[] */
    protected array $colours;

    protected static array $defaultColours = [
        'info' => 'white',
        'success' => 'green',
        'warn' => 'yellow',
        'error' => 'red',
        'debug' => 'magenta',
    ];

    protected static array $icons = [
        'info' => 'ⓘ',
        'success' => '✔',
        'warn' => '⚠',
        'error' => '✖',
        'debug' => '⚒',
    ];

    public function __construct(string $name, string $mode = 'labels', array $colours = [])
    {
        $this->name = $name;
        $this->mode = $mode;
        $this->colours = empty($colours) ? static::$defaultColours : $colours;
        $this->outputInterface = new ConsoleOutput;
    }
    
    public function useOutput(OutputInterface $outputInterface): self
    {
        $this->outputInterface = $outputInterface;
        return $this;
    }

    public function showDebugOutput(bool $show = true): self
    {
        $this->showDebugOutput = $show;
        return $this;
    }

    public function hideDebugOutput(): self
    {
        $this->showDebugOutput = false;
        return $this;
    }

    public static function app(string $name): self
    {
        return new static($name);
    }

    public function success($text)
    {
        return $this->line(
            sprintf("%s success <info>$text </info>", $this->icon('success'))
        );
    }

    public function info($text)
    {
        return $this->line(
            sprintf("<info>%s info</info> {$text}", $this->icon('info'))
        );
    }

    public function debug($text)
    {
        return $this->showDebugOutput
            ? $this->line(
                sprintf("<fg=blue>%s debug</> $text", $this->icon("debug"))
            )
            : '';
    }

    public function warn($text)
    {
        return $this->line(
            sprintf("<fg=yellow>%s warning</> $text", $this->icon("warn"))
        );
    }

    public function error($text)
    {
        return $this->line(
            sprintf("<fg=red>%s error</> $text", $this->icon("error"))
        );
    }

    /**
     * Output the given text to the console.
     */
    public function line($text = "")
    {
        if (static::isCapturing($this->name)) {
            static::capture($this->name, $text);
        }

        if (static::isMuted($this->name)) {
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
            static::$globalMuteExceptions = [];
        } else {
            // Add specified apps to mute list
            static::$mutedAppsList[$app] = true;
            unset(static::$globalMuteExceptions[$app]);
        }
    }

    public static function unmute(string $app = null)
    {
        if (empty($app)) {
            // Unmute all apps
            static::$isMutedGlobally = false;
            static::$mutedAppsList = [];
            static::$globalMuteExceptions = [];
        } else {
            unset(static::$mutedAppsList[$app]);
            static::$globalMuteExceptions[$app] = true;
        }
    }

    /**
     * Mute output from all apps but this one
     */
    public function only(): Clara
    {
        static::mute();
        static::unmute($this->name);
        return $this;
    }

    protected static function isMuted(string $app)
    {

        if (static::$isMutedGlobally && !isset(static::$globalMuteExceptions[$app])) {
            return true;
        }

        return !empty(static::$mutedAppsList[$app]);
    }

    public static function startCapturingOutput(string $app)
    {
        // Using a hash key rather than list entry to take care of duplicate calls
        static::$appsBeingCaptured[$app] = true;
        static::$capturedOutput[$app] = static::$capturedOutput[$app] ?? [];
    }

    public static function stopCapturingOutput(string $app)
    {
        if (static::isCapturing($app)) {
            unset(static::$appsBeingCaptured[$app]);
        }
    }

    public static function clearCapturedOutput(string $app)
    {
        static::$capturedOutput[$app] = [];
    }

    public static function getCapturedOutput(string $app)
    {
        return static::$capturedOutput[$app] ?? [];
    }

    protected static function isCapturing(string $app)
    {
        return isset(static::$appsBeingCaptured[$app]);
    }

    protected static function capture(string $app, $text)
    {
        return static::$capturedOutput[$app][] = $text;
    }

    public static function reset()
    {
        static::$isMutedGlobally = false;
        static::$mutedAppsList = [];
        static::$globalMuteExceptions = [];
        static::$appsBeingCaptured = [];
        static::$capturedOutput = [];
    }

    protected function icon($type)
    {
        return static::$icons[$type];
    }

}