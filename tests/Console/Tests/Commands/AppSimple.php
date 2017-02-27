<?php

namespace Console\Tests\Commands;

use Console\CommandInterface;
use Console\Args;

class AppSimple implements CommandInterface
{

    public function execute(Args $args)
    {

        $args->setAliases([
            'l' => 'longform'
        ]);

        echo "AppSimple:";
        print_r($args->getAll());

    }

    public function getDescription()
    {

        return 'Simple command interface';

    }

}