<?php

namespace controllers\models;


use DOMDocument;

/**
 * Class CurrencyLoadingModel implements data loading into the database
 */
class CurrencyLoadingModel
{

    /**
     * @var DatabaseConnection|object Link to the object database connection
     */
    private object $db;

    /**
     * CurrencyLoadingModel constructor.
     */
    public function __construct()
    {
        $this->db = new services\DatabaseConnection();
    }

    /**
     * main method
     * @return string data loading result
     * @throws Exception Data not saved to database
     */
    public function run(): string
    {
        return $this->saveDynamicsCurrenciesDatabase($this->getListCurrency());
    }

    /**
     * This method populates the database in the last 30 days.
     * @param $listCurrency list of currmodelsencies
     * @return string результат выполнения
     * @throws Exception Data not saved to database
     */
    private function saveDynamicsCurrenciesDatabase($listCurrency): string
    {
        $dateStart = date('d/m/Y', strtotime('-30 days'));
        $dateEnd = date('d/m/Y');
        $url = "http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1={$dateStart}&date_req2={$dateEnd}&VAL_NM_RQ=";
        foreach ($listCurrency as $index) {
            $dom = new DOMDocument();
            if ($dom->load($url . $index['id'])) {
                foreach ($dom->getElementsByTagName('Record') as $el) {
                    $date = date_format(date_create_from_format('d.m.Y', $el->attributes[0]->nodeValue), 'Y-m-d');
                    $value = str_replace(',', '.', $el->childNodes[1]->nodeValue);

                    $sql = 'INSERT INTO currency SET ';
                    $sql .= "valuteID = '{$index['id']}', ";
                    $sql .= "numCode = '{$index['numCode']}', ";
                    $sql .= "charCode = '{$index['charCode']}', ";
                    $sql .= "name = '{$index['name']}', ";
                    $sql .= "value = {$value}, ";
                    $sql .= "date = '{$date}';";


                    if (!$this->db->setData($sql)) {
                        throw new Exception('Data not saved to database');
                    }
                }
            }
        }
        return 'Database loading completed successfully';
    }

    /**
     * This method creates a list of currencies.
     * @return array list of currencies
     */
    private function getListCurrency(): array
    {
        $dom = new DOMDocument();
        $data = date('d/m/Y');
        $url = 'http://www.cbr.ru/scripts/XML_daily_eng.asp?date_req=' . $data;
        $arr = [];
        if ($dom->load($url)) {
            foreach ($dom->getElementsByTagName('Valute') as $el) {
                $arr[] = [
                    'id' => $el->attributes[0]->nodeValue,
                    'numCode' => $el->childNodes[0]->nodeValue,
                    'charCode' => $el->childNodes[1]->nodeValue,
                    'name' => $el->childNodes[3]->nodeValue
                ];
            }
        }
        return $arr;
    }
}