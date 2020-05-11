<?php

namespace controllers\models;

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

        return $this->getForm($_POST);
    }

    /**
     * This method determines which form should be returned depending on the request.
     * @param $post
     * @return array query result
     */
    private function getForm($post): array
    {
        $verifiedData = $this->dataMatchPattern($post);
        if (!$verifiedData['valid']) {
            $result['form'] = FORM_LOGIN;
            return $verifiedData;
        }

        $_SESSION['user'] = [$verifiedData['data']];
        $verifiedData['form'] = FORM_TABLE;
        return $verifiedData;
    }

    /**
     * This method checks the data received in the request.
     * @param $data data from the request
     * @return array result of checking
     */
    private function dataMatchPattern($data): array
    {
        $result['valid'] = 1;

        if (!is_array($data) or count($data) !== 3) {
            $result['valid'] = 0;
            $result['data'] = 'Error. GET method parameters do not match the pattern.';
            $result['codeError'] = 1;
            return $result;
        }

        if (!isset($data['email']) or !isset($data['pass'])) {
            $result['valid'] = 0;
            $result['data'] = 'Error. The passed parameter names do not match the pattern.';
            $result['codeError'] = 2;
            return $result;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $result['valid'] = 0;
            $result['data'] = 'Error. The specified mail does not match the pattern';
            $result['codeError'] = 3;
            return $result;
        }

        $pass = trim($data['pass']);
        if (empty($pass) or (mb_strlen($pass) < 6 or mb_strlen($pass) > 16)) {
            $result['valid'] = 0;
            $result['data'] = 'Error. The specified password does not match the pattern.';
            $result['codeError'] = 4;
            return $result;
        }


        $result['data'] = [
            'email' => $data['email'],
            'pass' => $data['pass']
        ];

        return $result;
    }

}