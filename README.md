# clara 🔊
[![image](http://img.shields.io/packagist/v/shalvah/clara.svg?style=flat)](https://packagist.org/packages/shalvah/clara) [![Total Downloads](https://poser.pugx.org/shalvah/clara/downloads)](https://packagist.org/packages/shalvah/clara) [![Build Status](https://travis-ci.com/shalvah/clara.svg?branch=master)](https://travis-ci.com/shalvah/clara)


Simple, pretty, testable console output for PHP CLI apps.

<p align="center">

<img alt="Output on macOS" src="./screenshot-mac.png">

<img alt="Output on Windows Terminal" src="./screenshot-windows-teminal.png" >

</p>

## Installation
(PHP 7.2+)

```bash
composer require shalvah/clara
```

## Using Clara in a CLI app
```php
$output = clara('myappname');

$output->info("Installing package");
$output->debug("Attempt 3 of 5");
$output->warn("The file does not exist.");
$output->error("Something went wrong!");
$output->success("Done. Go and be awesome.");
```

The output will be coloured and presented as in the screenshot shown above.

If you'd like to output a line of text without the extra formatting provided by the functions above, you can use the `$output->line()` method instead.

### Toggling debug output
It's conventional to include a verbose flag (`-v`) in your CLI app that lets you show additional (debug) output to the user. You could then check for the value of the flag in an if-statement before outputting any debug logs. Clara makes this easier by letting you choose whether debug logs are on or not:

```php
$isVerbose = $this->getFlag('v');

// If $isVerbose is true,// 
// Clara won't print or capture any debug logs
$app1 = clara('app1', $isVerbose); 
$app1->debug("App 1 - Output 1");

// You can also toggle debug output manually at any time
$app1->showDebugOutput();
$app1->debug("App 1 - Output 2");

$app1->hideDebugOutput();
$app1->debug("App 1 - Output 3");
```

Note that by default (if you do not pass a second parameter to `clara()` or call the toggle methods), Clara will show all output.

## Testing a CLI app
Clara provides some utilities to help you test your CLI apps' output, or turn it off when you don't need it.

### Muting output
Sometimes when running your app's tests, you don't want to clutter your console with the output messages. You can turn off Clara's output by using the `mute()` and `unmute` static methods. To mute or unmute a specific app, pass in the app name.

```php
$output1 = clara('myapp1');
$output2 = clara('myapp2');

Clara::mute('myapp1'); // Mute only output from "myapp1"
// Won't be printed.
$output1->info("Installing package");

// Will be printed
$output2->info("Installing package");

Clara::mute(); // Mute all apps
Clara::unmute("myapp1"); // Unmute myapp1
Clara::unmute(); // Unmute all apps
```
### Capturing the output
Sometimes you need to assert that your app printed what you expect. An easy way is to use output capturing.

```php
Clara::startCapturingOutput('myapp1'); // Clara will start capturing output from myapp1
$output1 = clara('myapp1');
$output1->warn("Going to fail");
$output1->error("Failed");

$capturedOutput = Clara::getCapturedOutput('myapp1');
// $capturedOutput = [
//   "<fg=yellow>🚸 warning</> Going to fail",
//   "<fg=red>🚫 error</> Failed",
// ]

Clara::stopCapturingOutput('myapp1');
Clara::clearCapturedOutput('myapp1'); // Will empty saved output
``` 

You can reset the entire state of Clara to default by calling `Clara::reset()`. This will clear captured output, stop capturing for all apps and unmute all apps.
 
## Note on emoji support
Some environments (example: Windows console) don't have proper support for Unicode, so emojis may not display properly.

![Output on Windows Cmder](./screenshot-cmder.png)
