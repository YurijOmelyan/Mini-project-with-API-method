<?php

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
        require_once DATA_BASE_CONNECTION;
        $this->db = new DatabaseConnection();
    }

    /**s
     * main method
     * @return array query result
     */
    public function run(): array
    {
        $verifiedData = $this->dataMatchPattern($_GET);
        if (!$verifiedData['valid']) {
            return $verifiedData['data'];
        }

        return $this->getQueryResult($verifiedData['data']);
    }

    /**
     * This method checks the data received in the request.
     * @param $data data from the request
     * @return array result of checking
     */
    private function dataMatchPattern($data): array
    {
        $result['valid'] = 1;

        if (!is_array($data) or count($data) !== 4) {
            $result['valid'] = 0;
            $result['data'] = 'Error. GET method parameters do not match the pattern.';
            return $result;
        }

        if (!isset($data['date_req1']) or !isset($data['date_req2']) or !isset($data['valuteID'])) {
            $result['valid'] = 0;
            $result['data'] = 'Error. The passed parameter names do not match the pattern.';
            return $result;
        }

        if (!date_create_from_format('Y-m-d', $data['date_req1'])
            or !date_create_from_format('Y-m-d', $data['date_req2'])) {
            $result['valid'] = 0;
            $result['data'] = 'Error. The specified date does not match the pattern.';
            return $result;
        }

        if (!preg_match('/^R\d{5}[a-zA-Z]?$/', $data['valuteID'])) {
            $result['valid'] = 0;
            $result['data'] = 'Error. Parameter value ValueID does not match the pattern.';
            return $result;
        }

        $result['data'] = [
            'dateStart' => $data['date_req1'] < $data['date_req2'] ? $data['date_req1'] : $data['date_req2'],
            'dateEnd' => $data['date_req1'] > $data['date_req2'] ? $data['date_req1'] : $data['date_req2'],
            'valuteID' => $data['valuteID']
        ];
        return $result;
    }

    /**
     * In this method, an sql query is generated and a database query is executed
     * @param $data data from the request
     * @return array query result
     */
    private function getQueryResult($data): array
    {
        $sql = 'SELECT valuteID, numCode, charCode, name, value FROM currency ';
        $sql .= "WHERE valuteID = '{$data['valuteID']}' AND date BETWEEN '{$data['dateStart']}' AND '{$data['dateEnd']}'";

        $result = $this->db->getData($sql);

        return $result;
    }
}
