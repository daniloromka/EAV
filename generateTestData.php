<?php

use Daniloromka\EAV\TestDataGenerator;

require_once __DIR__ . '/autoload.php';

echo "Estimated execution time: 15 min\n";
TestDataGenerator::start();
echo "Successfully completed.\n";
