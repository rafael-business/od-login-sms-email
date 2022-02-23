<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://olivas.digital
 * @since      1.0.0
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/admin
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1><?php _e( 'SMS/Email Login', 'od-login-sms-email' ); ?></h1>
    <hr class="wp-header-end">
    <form method="POST" action="options.php" autocomplete="off">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container">
                <?php settings_fields( 'od-login-sms-email-configs' ); ?>
                <div class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle"><?php _e( 'SMS', 'od-login-sms-email' ); ?></h2>
                    </div>
                    <div class="inside">
                        <div class="main">
                            <div class="field">
                                <label><input type="checkbox" name="od_login_sms_email_sms" value="1" <?= get_option('od_login_sms_email_sms') == '1' ? 'checked="checked"' : '' ?>> <?php _e( 'Enable', 'od-login-sms-email' ); ?></label>
                            </div>
                            <div class="field">
                                <label for="od_login_sms_email_sms_icon"><?php _e( 'SMS Icon', 'od-login-sms-email' ); ?></label>
                                <input type="url" name="od_login_sms_email_sms_icon" value="<?= esc_attr( get_option('od_login_sms_email_sms_icon') ) ?>" pattern="https?://.*">
                                <div id="sms_icon" class="img_field_thumb"><img src="<?= get_option('od_login_sms_email_sms_icon') ? get_option('od_login_sms_email_sms_icon') : plugin_dir_url( __DIR__ ).'../public/img/sms.png' ?>" width="30" height="30"></div>
                            </div>
                            <div class="field">
                                <label for="od_login_sms_email_account"><?php _e( 'Zenvia Account', 'od-login-sms-email' ); ?></label>
                                <input type="text" name="od_login_sms_email_account" value="<?= esc_attr( get_option('od_login_sms_email_account') ) ?>">
                            </div>
                            <div class="field">
                                <label for="od_login_sms_email_password"><?php _e( 'Zenvia Password', 'od-login-sms-email' ); ?></label>
                                <input type="password" name="od_login_sms_email_password" value="<?= esc_attr( get_option('od_login_sms_email_password') ) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle"><?php _e( 'Email', 'od-login-sms-email' ); ?></h2>
                    </div>
                    <div class="inside">
                        <div class="main">
                            <div class="field">
                                <label><input type="checkbox" name="od_login_sms_email_email" value="1" <?= get_option('od_login_sms_email_email') == '1' ? 'checked="checked"' : '' ?>> <?php _e( 'Enable', 'od-login-sms-email' ); ?></label>
                            </div>
                            <div class="field">
                                <label for="od_login_sms_email_email_icon"><?php _e( 'Mail Icon', 'od-login-sms-email' ); ?></label>
                                <input type="url" name="od_login_sms_email_email_icon" value="<?= esc_attr( get_option('od_login_sms_email_email_icon') ) ?>" pattern="https?://.*">
                                <div id="email_icon" class="img_field_thumb"><img src="<?= get_option('od_login_sms_email_email_icon') ? get_option('od_login_sms_email_email_icon') : plugin_dir_url( __DIR__ ).'../public/img/o-email.png' ?>" width="30" height="30"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="submit">
                    <input class="button button-primary" type="submit" value="Salvar">
                </p>
            </div>
        </div>
    </form>
</div>
