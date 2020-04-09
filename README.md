# reporter

![image](http://img.shields.io/packagist/v/shalvah/reporter.svg?style=flat) [![Total Downloads](https://poser.pugx.org/shalvah/reporter/downloads)](https://packagist.org/packages/shalvah/reporter)

Simple console output for PHP CLI apps.

![Output on Windows Terminal](./screenshot-windows-teminal.png)

## Installation
(PHP 7.2+)

```bash
composer require shalvah/reporter
```

## Usage
```php
<?php

use Shalvah\Reporter\Reporter;

Reporter::info("Installing package");
Reporter::debug("Attempt 3 of 5");
Reporter::warn("The file does not exist.");
Reporter::error("Something went wrong!");
Reporter::success("Done. Go and be awesome.");
```

The output will be coloured and presented as in the screenshot shown above.

If you'd like to output a line of text without the extra formatting provided by the functions above, you can use the `output()` function instead.

You can also mute and unmute the output by using `Reporter::mute()` and `Reporter::unmute()`. This can be useful if you;d like to reduce CLI noise when running your tests. The output will always be returned, so you can still assert on that.

## Note on emoji support
Some environments (example: Windows console) don't have proper support for Unicode, so emojis may not display properly.

![Output on Windows Cmder](./screenshot-cmder.png)

