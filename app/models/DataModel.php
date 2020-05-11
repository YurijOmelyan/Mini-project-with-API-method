<?php

namespace controllers\models;

use controllers\models\services\Validator;

/**
 * Class DataModel selects data from the database upon request from the website
 */
class DataModel
{
    /**
     * @var DatabaseConnection|object Link to the object database connection
     */
    private object $db;

    /**
     * DataModel constructor.
     * @param object $db
     */
    public function __construct()
    {
        $this->db = new services\DatabaseConnection();
    }

    /**
     * main method
     * @return array query result
     */
    public function run(): array
    {
        if (!isset($_SESSION['user'])) {
            return ['form' => 'http://localhost'];
        }

        $verifiedData = Validator::validationDataTableForm($_GET);
        if (!$verifiedData['valid']) {
            return $verifiedData;
        }
        return $this->getData($verifiedData);
    }

    /**
     * In this method, an sql query is generated and a database query is executed
     * @param $date data from the request
     * @return array query result
     */
    private function getData($date): array
    {
        $sql = 'SELECT valuteID as ID, numCode as NumCode, charCode as CharCode,';
        $sql .= 'name as Name, value as Value FROM currency ';
        $sql .= "WHERE date = '{$date['date']['data']}' ORDER BY CharCode";

        $result = $this->db->getData($sql);
        if (count($result) === 0) {
            return ['data' => 'No data for the indicated date'];
        }
        return $result;
    }
}