<?php

use Shalvah\Clara\Clara;

function clara(string $name, $showDebugOutput = false)
{
    return Clara::app($name, $showDebugOutput);
}