<?php

namespace Shalvah\Clara;

use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * See https://symfony.com/doc/current/console/coloring.html
 */
class Clara
{
    public static $CLARA_ON = true;
    
    /**
     * @var string
     */
    protected $name;
    /**
     * @var bool
     */
    private $on;
    /**
     * @var array
     */
    public $captures;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->on = true;
        $this->captures = [];
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
        if (static::$CLARA_ON && $this->on) {
            (new ConsoleOutput)->writeln($text);
        } else {
            $this->captures[] = $text;
        }
        return $text;
    }

    public function mute()
    {
        $this->on = false;
        $this->captures = [];
    }

    public function unmute()
    {
        $this->on = true;
    }

    public static function muteGlobal()
    {
        static::$CLARA_ON = false;
    }

    public static function unmuteGlobal()
    {
        static::$CLARA_ON = true;
    }

}