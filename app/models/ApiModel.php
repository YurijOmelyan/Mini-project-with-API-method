<?php

namespace controllers\models;

use controllers\models\services\Validator;

/**
 * ApiModel class implements API method
 */
class ApiModel
{
    /**
     * @var DatabaseConnection|object Link to the object database connection
     */
    private object $db;

    /**
     * ApiModel constructor.
     */
    public function __construct()
    {
        $this->db = new services\DatabaseConnection();
    }

    /**s
     * main method
     * @return array query result
     */
    public function run(): array
    {
        $verifiedData = Validator::validationDataApiMethod($_GET);
        if (!$verifiedData['valid']) {
            return $verifiedData;
        }

        return $this->getQueryResult($verifiedData);
    }

    /**
     * In this method, an sql query is generated and a database query is executed
     * @param $data data from the request
     * @return array query result
     */
    private function getQueryResult($data): array
    {
        $sql = 'SELECT valuteID, numCode, charCode, name, value FROM currency ';
        $sql .= "WHERE valuteID = '{$data['valuteID']['data']}'";
        $sql .= "AND date BETWEEN '{$data['date_req1']['data']}' AND '{$data['date_req2']['data']}'";

        return $this->db->getData($sql);
    }
}
