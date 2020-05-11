<?php

namespace controllers\models\services;

use Exception;

/**
 * The DatabaseConnection class implements a database connection.
 */
class DatabaseConnection
{
    /**
     * @var array  array of settings for the database
     */
    private array $bdSetting;
    /**
     * @var object link to the object database connection
     */
    private object $link;

    /**
     * DatabaseConnection constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->bdSetting = require_once DB_SETTING;
        $this->link = $this->getConnection();
    }

    /**
     * @return object link to the object database connection
     * @throws Exception Database Connection Error
     */
    private function getConnection(): object
    {
        $link = mysqli_connect(
            $this->bdSetting['servername'],
            $this->bdSetting['username'],
            $this->bdSetting['password'],
            $this->bdSetting['dbname']
        );

        if (mysqli_connect_errno()) {
            throw new Exception('Database Connection Error');
        }

        return $link;
    }

    /**
     * This method queries the database and returns the result of the query.
     * @param $sqlQuery SQL query
     * @return array|null query result
     */
    public function getData($sqlQuery): ?array
    {
        return mysqli_fetch_all(mysqli_query($this->link, $sqlQuery), MYSQLI_ASSOC);
    }

    /**
     * This method pushes data into the database.
     * @param $sqlQuery SQL query
     * @return bool|null query result
     */
    public function setData($sqlQuery): ?bool
    {
        return mysqli_query($this->link, $sqlQuery);
    }
}