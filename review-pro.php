<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.smilesavvy.com
 * @since             1.0.0
 * @package           Review_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       Review Pro
 * Plugin URI:        https://www.smilesavvy.com/services/review-pro/
 * Description:       Review Pro helps manage your online reputation through positive reviews. This plugin enables usage of Review Pro on your WordPress site.
 * Version:           1.0.0
 * Author:            Smile Savvy
 * Author URI:        https://www.smilesavvy.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       review-pro
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 */
define('REVIEW_PRO_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-review-pro-activator.php
 */
function activate_review_pro() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-review-pro-activator.php';
    Review_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-review-pro-deactivator.php
 */
function deactivate_review_pro() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-review-pro-deactivator.php';
    Review_Pro_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_review_pro');
register_deactivation_hook(__FILE__, 'deactivate_review_pro');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-review-pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_review_pro() {
    $plugin = new Review_Pro();
    $plugin->run();
}

run_review_pro();

add_action('init', 'modify_jquery');

function review_pro_register_scripts_styles() {
    wp_register_script('review-pro-external-js', 'https://reviewpro.smilesavvy.com/Modules/external_module/embed.js', array('jquery'), null, false);
    wp_register_script('review-pro-multi-external-js', 'https://reviewpro.smilesavvy.com/Modules/external_module/embed1.js', array('jquery'), null, false);
    wp_register_style('review-pro-external-css', 'https://reviewpro.smilesavvy.com/Modules/external_module/style.min.css', '', null, false);

    wp_enqueue_style('review-pro-external-css'); // loads CSS, as loading it from within the shortcode function would place it in the footer, which is bad
}

add_action('wp_enqueue_scripts', 'review_pro_register_scripts_styles');

// creates primary shortcode function for review pro
function reviewpro($atts, $content = null) {
    extract(shortcode_atts(array(
        "color" => 'light',
        "type" => 'stream',
        "id" => '999',
        "datamax" => '',
        "datasort" => '',
        "multi" => ''
                    ), $atts));

    // sets our shortcode values provided via the shortcode as a "global variable" so they can be used in other functions
    update_option('_rpid', $id);
    update_option('_datamax', $datamax);
    update_option('_datasort', $datasort);
    update_option('_multi', $multi);

    // load scripts only after shortcode is called, but also, load proper script based on multi-account option
    $rpmulti = get_option('_multi');
    if ($rpmulti != "yes") {
        wp_enqueue_script('review-pro-external-js');
    } else {
        wp_enqueue_script('review-pro-multi-external-js');
    }

    // return applicable values into markup to be injected where the shortcode is called
    if ($multi != "yes") {
        return '<div id="reviews-' . $type . '" class="reviews ' . $color . '">' . $content . '</div>';
    } else {
        // convert comma-separated ID list into an array
        $rpids = get_option('_rpid');
        $rpidsarray = explode(',', $rpids);
        ob_start();
        foreach ($rpidsarray as $uid) {
            echo '<div id="reviews-stream-' . $uid . '" class="reviews ' . $color . '">' . $content . '</div>';
        }
        return ob_get_clean();
    }
}

add_shortcode("reviewpro", "reviewpro");

// add custom attributes to previously enqueued scripts
function review_pro_modify_loaded_scripts($tag, $handle, $src) {

    // pulls the global variables from the function in which they were updated
    $rpid = get_option('_rpid');
    $rpdm = get_option('_datamax');
    $rpds = get_option('_datasort');
    $rpmulti = get_option('_multi');

    // the handles of the enqueued scripts we want to customize
    if ($rpmulti != "yes") {
        $custom_scripts = array('review-pro-external-js');
    } else {
        $custom_scripts = array('review-pro-multi-external-js');
    }

    if (in_array($handle, $custom_scripts)) {
        return '<script id="' . $rpid . '" src="' . $src . '" data-max="' . $rpdm . '" data-sort="' . $rpds . '"></script>' . "\n";
    }
    return $tag;
}

add_filter('script_loader_tag', 'review_pro_modify_loaded_scripts', 10, 3);
