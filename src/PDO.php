<?php

namespace Daniloromka\EAV;

class PDO extends \PDO
{
    public function __construct()
    {
        $dsn = getenv('PDO_PGSQL_DSN');
        parent::__construct($dsn);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
