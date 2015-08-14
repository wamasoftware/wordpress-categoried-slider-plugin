<?php

ob_start();
session_start();

/*
  Plugin Name: Slider
  Plugin URI:
  Description: Admin can add images in slider.
  Author: Wamasoftware
  Version: 1.0.0
  Author URI:
*/

global $wpdb;
global $wnm_db_version;
$charset_collate = $wpdb->get_charset_collate();
//create table when activate
function jal_install()
{
    global $wpdb;
    //set version
    $wnm_db_version = "1.0";
    //set table name
    $table_name_p = $wpdb->prefix . "slider";
    //create gallery table
    $sql = "CREATE TABLE $table_name_p (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `slider_category` varchar(255) NOT NULL,
        `slider_image` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    $table_names = $wpdb->prefix . "slider_category";
    
    //create gallery_albam table
    $sql = "CREATE TABLE $table_names (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `category_name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option("wnm_db_version", $wnm_db_version);
}
//CREATE TABLE
register_activation_hook(__FILE__, 'jal_install');

function slider_plugin_menu() {
    add_menu_page('slider', 'slider', 'manage_options', 'slider', 'parse_slider_shortcode');
    add_submenu_page('slider', 'Slider Category', 'Slider Category', 'manage_options', 'slidercategory', 'parse_category_shortcode');
    add_submenu_page('slider', 'Add Slider Category', 'Add Slider Category', 'manage_options', 'categoryadd', 'slider_category_shortcode');
    add_submenu_page('slider', 'Add Slider Image', 'Add Slider Image', 'manage_options', 'slideradd', 'add_slider_shortcode');
}

add_action('admin_menu', 'slider_plugin_menu');

function parse_slider_shortcode() {

    //function for insert, update, and delete slider
    //function for insert page and select data for update 

    function slideradd() {
        global $wpdb;
        if (isset($_GET['id'])) { //for getting selected id data in form for update operation
            $query = 'select * from '.$wpdb->prefix.'slider WHERE id=' . $_GET['id'];
            $record = (array) $wpdb->get_row($query);
        }
        include ('slideradd.php');
    }

    //function for listing page
    function sliderlist() {
        global $wpdb;
        $query = 'select * from '.$wpdb->prefix.'slider;';
        $result = $wpdb->get_results($query);
        include ('sliderlist.php');
    }

    function get_slider_list() {
        global $wpdb;
        $query = 'select * from '.$wpdb->prefix.'slider;';
        $result = $wpdb->get_results($query);
        ob_start();
        require_once 'sliderlist.php';
        $output_string = ob_get_contents();
        ob_end_clean();
        return $output_string;
    }

    add_shortcode('slider_list', 'get_slider_list');
    sliderlist();

    function get_slider_list_table() {
        global $wpdb;
        $query = 'select * from '.$wpdb->prefix.'slider;';
        $result = $wpdb->get_results($query);
        return json_encode($result);
    }

    add_shortcode('sliderlistdata', 'get_slider_list_table');
}

function parse_category_shortcode() {

    //function for insert, update, and delete category
    //function for insert page and select data for update
    function categoryadd() {
        global $wpdb;
        if (isset($_GET['id'])) { //for getting selected id data in form for update operation
            $query = 'select * from '.$wpdb->prefix.'slider_category WHERE id=' . $_GET['id'];
            $record = (array) $wpdb->get_row($query);
        }
        include ('slidercategory.php');
    }
    //function for listing page
    function categorylist() {
        global $wpdb;
        $query = 'select * from '.$wpdb->prefix.'slider_category;';
        $result = $wpdb->get_results($query);
        include ('categorylist.php');
    }

    function get_category_list() {
        global $wpdb;
        $query = 'select * from '.$wpdb->prefix.'slider_category;';
        $result = $wpdb->get_results($query);
        ob_start();
        require_once 'categorylist.php';
        $output_string = ob_get_contents();
        ob_end_clean();
        return $output_string;
    }

    add_shortcode('category_list', 'get_category_list');
    categorylist();

    function get_category_list_table() {
        global $wpdb;
        $query = 'select * from '.$wpdb->prefix.'slider_category;';
        $result = $wpdb->get_results($query);
        return json_encode($result);
    }

    add_shortcode('categorylistdata', 'get_category_list_table');
}

/**
 * add new slider
 * @global type $wpdb
 */
function add_slider_shortcode() {
    //create DB object
    global $wpdb;
    if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'update' && $_POST['action'] == null) {
        $query = 'select * from '.$wpdb->prefix.'slider WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    } else if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'delete') { // For Delete
        $wpdb->query('DELETE FROM '.$wpdb->prefix.'slider WHERE id=' . $_GET['id']);
        $_SESSION['success'] = 'Slider image deleted successfully.';
        wp_redirect(admin_url('options-general.php?page=slider'));
        exit;
    } else if ($_POST['action'] == 'add_slider') {
        if ($_POST['id'] == '') {
            $fileName = '';
            if ($_FILES['sliderimages']['size'] != 0) {
                $fileName = $_FILES["sliderimages"]["name"];
                $fileTmpLoc = $_FILES["sliderimages"]["tmp_name"];
                $pathAndName = wp_upload_dir();

                //set file path
                $pathAndName = $pathAndName['basedir'];
                //create directory if not exists
                if (!file_exists($pathAndName)) {
                    mkdir($pathAndName, 0777, true);
                }
                //set file name
                //$fileName = time() . $fileName;
                $fileName = $fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName . '/' . $fileName);
            }
            //insert gallery
            $category1 = $_POST['category'];
            for($i=0;$i<sizeof($category1);$i++) {
                $wpdb->insert('wp_slider', array(slider_category => $category1[$i], slider_image => $fileName));
            }
        } else {
            $fileName = '';
            if ($_FILES['sliderimages']['size'] != 0) {
                $fileName = $_FILES["sliderimages"]["name"];
                $fileTmpLoc = $_FILES["sliderimages"]["tmp_name"];
                $pathAndName = wp_upload_dir();
                //set file path
                $pathAndName = $pathAndName['basedir'];
                //create directory if not exists
                if (!file_exists($pathAndName)) {
                    mkdir($pathAndName, 0777, true);
                }
                $query = 'select slider_image from '.$wpdb->prefix.'slider WHERE id=' . $_GET['id'];
                $image = (array) $wpdb->get_row($query);
                unlink($pathAndName . '/' . $image['slider_image']);
                //set file name
                //$fileName = time() . $fileName;
                $fileName = $fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName . '/' . $fileName);
            }
            //$wpdb->update('wp_slider', array(id => $_POST[id], slider_category => $_POST[slidercategory], slider_image => $fileName), array('id' => $_POST['id']));
            $category1 = $_POST['category'];
            for($i=0;$i<sizeof($category1);$i++) {
                $wpdb->update($wpdb->prefix.'slider', array(id => $_POST[id], slider_category => $category1[$i], slider_image => $fileName), array('id' => $_POST['id']));
            }
        }
        ob_start();
        wp_redirect(admin_url('options-general.php?page=slider'));
        exit;
    }

    if (isset($_GET['id'])) { //for getting selected id data in form for update operation
        $query = 'select * from '.$wpdb->prefix.'slider WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    }

    if ($_GET['page'] == 'slideradd') {
        // ob_start();
        $query = 'select * from '.$wpdb->prefix.'slider_category;';
        $result = $wpdb->get_results($query);
        
        require_once 'slideradd.php';
        $output_string = ob_get_contents();
    }
}

/**
 * add new slider category
 * @global type $wpdb
 */
function slider_category_shortcode() {
    //create DB object
    global $wpdb;
    if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'update' && $_POST['action'] == null) {
        $query = 'select * from '.$wpdb->prefix.'slider_category WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    } else if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'delete') { // For Delete
        $wpdb->query('DELETE FROM '.$wpdb->prefix.'slider_category WHERE id=' . $_GET['id']);
        wp_redirect(admin_url('admin.php?page=slidercategory'));
        exit;
    } else if ($_POST['action'] == 'add_category') {
        if ($_POST['id'] == '') {
            //insert gallery
            $wpdb->insert('wp_slider_category', array(category_name => $_POST[categoryname]));
        } else {
            $wpdb->update('wp_slider_category', array(id => $_POST[id], category_name => $_POST[categoryname]), array('id' => $_POST['id']));
        }
        ob_start();
        wp_redirect(admin_url('admin.php?page=slidercategory'));
        exit;
    }
    
    //for getting selected id data in form for update operation
    if (isset($_GET['id'])) { 
        $query = 'select * from '.$wpdb->prefix.'slider_category WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    }

    if ($_GET['page'] == 'categoryadd') {
        // ob_start();
        require_once 'slidercategory.php';
        $output_string = ob_get_contents();
    }
}   

//GET ALBUM SHORTCODE
//example ->[slider-images slider_category=testing1]
function image_slider($slider_category = null)
{
    global $wpdb;
    if (!empty($id['id']))
        $query = 'select * from '.$wpdb->prefix.'slider where slider_category=' . $slider_category['slider_category'];
    else
        $query = 'select * from '.$wpdb->prefix.'slider where slider_category';

    $slides = $wpdb->get_results($query);

    ob_start();
    require_once 'slideshow.php';
    $slides = ob_get_contents();

    ob_end_clean();
    return $slides;
    echo '<pre>';print_r($slides);exit;
}

add_shortcode('slider-images', 'image_slider');

?>