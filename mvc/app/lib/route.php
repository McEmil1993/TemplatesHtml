<?php

class route
{   
    public function __construct($url){
        $this->route($url);
    }

    private function route($url)
    {
        $urlParts = explode('/', rtrim($url, '/'));

        $controllerName = ucfirst(strtolower($urlParts[0])) . 'Controller';
        $methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';
        if (empty($urlParts[0])) {
            $controllerName = 'AuthenticationController';
        }
       $controllerFile = PATH_CONTROLLER . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            if (class_exists($controllerName)) {
                $controller = new $controllerName();

                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                } else {
                    $this->showError();
                }
            } else {
                $this->showError();
            }
        } else {
            $this->showError();
        }
    }

    private function showError() {
        require_once PATH_CONTROLLER . 'ErrorController.php';
        $errorController = new ErrorController();
        $errorController->index();
    }
}

?>