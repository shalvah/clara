<?php

use Shalvah\Clara\Clara;

function clara(string $name, string $mode = self::MODE_ICONS, array $colours = []): Clara
{
    return Clara::app($name, $mode, $colours);
}