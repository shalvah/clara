<?php

require 'vendor/autoload.php';

use Shalvah\Reporter\Reporter;


Reporter::mute();
Reporter::info("Installing package");
Reporter::debug("Attempt 3 of 5");
Reporter::warn("The file does not exist.");
Reporter::error("Something went wrong!");
Reporter::success("Done. Go and be awesome.");
Reporter::info("Installing package");
Reporter::debug("Attempt 3 of 5");
Reporter::warn("The file does not exist.");
Reporter::error("Something went wrong!");
Reporter::success("Done. Go and be awesome.");

Reporter::unmute();
Reporter::info("Unmuted: Installing package");
Reporter::debug("Unmuted: Attempt 3 of 5");
Reporter::warn("Unmuted: The file does not exist.");
Reporter::error("Unmuted: Something went wrong!");
Reporter::success("Unmuted: Done. Go and be awesome.");