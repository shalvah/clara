<?php

require 'vendor/autoload.php';

use Shalvah\Clara\Clara;


Clara::mute();
Clara::info("Installing package");
Clara::debug("Attempt 3 of 5");
Clara::warn("The file does not exist.");
Clara::error("Something went wrong!");
Clara::success("Done. Go and be awesome.");
Clara::info("Installing package");
Clara::debug("Attempt 3 of 5");
Clara::warn("The file does not exist.");
Clara::error("Something went wrong!");
Clara::success("Done. Go and be awesome.");

Clara::unmute();
Clara::info("Unmuted: Installing package");
Clara::debug("Unmuted: Attempt 3 of 5");
Clara::warn("Unmuted: The file does not exist.");
Clara::error("Unmuted: Something went wrong!");
Clara::success("Unmuted: Done. Go and be awesome.");