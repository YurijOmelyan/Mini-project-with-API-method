<?php

/**
 * This class provides the link between logic and information output for the API method.
 */
class ApiController
{
    public function run()
    {
        require_once PATH_MODEL . 'ApiModel.php';
        $model = new ApiModel();
        echo json_encode($model->run());
    }
}