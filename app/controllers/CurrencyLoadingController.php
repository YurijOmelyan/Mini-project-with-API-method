<?php

namespace controllers;

/**
 * This class provides a link between logic and the output of information for loading data into a database.
 */
class CurrencyLoadingController
{

    public function run()
    {
        $model = new models\CurrencyLoadingModel();
        echo $model->run();
    }
}