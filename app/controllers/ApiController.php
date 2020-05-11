<?php

namespace controllers;

/**
 * This class provides the link between logic and information output for the API method.
 */
class ApiController
{
    public function run()
    {
        $model = new models\ApiModel();
        echo json_encode($model->run());
    }
}