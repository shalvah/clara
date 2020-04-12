<?php

use Shalvah\Clara\Clara;

function clara(string $name, $showDebugOutput = true)
{
    return Clara::app($name, $showDebugOutput);
}