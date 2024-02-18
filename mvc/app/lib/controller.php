<?php

require_once 'app/lib/view.php';
// require_once 'app/models/settingsModel.php';

class Controller {

    private $view;
    private $settingsModel;

    public function __construct(View $view, SettingsModel $settingsModel) {
        session_start();
        $this->view = $view;
        $this->settingsModel = $settingsModel;
    }

    public function getView() {
        return $this->view;
    }

    public function getSystemName() {
        $settings = $this->settingsModel->retrieveSettings();
        $systemName = isset($settings[0]->desc) ? $settings[0]->desc : '';
        return $systemName;
    }

    public function getSystemAddress() {
        $settings = $this->settingsModel->retrieveSettings();
        $systemAddress = isset($settings[3]->desc) ? $settings[3]->desc : '';
        return $systemAddress;
    }

    public function isSessionEmpty() {
        return empty($_SESSION['user_id']);
    }
}

?>