<?php

class view{

	public static function render($view, $data = []) {
        $viewFile = PATH_VIEW . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file '$view.php' not found.");
        }

        ob_start();
        extract($data);
        include $viewFile;
        $content = ob_get_clean();

        return $content;
    }
}

?>