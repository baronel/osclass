<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
Plugin Name: Photo home gallery
Plugin URI: https://github.com/panaionutwteh/osclass/tree/master/bs_home_gallery
Description: This plugin help you to add the image for home gallery
Version: 1.0.0
Author: WTEH
Author URI: http://www.wteh.ro/
Plugin update URI: http://osclass.wteh.ro/plugins/bs_home_gallery/update.php
*/
require_once 'DAOHomeGallery.php';

/**
 *
 */
define("UPLOAD_DIR", osc_content_path() . 'uploads/home_gallery/');
/**
 *
 */
define("UPLOAD_PATH", osc_base_url() . 'oc-content/uploads/home_gallery/');
$uploadImageName = null;

/**
 *
 */
function photoGalleryCallAfterInstall()
{
    $path = osc_plugin_resource('bs_home_gallery/struct.sql');
    $sql = file_get_contents($path);
    DAOHomeGallery::newInstance()->importSql($sql);
}

/**
 *
 */
function photoGalleryCallAfterUninstall()
{
    DAOHomeGallery::newInstance()->dropHomeGalleryTable();
}

/**
 * @return int
 */
function photoGalleryActions()
{
    global $uploadImageName;

    $dao_preference = new Preference();
    $addnew = Params::getParam('newadd');
    $bs_key_id = Params::getParam('delete');

    if (Params::getParam('file') != 'bs_home_gallery/admin.php') {
        return 0;
    }

    if ($addnew == 'true' && uploadImageHomeGallery()) {
        $title = Params::getParam('s_title');
        $description = Params::getParam('s_description');
        $img = $uploadImageName;

        $add = DAOHomeGallery::newInstance()->addHomeImageGallery(array(
            's_title' => $title,
            's_description' => $description,
            's_image' => $img,
        ));

        if($add){
            osc_add_flash_ok_message(__('The home gallery data has been added', 'bs_home_gallery'), 'admin');
        } else {
            osc_add_flash_error_message(_e('An error occurred delete the home gallery data', 'bs_home_gallery'), 'admin');
        }
        osc_redirect_to(osc_admin_render_plugin_url('bs_home_gallery/admin.php'));
    } elseif ($bs_key_id != '') {
        $deleteImage = DAOHomeGallery::newInstance()->deleteByGalleryId($bs_key_id);
        if($deleteImage) {
            osc_add_flash_ok_message(__('The home gallery data has been deleted', 'bs_home_gallery'), 'admin');
        } else {
            osc_add_flash_error_message(__('An error occurred delete the home gallery data', 'bs_home_gallery'), 'admin');
        }
        osc_redirect_to(osc_admin_render_plugin_url('bs_home_gallery/admin.php'));
    }
}


/**
 * @return int
 */
function uploadImageHomeGallery()
{
    global $uploadImageName;

    if (!empty($_FILES['imageName'])) {
        $myFile = $_FILES['imageName'];

        // if directory exist
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR);
            // set proper permissions on the new dir
            chmod(UPLOAD_DIR, 0777);
        }

        if ($myFile["error"] !== UPLOAD_ERR_OK) {
            osc_add_flash_error_message(__('An error occurred saving the image', 'bs_home_gallery'), 'admin');
            osc_redirect_to(osc_admin_render_plugin_url('bs_home_gallery/admin.php'));
        }

        // ensure a safe filename
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

        $parts = pathinfo($name);

        $name = md5($parts["filename"]) . '.' . $parts["extension"];
        // don't overwrite an existing file
        $i = 0;
        $parts = pathinfo($name);
        while (file_exists(UPLOAD_DIR . $name)) {
            $i++;
            $name = md5($parts["filename"]) . "-" . $i . "." . $parts["extension"];
        }

        // preserve file from temporary directory
        $success = move_uploaded_file($myFile["tmp_name"],
            UPLOAD_DIR . $name);
        if (!$success) {
            osc_add_flash_error_message(__('An error occurred saving the image', 'bs_home_gallery'), 'admin');
            osc_redirect_to(osc_admin_render_plugin_url('bs_home_gallery/admin.php'));
        }
        // set proper permissions on the new file
        chmod(UPLOAD_DIR . $name, 0755);

        $uploadImageName = $name;

        return 1;
    }
}

/**
 * @return array
 */
function get_all_photo_gallery($order = 'asc')
{
    $imageGallery = DAOHomeGallery::newInstance()->getAllImageGallery($order);
    if (count($imageGallery) > 0) {
        return $imageGallery;
    }
    return array();
}

function get_photo_gallery_image_url($image_name){
    return UPLOAD_PATH . $image_name;
}

/**
 *
 */
function photoGalleryAdmin()
{
    osc_admin_render_plugin('bs_home_gallery/admin.php');
}

/**
 *
 */
function photoGalleryAdminMenu()
{
    osc_admin_menu_plugins('Home image gallery', osc_admin_render_plugin_url('bs_home_gallery/admin.php'), 'bs_home_gallery_submenu');
}

/**
 *
 */
function photoGalleryLoadScripts()
{
    osc_enqueue_style('homeGallery', osc_base_url() . 'oc-content/plugins/bs_home_gallery/assets/css/custom_home_gallery.css');
}

// This is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), 'photoGalleryCallAfterInstall');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'photoGalleryCallAfterUninstall');
osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'photoGalleryAdmin');
osc_add_hook('admin_menu_init', 'photoGalleryAdminMenu');
// register css
osc_add_hook('init_admin', 'photoGalleryLoadScripts');
osc_add_hook('init_admin', 'photoGalleryActions');
?>