<?php

require_once __DIR__ . '/listConstants.php';

/**
 * Class Router redirects to the desired class, depending on the request
 */
class Router
{
    /**
     * main method
     */
    public function run()
    {
        $controllerName = 'controllers\\' . $this->getNameController();
        $object = new $controllerName();
        $object->run();
    }

    /**
     * looking for the name of the controller depending on the request
     *
     * @return string controller name
     */
    private function getNameController(): string
    {
        $roots = require_once ROOTS;
        if (!isset($_GET) or empty($_GET) or !isset($_GET['q'])) {
            return $roots['/'];
        }

        $parsedData = explode('/', $_GET['q']);
        if (isset($roots[$parsedData[0]])) {
            return $roots[$parsedData[0]];
        }
        return $roots['/'];
    }
}