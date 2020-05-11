<?php


namespace controllers\models\services;

/**
 * Class Validator performs data validation
 * @package controllers\models\services
 */
class Validator
{
    /**
     * This method checks the data received from the table form
     * @param $data data to verify
     * @return array result of checking
     */
    public static function validationDataTableForm($data): array
    {
        $key = 'date';
        $result['valid'] = 1;
        $result['array'] = self::isValidArray($data, 2);
        $result[$key] = isset($data[$key]) ? self::isValidDate($data, $key) : self::variableNotExist();

        if (!$result['array']['valid']
            or !$result['date']['valid']) {
            $result['valid'] = 0;
            $result['methodErrorCode'] = 3;
        }
        return $result;
    }

    /**
     * This method checks the data from the authorization form.
     * @param $data data to verify
     * @return array result of checking
     */
    public static function validationDataAuthorizationForm($data): array
    {
        $arr = ['email' => 'isValidMail', 'pass' => 'isValidPass'];

        $result['valid'] = 1;
        $result['array'] = self::isValidArray($data, 3);

        foreach ($arr as $key => $value) {
            $result[$key] = isset($data[$key]) ? self::$value($data, $key) : self::variableNotExist();
        }

        if (!$result['array']['valid']
            or !$result['email']['valid']
            or !$result['pass']['valid']) {
            $result['valid'] = 0;
            $result['methodErrorCode'] = 2;
        }

        return $result;
    }

    /**
     * This method validates data from the method API
     * @param $data data to verify
     * @return array result of checking
     */
    public static function validationDataApiMethod($data): array
    {
        $arr = [
            'date_req1' => 'isValidDate',
            'date_req2' => 'isValidDate',
            'valuteID' => 'isValidValuteId'
        ];
        $result['valid'] = 1;

        $result['array'] = self::isValidArray($data, 4);

        foreach ($arr as $key => $value) {
            $result[$key] = isset($data[$key]) ? self::$value($data, $key) : self::variableNotExist();
        }

        if (!$result['array']['valid']
            or !$result['date_req1']['valid']
            or !$result['date_req2']['valid']
            or !$result['valuteID']['valid']) {
            $result['valid'] = 0;
            $result['methodErrorCode'] = 1;
        }

        $dateStart = $result['date_req1']['data'];
        $dateEnd = $result['date_req2']['data'];

        $result['date_req1']['data'] = $dateStart < $dateEnd ? $dateStart : $dateEnd;
        $result['date_req2']['data'] = $dateStart > $dateEnd ? $dateStart : $dateEnd;

        return $result;
    }

    /**
     * This method checks the incoming array according to the pattern.
     * @param $data array to check
     * @param $arrSize array size according to pattern
     * @return array result of checking
     */
    private static function isValidArray($data, $arrSize): array
    {
        $result['valid'] = 1;
        if (!is_array($data) or count($data) !== $arrSize) {
            $result['valid'] = 0;
            $result['data'] = 'Error. Parameters do not match the pattern.';
            $result['errorCode'] = 1;
        }
        return $result;
    }

    /**
     * This method returns error text for non existing variables.
     * @return array error code and error text
     */
    private static function variableNotExist(): array
    {
        $result['valid'] = 0;
        $result['data'] = 'Error. The passed parameter names do not match the pattern.';
        $result['errorCode'] = 2;
        return $result;
    }

    /**
     * This method checks the date according to the pattern.
     * @param $data data array
     * @param $key date key in data array
     * @return array result of checking
     */
    private static function isValidDate($data, $key): array
    {
        $result['valid'] = 0;
        if (!date_create_from_format('Y-m-d', $data[$key])) {
            $result['data'] = 'Error. The specified date does not match the pattern.';
            $result['errorCode'] = 3;
            return $result;
        }

        $result['valid'] = 1;
        $result['data'] = $data[$key];

        return $result;
    }

    /**
     * This method checks the currency identifier according to the template.
     * @param $data data array
     * @param $key currency identifier key in the data array
     * @return array result of checking
     */
    private static function isValidValuteId($data, $key): array
    {
        $result['valid'] = 0;

        if (!preg_match('/^R\d{5}[a-zA-Z]?$/', $data[$key])) {
            $result['data'] = 'Error. Parameter value ValueID does not match the pattern.';
            $result['errorCode'] = 4;
            return $result;
        }

        $result['valid'] = 1;
        $result['data'] = $data[$key];

        return $result;
    }

    /**
     * This method checks the email according to the pattern.
     * @param $data data array
     * @param $key email key in data array
     * @return array result of checking
     */
    private static function isValidMail($data, $key): array
    {
        $result['valid'] = 0;
        if (!filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
            $result['data'] = 'Error. The specified mail does not match the pattern';
            $result['errorCode'] = 5;
            return $result;
        }

        $result['valid'] = 1;
        $result['data'] = $data[$key];

        return $result;
    }

    /**
     * This method checks the password according to the pattern.
     * @param $data data array
     * @param $key password key in the data array
     * @return array result of checking
     */
    private static function isValidPass($data, $key): array
    {
        $result['valid'] = 0;
        $pass = trim($data['pass']);
        if (empty($data[$key])
            or (mb_strlen($data[$key]) !== mb_strlen($pass))
            or (mb_strlen($pass) < 6 or mb_strlen($pass) > 16)) {
            $result['data'] = 'Error. The specified password does not match the pattern.';
            $result['errorCode'] = 6;
            return $result;
        }

        $result['valid'] = 1;
        $result['data'] = $data[$key];

        return $result;
    }
}
