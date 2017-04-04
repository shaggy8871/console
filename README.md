# Console

Console is a lightweight console routing framework for PHP. It's easy to get started, requires almost zero configuration, and can run within existing projects without a major rewrite.

Installation:

In composer.json:
```
"require": {
    "shaggy8871/console": "dev-master"
}
```

Then run:
```
composer install
```

Example console.php file:

```php
<?php
include_once "vendor/autoload.php";

$console = new Console\Runner();

// Add one or more commands here
$console->registerAll([
    'app:command' => new Commands\CustomCommand()
]);

// Start your engines...
try {
    $console->run();
} catch(Console\Exception\CommandNotFoundException $e) {
    // Handle errors
}

```

Example command file:

```php
<?php

namespace Commands;

use Console\CommandInterface;
use Console\Args;

class CustomCommand implements CommandInterface
{

    public function execute(Args $args)
    {

        // Convert short-form to long-form arguments
        $args->setAliases([
            'l' => 'longform'
        ]);

        // Write custom code
        // ... Do something with $args->longform or $args->getAll()

    }

    public function getDescription()
    {

        return 'Description of this command';

    }

}
```

### Color coding

To color-code output, add the following to any output:

```php
<?php
use Console\Color;

echo Color::apply('Hello, world!', 'red bold bg_white');
```

| Colors       |
| ------------ |
| black        |
| red          |
| green        |
| yellow       |
| blue         |
| purple       |
| cyan         |
| light_gray   |
| dark_gray    |
| light_red    |
| light_green  |
| light_yellow |
| light_blue   |
| light_purple |
| light_cyan   |
| white        |

| Modifiers    |
| ------------ |
| bold         |
| underline    |

| Backgrounds  |
| ------------ |
| bg_black     |
| bg_red       |
| bg_green     |
| bg_yellow    |
| bg_blue      |
| bg_purple    |
| bg_cyan      |
| bg_white     |
