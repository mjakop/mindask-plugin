<?php
/*
Plugin Name: MindAsk
Plugin URI:  http://www.mindask.com
Description: MindAsk helps you to better understand your customers.
Version:     1.0
Author:      MindAsk
Author URI:  http://www.mindask.com
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function mindask_api_call($action, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://app.mindask.com/api/plugins/$action/");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    $result = curl_exec($ch);
    curl_close($ch);  
    return json_decode($result);
}

 
function mindask_install() {
    $response = mindask_api_call('install', array('url'=>get_site_url()));
    update_option('mindask_api_code', $response->public_code);
    update_option('mindask_enabled', True); 
}
register_activation_hook( __FILE__, 'mindask_install' );

function mindask_uninstall() {
    $response = mindask_api_call('uninstall', array('url'=>get_site_url()));
    delete_option('mindask_api_code');
    delete_option('mindask_enabled');
    delete_option('mindask_selected_survey');
}
register_deactivation_hook( __FILE__, 'mindask_uninstall' );


function mindask_index(){
    if (isset($_GET['action'])){
        if ($_POST['enabled'] == '1'){
            update_option('mindask_enabled', True);
        } else {
            update_option('mindask_enabled', False);
        }
        if ($_POST['survey'] != 'none'){ 
            update_option('mindask_selected_survey', $_POST['survey']);
        } else {
            delete_option('mindask_selected_survey');
        }
    }
    $api_code = get_option('mindask_api_code');
    $response = mindask_api_call('list_surveys', array('code'=>$api_code));
    if ($response->status != 'ok'){
        die();
    }
    $surveys = $response->surveys;
    include_once('templates/mindask_index.php');
}

function mindask_add_admin_menu() {
    add_menu_page(__('MindAsk','mindask'), __('MindAsk','mindask'), 'manage_options', 'mindask-index', 'mindask_index' );
}
add_action('admin_menu', 'mindask_add_admin_menu');

function mindask_scripts() {
    if (get_option('mindask_enabled') == True){
        $selected_survey = get_option('mindask_selected_survey');
        if (isset($selected_survey)) {
            wp_enqueue_script(
                'mindask',
                "http://app.mindask.com/api/load/$selected_survey/",
                array(),
                1,
                true
            );
        }
    }
}

add_action('wp_enqueue_scripts', 'mindask_scripts');

?>