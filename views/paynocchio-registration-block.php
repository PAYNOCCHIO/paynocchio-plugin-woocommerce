<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<section class="paynocchio">
    <div class="paynocchio-embleme">
        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/kopybara-logo.png' ?>" class="!cfps-block !cfps-mx-auto" />
    </div>
    <div id="paynocchio_auth_block" class="cfps-max-w-4xl cfps-mx-auto cfps-mb-4">
        <div class="paynocchio_login_form visible">
            <h2 class="cfps-text-center">Please, login before contunue!</h2>
            <p class="cfps-mb-8 cfps-text-center">Not registered? <a class="form-toggle-a">Sign up!</a></p>
            <form name="loginform" id="paynocchio_loginform" action="<?php bloginfo('url') ?>/wp-login.php" method="post">
                <div class="row">
                    <div class="col">
                        <label for="log">Login</label>
                        <input type="text" name="log" class="paynocchio_input" id="paynocchio_user_login" />
                    </div>
                    <div class="col">
                        <label for="pwd">Password</label>
                        <input type="password" name="pwd" class="paynocchio_input" id="paynocchio_user_pass" />
                    </div>
                </div>
                <p class="row">
                    <label><input name="rememberme" type="checkbox" id="paynocchio_rememberme" value="forever" /> Remember me</label>
                </p>
                <p class="row">
                    <input type="submit" name="wp-submit" id="paynocchio_wp-submit" class="paynocchio_button" value="Log in" />
                    <input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?><?php echo $attr['login_redirect'] ?? ''; ?>" />
                    <input type="hidden" name="cfps_cookie" value="1" />
                </p>
            </form>
        </div>

        <div class="paynocchio_register_form">
            <h2 class="cfps-text-center">Join <?php bloginfo('title') ?> for your convenience!</h2>
            <p class="cfps-text-center cfps-mb-8">Already registered? <a class="form-toggle-a">Log in!</a></p>
            <form name="registerform" id="registerform" action="<?php bloginfo('url') ?>/wp-login.php?action=register" method="post" novalidate="novalidate">
                <div class="row">
                    <div class="col">
                        <label for="user_login" class="for_input">Username</label><br />
                        <input type="text" name="user_login" id="user_login" class="paynocchio_input" value="" autocapitalize="off" autocomplete="username" required="required">
                    </div>
                    <div class="col">
                        <label for="user_email" class="for_input">Email</label><br />
                        <input type="email" name="user_email" id="user_email" class="paynocchio_input" value="" autocomplete="email" required="required">
                    </div>
                </div>
                <p id="reg_passmail" class="row">
                    Registration confirmation will be emailed to you.
                </p>
                <input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?><?php echo $attr['register_redirect'] ?? ''; ?>">
                <p class="submit row">
                    <input type="submit" name="wp-submit" id="wp-submit" class="paynocchio_button" value="Register">
                </p>
            </form>
        </div>
    </div>
</section>
<?php