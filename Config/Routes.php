<?php
/**
 * Rutas del complemento Live Custom CSS.
 */

use CodeIgniter\Router\RoutesCollection;

/** @var RoutesCollection $routes */
$routes->group("live_custom_css_settings", ["namespace" => "Live_Custom_CSS\Controllers"], function ($subroutes) {
    $subroutes->get('/', 'Live_Custom_CSS_settings::index');
    $subroutes->post('save', 'Live_Custom_CSS_settings::save');
});