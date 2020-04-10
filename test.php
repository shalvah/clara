<?php

require 'vendor/autoload.php';

use Shalvah\Clara\Clara;

Clara::app('app1')->info("Installing package");
Clara::app('app1')->debug("Attempt 3 of 5");
Clara::app('app1')->warn("The file does not exist.");
Clara::app('app1')->error("Something went wrong!");
Clara::app('app1')->success("Done. Go and be awesome.");

Clara::mute();
$output->info("Muted: Installing package");
$output->debug("Muted: Attempt 3 of 5");
$output->warn("Muted: The file does not exist.");
$output->error("Muted: Something went wrong!");
$output->success("Muted: Done. Go and be awesome.");

$output->unmute();
$output->info("Unmuted: Installing package");
$output->debug("Unmuted: Attempt 3 of 5");
$output->warn("Unmuted: The file does not exist.");
$output->error("Unmuted: Something went wrong!");
$output->success("Unmuted: Done. Go and be awesome.");