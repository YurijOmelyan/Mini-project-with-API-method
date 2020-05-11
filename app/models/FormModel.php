<?php

namespace controllers\models;

use controllers\models\services\Validator;

/**
 * Class FormModel returns the path to the form depending on the request received
 */
class FormModel
{
    /**
     * main method
     * @return array query result
     */
    public function run(): array
    {
        if (!isset($_POST['login']) or !isset($_POST['email']) or !isset($_POST['pass'])) {
            return ['form' => FORM_LOGIN];
        }

        return $this->getForm();
    }

    /**
     * This method determines which form should be returned depending on the request.
     * @return array query result
     */
    private function getForm(): array
    {
        $verifiedData = Validator::validationDataAuthorizationForm($_POST);
        if (!$verifiedData['valid']) {
            $verifiedData['form'] = FORM_LOGIN;
            return $verifiedData;
        }

        $_SESSION['user'] = $verifiedData['email']['data'];
        $verifiedData['form'] = FORM_TABLE;
        return $verifiedData;
    }
}