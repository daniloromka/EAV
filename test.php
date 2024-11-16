<?php

use \Daniloromka\EAV\EAVHelper;

require_once __DIR__ . '/autoload.php';

$EAVHelper = new EAVHelper();

echo "Total records count: " . $EAVHelper->getTotalRecordsCount() . "\n";

$filter = ['1' => 'Value 1'];

$start = microtime(true);
echo "Filtered records count where " . json_encode($filter) . ": " . count($EAVHelper->getFilteredRecords($filter)) . "\n";
echo "Execution time: " . (round(microtime(true) - $start, 4) * 1000). " ms\n";
