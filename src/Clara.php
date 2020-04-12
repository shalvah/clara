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
     * @var array
     */
    private static $capturedOutput = [];

    /**
     * @var array
     */
    private static $globalMuteExceptions = [];

    /**
     * @var string[]
     */
    private static $appsBeingCaptured = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var OutputInterface
     */
    protected $outputInterface;
    
    /**
     * @var bool
     */
    protected $showDebugOutput = true;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->outputInterface = new ConsoleOutput;
    }
    
    public function useOutput(OutputInterface $outputInterface)
    {
        $this->outputInterface = $outputInterface;
        return $this;
    }

    public function showDebugOutput($show = true)
    {
        $this->showDebugOutput = $show;
        return $this;
    }

    public function hideDebugOutput()
    {
        $this->showDebugOutput = false;
        return $this;
    }

    public static function app(string $name, $showDebugOutput = false)
    {
        $output = new static($name);
        return $showDebugOutput ? $output->showDebugOutput() : $output->hideDebugOutput();
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
        return $this->showDebugOutput && $this->line("<fg=blue>🐛 debug</> {$text}");
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

}