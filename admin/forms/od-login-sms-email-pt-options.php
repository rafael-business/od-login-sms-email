<?php
$id = get_the_ID();
$code = get_post_meta( $id, 'access_code', true );
$code = $code ? esc_attr( $code ) : rand(10000, 99999);

$requester = get_post_meta( $id, 'requester', true );
$requester = $requester ? esc_attr( $requester ) : '';

$exp_date = get_post_meta( $id, 'exp_date', true );
$exp_date = $exp_date ? substr($exp_date, 0, 19) : '';

$cron_jobs = get_option( 'cron' );
var_dump($cron_jobs);
?>
<div>
    <style>
        input[type=text], 
        input[type=datetime-local], 
        input[type=number] {
            width: 100%;
        }
    </style>
    <p class="meta-options">
        <label for="access_code"><?php _e( 'Access Code', 'od-login-sms-email' ); ?></label>
        <input id="access_code" type="number" name="access_code" min="10000" max="99999" value="<?php echo $code; ?>">
    </p>
    <p class="meta-options">
        <label for="requester"><?php _e( 'Requester', 'od-login-sms-email' ); ?></label>
        <input id="requester" type="text" name="requester" value="<?php echo $requester; ?>">
    </p>
    <p class="meta-options">
        <label for="exp_date"><?php _e( 'Expiration Date', 'od-login-sms-email' ); ?></label>
        <input id="exp_date" type="datetime-local" name="exp_date" value="<?php echo $exp_date; ?>">
    </p>
</div>