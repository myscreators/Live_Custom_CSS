<?php

namespace Live_Custom_CSS\Controllers;

use App\Controllers\Security_Controller;

class Live_Custom_CSS_settings extends Security_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    public function index()
    {
        $view_data = [];
        $view_data['custom_css'] = get_setting('live_custom_css_code') ?: '';
        return $this->template->rander("Live_Custom_CSS\Views\settings\index", $view_data);
    }

    public function save()
    {
        try {
            $css = $this->request->getPost('custom_css') ?: '';
            
            $this->Settings_model->save_setting("live_custom_css_code", $css);

            header('Content-Type: application/json');
            echo json_encode(array(
                "success" => true,
                "message" => app_lang("settings_updated")
            ));
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "success" => false,
                "message" => "Error al guardar: " . $e->getMessage()
            ));
            exit;
        }
    }
}