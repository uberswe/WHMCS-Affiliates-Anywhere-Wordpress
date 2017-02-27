<?php

/*
Plugin Name: Anveto Affiliate Anywhere WHMCS
Plugin URI: http://anveto.com/members/
Description: This plugin stores UDIDs from apps and allows users to send push notifications to users.
Version: 1.2
Author: Anveto, Markus Tenghamn
Author URI: http://anveto.com
License: GPL2
*/

add_action('admin_menu', 'anveto_affiliate_anywhere_whmcs_menu');



function anveto_affiliate_anywhere_whmcs_menu()
{
    add_menu_page('Anveto Affiliate Anywhere for WHMCS', 'Affiliate Anywhere', 'administrator', __FILE__, 'anveto_affiliate_anywhere_whmcs_settingsPage', plugins_url('/images/icon.png', __FILE__));

    add_action('admin_init', 'anveto_affiliate_anywhere_whmcs_registerSettings');
}

function anveto_affiliate_anywhere_whmcs_registerSettings()
{
    register_setting('anveto-settingsGroup', 'anveto-affiliate-anywhere-whmcs-url');
//    register_setting('anveto-settingsGroup', 'anveto-shortenInternalLinks');
}

function anveto_affiliate_anywhere_whmcs_settingsPage()
{
    ?>
    <div class="wrap">
        <h2>Anveto Affiliate Anywhere for WHMCS</h2>

        <p>
            This plugin adds the ability for ?aff to be used in links on any page of your wordpress website in order to register and track affiliate clicks/sales with WHMCS on remote websites.
        </p>

        <p>
            You will need the Anveto Affiliate Anywhere Plus addon for WHMCS in order to use this plugin. Purchase the addon here http://www.whmcs.com/appstore/3978/Anveto-Affiliate-Anywhere-Plus.html
        </p>

        <form method="post" action="options.php">
            <?php settings_fields('anveto-settingsGroup'); ?>
            <?php do_settings_sections('anveto-settingsGroup'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">WHMCS Url</th>
                    <td><input type="text" name="anveto-affiliate-anywhere-whmcs-url"
                               value="<?php echo get_option('anveto-affiliate-anywhere-whmcs-url'); ?>"/>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php
}

function anveto_affiliate_anywhere_whmcs_install() {

}

function anveto_affiliate_anywhere_whmcs_aff()
{
    if (isset($_GET['aff']) && $_GET['aff'] > 0) {
        $whmcsurl = get_option('anveto-affiliate-anywhere-whmcs-url');
        if (strlen($whmcsurl) > 5) {
            // Will only work with Anveto Affiliate Anywhere Plus
            $anvetoUrl = $whmcsurl . "?aff=" . $_GET['aff'] . "&site=http" . (isset($_SERVER['HTTPS']) ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . "/" . $_SERVER['REQUEST_URI'];
            if (!headers_sent()) {
                    header("Location: " . $anvetoUrl);
                } else {
                    echo '<script type="text/javascript">';
                    echo 'window.location.href="'.$anvetoUrl.'";';
                    echo '</script>';
                    echo '<noscript>';
                    echo '<meta http-equiv="refresh" content="0;url='.$anvetoUrl.'" />';
                    echo '</noscript>';
                }
                die();
        }
    }
}


add_action('wp_head','anveto_affiliate_anywhere_whmcs_aff');


register_activation_hook( __FILE__, 'anveto_affiliate_anywhere_whmcs_install' );
