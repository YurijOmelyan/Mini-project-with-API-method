<?php

/**
 * This class provides a link between the logic and the output of information to select the desired form.
 */
class FormController
{
    private $data;

    public function run()
    {
        require_once PATH_MODEL . 'FormModel.php';
        $form = new FormModel();
        $this->data = $form->run();
        echo require $this->data['form'];
    }
}