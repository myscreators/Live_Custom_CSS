<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!class_exists('Live_Custom_CSS\Controllers\Live_Custom_CSS_settings')) {
    $psr4 = \Config\Services::autoloader();
    $psr4->addNamespace('Live_Custom_CSS', PLUGINPATH . 'Live_Custom_CSS');
}