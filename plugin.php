<?php 
/**
 * Plugin Name: StartGo Contact
 * Description: StartGo Contact Form
 * Version: 1.0.0
 * Requires Plugins: advanced-custom-fields
 * Author: Oleksiy Boyda
 * Text Domain: sgc
 */

define('SGC_VERSION', '1.0.0');
define('SGC_ROOT', __DIR__);
define('SGC_INDEX', plugins_url('', __FILE__));

require SGC_ROOT . '/vendor/autoload.php';
require SGC_ROOT . '/src/class-load.php';
require SGC_ROOT . '/src/scripts.php';
require SGC_ROOT . '/src/setup.php';
// require SGC_ROOT . '/src/acf-options.php'; #Requires ACF Pro
require SGC_ROOT . '/src/acf-fields.php';
