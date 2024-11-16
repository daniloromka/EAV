<?php

namespace Daniloromka\EAV;

class EAVHelper
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO();
    }

    public function getTotalRecordsCount()
    {
        return $this->pdo->query('SELECT COUNT(*) FROM eavjsonb_entity')->fetchColumn();
    }

    public function getFilteredRecords(array $filter)
    {
        $filter = json_encode($filter);
        return $this->pdo->query("SELECT * FROM eavjsonb_entity WHERE attribute_values @> '$filter'")->fetchAll(PDO::FETCH_ASSOC);
    }
}
