<?php

namespace controllers;

/**
 * This class provides a link between thse logic and the output of information to select the desired form.
 */
class FormController
{

    public function run()
    {
        $form = new models\ FormModel();
        $data = $form->run();
        echo require $data['form'];
    }
}