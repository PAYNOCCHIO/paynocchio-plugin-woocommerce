<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    $paynocchio_classes = '';
    $settigns = get_option( 'woocommerce_paynocchio_settings');
    $paynocchio_classes .= array_key_exists('darkmode', $settigns) && $settigns['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
    $paynocchio_classes .= array_key_exists('rounded', $settigns) && $settigns['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';
    $embleme_link = plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/paynocchio_';
    $embleme_link .= array_key_exists('darkmode', $settigns) && $settigns['darkmode'] == 'yes' ? 'white.svg' : 'black.svg';

    $accent_color = '#3b82f6';
    if (array_key_exists('accent_color', $settigns)) {
        $accent_color = get_option( 'woocommerce_paynocchio_settings')['accent_color'];
    }

    $accent_text_color = '#ffffff';
    if (array_key_exists('accent_text_color', $settigns)) {
        $accent_text_color = get_option( 'woocommerce_paynocchio_settings')['accent_text_color'];
    }
?>

    <style>
        .paynocchio_colored {
            background-color: <?php echo $accent_color; ?>!important;
            color: <?php echo $accent_text_color; ?>!important;
        }
    </style>

    <section class="paynocchio <?php echo $paynocchio_classes; ?>">
        <div id="paynocchio_auth_block" class="cfps-max-w-[340px] cfps-mx-auto cfps-mb-4">
            <!--<div class="paynocchio-embleme">
                <img src="<?php /*echo $embleme_link; */?>" />
            </div>-->
            <div id="login-signup-forms">
                <div class="paynocchio_form paynocchio_login_form visible">
                    <h2 class="paynocchio_form_title">Log In</h2>
                    <p class="cfps-mb-8 cfps-text-center">Enter your email address and password to log in.</p>
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
                        <p>
                            <label><input name="rememberme" type="checkbox" id="paynocchio_rememberme" value="forever" /> Remember me</label>
                        </p>
                        <p>
                            <input type="submit" name="wp-submit" id="paynocchio_wp-submit" class="paynocchio_button cfps-w-full paynocchio_colored" value="Log in" />
                            <input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?><?php echo $attr['login_redirect'] ?? ''; ?>" />
                            <input type="hidden" name="cfps_cookie" value="1" />
                        </p>
                    </form>
                    <p class="cfps-mt-8 cfps-text-center">First time here? <a class="form-toggle-a">Registration</a></p>
                </div>

                <div class="paynocchio_form paynocchio_register_form">
                    <h2 class="cfps-text-center">Registration</h2>
                    <p class="cfps-mb-8 cfps-text-center">Enter your email username and address to sign up.</p>
                    <form class="cfps-mb-8" name="registerform" id="registerform" novalidate="novalidate">
                        <div class="row">
                            <div class="col">
                                <label for="user_login" class="for_input">Username</label>
                                <input type="text" name="user_login" id="user_login" class="paynocchio_input" value="" autocapitalize="off" autocomplete="username" required="required">
                            </div>
                            <div class="col">
                                <label for="user_email" class="for_input">Email</label>
                                <input type="email" name="user_email" id="user_email" class="paynocchio_input" value="" autocomplete="email" required="required">
                            </div>
                        </div>
                        <p id="reg_passmail" class="!cfps-text-sm !cfps-text-gray-400 cfps-text-center">
                            Registration confirmation will be emailed to you. By clicking Sign Up, You agree to the <a href="/privacy-policy" class="cfps-text-blue-500">privacy policy and the processing of personal data</a>.
                        </p>
                        <input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?><?php echo $attr['register_redirect'] ?? ''; ?>">
                        <p class="submit">
                            <input type="button" name="wp-submit" id="wp-submit-registration" class="paynocchio_button cfps-w-full paynocchio_colored" value="Register">
                        </p>
                    </form>
                    <div id="register_messages"></div>
                    <p class="cfps-text-center !cfps-mt-8">Already have an account? <a class="form-toggle-a">Log in</a></p>
                </div>
            </div>
        </div>
    </section>
<?php