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

class CustomCommand implements CommandInterface
{

    public function execute(array $args)
    {

        // Write custom code

    }

    public function getDescription()
    {

        return 'Description of this command';

    }


}
```

