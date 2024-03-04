<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<section class="paynocchio">
    <div id="paynocchio_auth_block" class="max-w-4xl mx-auto">
        <h2 class="h2 mb-0">Please login or register before contunue.</h2>
        <form id="paynocchio_login_block" action="login" method="post">
            <label for="paynocchio_username">Логин</label>
            <input id="paynocchio_username" name="paynocchio_username" type="text" />
            <label for="paynocchio_password">Пароль</label>
            <input id="paynocchio_password" name="paynocchio_password" type="password" />
            <a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Forgot password?</a>
            <button>Login</button>
            <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
        </form>
    </div>
</section>
<?php