<?php

namespace Daniloromka\EAV;

class TestDataGenerator
{
    public static function start($batchSize = 1000)
    {
        $pdo = new PDO();

        $categoriesCount = 2000;
        $insertedRecords = 0;
        $sql = '';

        for ($i = 1; $i <= $categoriesCount; $i++) {
            if (!$sql) {
                $sql = "INSERT INTO eavjsonb_category (id, name) VALUES ";
            }

            $name = 'Category ' . $i;
            $sql .= "($i,'$name'),\n";

            $insertedRecords++;

            if ($insertedRecords % $batchSize == 0 || $insertedRecords == $categoriesCount) {
                $pdo->exec(trim($sql, ",\n"));
                $sql = '';
            }
        }

        $attributesCount = 30000;
        $insertedRecords = 0;
        $sql = '';

        $attributes = [];
        for ($i = 1; $i <= $attributesCount; $i++) {
            if (!$sql) {
                $sql = "INSERT INTO eavjsonb_attribute (id, name) VALUES ";
            }

            $name = 'Attribute ' . $i;
            $sql .= "($i,'$name'),\n";

            $isDiapason = $i > 1 && rand(1, 10) == 1;
            $attributes[$i] = $isDiapason;

            $insertedRecords++;

            if ($insertedRecords % $batchSize == 0 || $insertedRecords == $attributesCount) {
                $pdo->exec(trim($sql, ",\n"));
                $sql = '';
            }
        }

        $insertedRecords = 0;
        $sql = '';
        $categoryAttributes = [];
        $isDiapason = false;

        $i = 1;
        for ($categoryId = 1; $categoryId <= $categoriesCount; $categoryId++) {
            $usedAttributes = [1];
            $categoryAttributesCount = rand(1, 100);
            for ($n = 1; $n <= $categoryAttributesCount; $n++) {
                if (!$sql) {
                    $sql = "INSERT INTO eavjsonb_category_attribute (id, attribute_id, category_id, is_diapason) VALUES ";
                }

                do {
                    $attributeId = rand(1, $attributesCount);
                } while (in_array($attributeId, $usedAttributes));
                $usedAttributes[] = $attributeId;

                if ($i > 1) {
                    $isDiapason = $attributes[$attributeId];
                    $categoryAttributes[$categoryId][$attributeId] = $isDiapason;
                } else {
                    $attributeId = 1;
                }

                $sql .= "($i,$attributeId,$categoryId," . ($isDiapason ? 'true' : 'false') . "),\n";

                $insertedRecords++;

                if ($insertedRecords % $batchSize == 0 || ($n == $categoryAttributesCount && $categoryId == $categoriesCount)) {
                    $pdo->exec(trim($sql, ",\n"));
                    $sql = '';
                }

                $i++;
            }
        }

        $insertedRecords = 0;
        $sql = '';

        $i = 0;
        $values = [];
        for ($attributeId = 1; $attributeId <= $attributesCount; $attributeId++) {
            $valuesCount = pow(rand(1, 2), rand(0, 10));
            for ($n = 1; $n <= $valuesCount; $n++) {
                $isDiapason = $attributes[$attributeId];
                if ($isDiapason) {
                    continue;
                }

                if (!$sql) {
                    $sql = "INSERT INTO eavjsonb_value (id, attribute_id, name) VALUES ";
                }

                $i++;
                $name = 'Value ' . $i;
                $sql .= "($i,$attributeId,'$name'),\n";
                $values[$attributeId][] = $name;

                $insertedRecords++;

                if ($insertedRecords % $batchSize == 0 || ($n == $valuesCount && $attributeId == $attributesCount)) {
                    $pdo->exec(trim($sql, ",\n"));
                    $sql = '';
                }
            }
        }

        $entitiesCount = 10000000;
        $insertedRecords = 0;
        $sql = '';

        for ($i = 1; $i <= $entitiesCount; $i++) {
            if (!$sql) {
                $sql = "INSERT INTO eavjsonb_entity (id, name, category_id, attribute_values) VALUES ";
            }

            $name = 'Entity ' . $i;
            $categoryId = rand(1, $categoriesCount);
            if ($i == 1) {
                $categoryId = 1;
                $attributeValues = ["1" => $values[1][0]];
            }
            foreach ($categoryAttributes[$categoryId] as $attributeId => $isDiapason) {
                if ($isDiapason) {
                    $attributeValues["$attributeId"] = rand(1, 10000000);
                } else {
                    $attributeValues["$attributeId"] = $values[$attributeId][rand(0, count($values[$attributeId]) - 1)];
                }
            }
            $sql .= "($i,'$name',$categoryId,'" . json_encode($attributeValues) . "'),\n";

            $attributeValues = [];

            $insertedRecords++;

            if ($insertedRecords % $batchSize == 0 || $insertedRecords == $entitiesCount) {
                $pdo->exec(trim($sql, ",\n"));
                $sql = '';
            }
        }
    }
}
