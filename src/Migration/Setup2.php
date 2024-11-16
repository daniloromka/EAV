<?php

namespace Daniloromka\EAV\Migration;

use Daniloromka\EAV\PDO;

class Setup2
{
    public function __construct()
    {
        $pdo = new PDO();

        $sql = 'CREATE INDEX idxattributevalues ON eavjsonb_entity USING GIN (attribute_values jsonb_path_ops);';

        $pdo->exec($sql);
    }
}
