<?php

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
        require_once DATA_BASE_CONNECTION;
        $this->db = new DatabaseConnection();
    }

    /**
     * main method
     * @return array query result
     */
    public function run(): array
    {
        $verifiedData = $this->dataMatchPattern($_GET);
        if (!$verifiedData['valid']) {
            return $verifiedData['data'];
        }
        return $this->getData($_GET['date']);
    }

    /**
     * This method checks the data received in the request.
     * @param $data data from the request
     * @return array result of checking
     */
    private function dataMatchPattern($data): array
    {
        $result['valid'] = 1;

        if (!is_array($data) or count($data) !== 2) {
            $result['valid'] = 0;
            $result['data'] = 'Error. GET method parameters do not match the pattern.';
            return $result;
        }

        if (!isset($data['date'])) {
            $result['valid'] = 0;
            $result['data'] = 'Error. The passed parameter names do not match the pattern.';
            return $result;
        }

        if (!date_create_from_format('Y-m-d', $data['date'])) {
            $result['valid'] = 0;
            $result['data'] = 'Error. The specified date does not match the pattern.';
            return $result;
        }
        return $result;
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
        $sql .= "WHERE date = '{$date}' ORDER BY CharCode";

        $result = $this->db->getData($sql);
        if (count($result) === 0) {
            return ['data' => 'No data for the indicated date'];
        }
        return $result;
    }
}