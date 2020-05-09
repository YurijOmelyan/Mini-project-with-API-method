<?php

/**
 * This class provides a link between logic and the output of information for loading data into a database.
 */
class CurrencyLoadingСontroller
{

    public function run()
    {
        require_once PATH_MODEL . 'CurrencyLoadingModel.php';
        $model = new CurrencyLoadingModel();
        echo $model->run();
    }
}