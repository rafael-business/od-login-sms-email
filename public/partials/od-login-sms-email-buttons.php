<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://olivas.digital
 * @since      1.0.0
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="od-login-sms-email">
    <a id="sms_popup" class="ywsl-social thickbox" href="#TB_inline?&width=250&height=236&inlineId=sms_content"><img src="<?= $sms_img ?>" width="35" height="35"></a>
    <a id="email_popup" class="ywsl-social thickbox" href="#TB_inline?&width=250&height=236&inlineId=email_content"><img src="<?= $email_img ?>" width="35" height="35"></a>
    <a id="code_popup" class="ywsl-social thickbox" href="#TB_inline?&width=250&height=236&inlineId=code_content"><img src="<?= $email_img ?>" width="35" height="35" style="display:none;"></a>
</div>
<div id="popup_thickbox">
    <?php add_thickbox(); ?>
    <div id="sms_content" style="display:none;">
        <img class="icon_popup" src="<?= $sms_img ?>" width="32" height="32">
        <div class="field_tickbox">
            <label for="cel"><?php _e('Cellphone', 'od-login-sms-email') ?></label>
            <input type="tel" id="cel" maxlength="15" pattern="\(\d{2}\)\s*\d{5}-\d{4}">
        </div>
        <div class="controls">
            <div class="status" id="sms_status"></div>
            <button id="send_sms" class="send_button"><?php _e('Send', 'od-login-sms-email'); ?></button>
        </div>
        <div class="msg"><?php _e('You will receive a numeric code via SMS.', 'od-login-sms-email'); ?></div>
    </div>
    <div id="email_content" style="display:none;">
        <img class="icon_popup" src="<?= $email_img ?>" width="32" height="32">
        <div class="field_tickbox">
            <label for="email"><?php _e('Email', 'od-login-sms-email') ?></label>
            <input type="email" id="email">
        </div>
        <div class="controls">
            <div class="status" id="email_status"></div>
            <button id="send_email" class="send_button"><?php _e('Send', 'od-login-sms-email'); ?></button>
        </div>
        <div class="msg"><?php _e('You will receive a numeric code via Email.', 'od-login-sms-email'); ?></div>
    </div>
    <div  id="code_content" style="display:none;">
        <div class="field_tickbox centralized">
            <label for="code"><?php _e('Access Code', 'od-login-sms-email'); ?></label>
            <input type="text" id="code" class="big_input" maxlength="5">
        </div>
        <div class="msg"></div>
    </div>
</div>
