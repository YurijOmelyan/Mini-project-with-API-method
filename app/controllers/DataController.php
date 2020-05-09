<?php

/**
 * This class provides a link between the logic and the output of information for fetching data from the database.
 */
class DataController
{
    public function run()
    {
        require_once PATH_MODEL . 'DataModel.php';
        $model = new DataModel();
        echo json_encode($model->run());
    }
}