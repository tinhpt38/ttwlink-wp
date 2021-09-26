<?php
require_once(__DIR__ . '../../includes/ttw_database.php');


add_action('init', 'ttw_decrypt_process');


function ttw_decrypt_process()
{

    if (isset($_REQUEST['ttw'])) {
        $token = $_REQUEST['ttw'];
        $db = new DatabaseHelper();
        $record = $db->get_destination($token);
        if (isset($record)) {
            $db->increment_visitedcount($record->ID, $record->visitedcount + 1);
            wp_redirect($record->destination);
            exit;
        } else {
        }
    }
}

add_action('wp_enqueue_scripts', 'ttw_enqueue_script');

function ttw_enqueue_script()
{


    wp_enqueue_script(
        'ttw-link-script',
        plugin_dir_url(__FILE__) . '/js/ttwmakelink.js',
        array('jquery'),
        true,
        true,
    );

    wp_localize_script('ttw-link-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));

    wp_register_style( 'ttwlink-css', 
    plugin_dir_url(__FILE__) . '/css/style.css', false,uniqid());
    wp_enqueue_style ( 'ttwlink-css' );

}

add_shortcode('ttwlink_make', 'ttw_shortcode');

function ttw_shortcode()
{
    ob_start();
    // include('/views/ttwlink.php');
?>
    <form method="post" name="ttwlink_make" class="ttw-make-form">

        <span class="ttw-text"><?php echo __('Enter a long link to make a TTWLink', 'ttwlink') ?></span>
        <input class="ttw-link-input" type="text" name="ttw_link_url">
        <span style="display:none" class="ttw-text trw-error"> <?php echo __('Invalid URL', 'ttwlink') ?></span>

        <button class="btn btn-primary ttwlink-button-make" type="button" name="ttw_link_submit"><?php echo __('Make TTWLink!', 'ttwlink') ?> </button>
    </form>
    <div class="lds-ripple ttw-process" style="display:none">
        <div></div>
        <div></div>
    </div>
    <div class="card ttw-result-card">
        <a style="display:none" class="btn btn-primary ttwlinkresult"></a>
    </div>
<?php
    return ob_get_clean();
}
