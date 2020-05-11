<?php

namespace controllers;

/**
 * This class provides a link between the logic and the output of information for fetching data from the database.
 */
class DataController
{
    public function run()
    {
        $model = new models\DataModel();
        echo json_encode($model->run());
    }
}