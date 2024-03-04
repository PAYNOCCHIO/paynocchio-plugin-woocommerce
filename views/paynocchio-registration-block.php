<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<section class="paynocchio">
    <div id="paynocchio_auth_block" class="max-w-4xl mx-auto">
        <h2 class="h2 mb-0">Please login or register before contunue.</h2>
        <div id="paynocchio_login_form">
            <label for="paynocchio_login_username">Логин</label>
            <input id="paynocchio_login_username" name="paynocchio_login_username" type="text" />
            <label for="paynocchio_login_password">Пароль</label>
            <input id="paynocchio_login_password" name="paynocchio_login_password" type="password" />
            <a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Forgot password?</a>
            <button type="button" id="paynocchio_login_button">Login</button>
            <?php wp_nonce_field( 'ajax-login-nonce', 'ajax-login-nonce' ); ?>
        </div>
    </div>
</section>
<?php