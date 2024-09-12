<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    $paynocchio_classes = '';
    $accent_color = '#3b82f6';
    $accent_text_color = '#ffffff';


    $settings = get_option( 'woocommerce_paynocchio_settings');
    if($settings) {
        $paynocchio_classes .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
        $paynocchio_classes .= array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';

        if (array_key_exists('accent_color', $settings)) {
            $accent_color = get_option( 'woocommerce_paynocchio_settings')['accent_color'];
        }

        if (array_key_exists('accent_text_color', $settings)) {
            $accent_text_color = get_option( 'woocommerce_paynocchio_settings')['accent_text_color'];
        }
    }
?>

    <style>
        .paynocchio_colored {
            background-color: <?php echo $accent_color; ?>!important;
            color: <?php echo $accent_text_color; ?>!important;
        }
    </style>

    <section class="paynocchio <?php echo $paynocchio_classes; ?>">
        <div id="paynocchio_auth_block" class="cfps-mx-auto cfps-p-4 cfps-mb-4 cfps-flex cfps-flex-col cfps-flex-gap-8 cfps-items-center cfps-justify-between">

            <div class="cfps-flex cfps-flex-col cfps-gap-8 cfps-items-top cfps-p-8 cfps-w-full">
                <h2 class="!cfps-text-3xl">How to earn bonuses</h2>
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_1.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h3 class="!cfps-mb-0 !cfps-text-left">Be a member</h3>
                        <p class="cfps-text-base">Login or register</p>
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_2.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h3 class="!cfps-mb-0 !cfps-text-left">Activate your wallet</h3>
                        <p class="cfps-text-base">to get the rewards</p>
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_3.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h3 class="!cfps-mb-0 !cfps-text-left">Earn bonuses</h3>
                        <p class="cfps-text-base">for purchases and topups</p>
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_4.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h3 class="!cfps-mb-0 !cfps-text-left">Buy profitable</h3>
                        <p class="cfps-text-base">spending bonuses for purchases</p>
                    </div>
                </div>
            </div>

            <div id="login-signup-forms" class="cfps-w-full cfps-bg-gray-100 cfps-rounded-lg">
                <div class="paynocchio_form paynocchio_login_form visible">
                    <h2 class="paynocchio_form_title">Log In</h2>
                    <p class="cfps-mb-8 cfps-text-center cfps-text-balance">Enter your email address and password to log in.</p>
                    <form name="loginform" id="paynocchio_loginform"  method="post">
                        <div class="row">
                            <div class="col">
                                <label for="log">Login</label>
                                <input type="text" name="log" class="paynocchio_input border" id="user_name" />
                            </div>
                            <div class="col">
                                <label for="pwd">Password</label>
                                <div class="paynocchio_pwd">
                                    <input type="password" name="pwd" class="paynocchio_input" id="user_pass"  value="" autocomplete="current-password" spellcheck="false" required="required" />
                                    <button type="button" class="" id="show_password" data-toggle="0" aria-label="Show password" title="Show password">
                                        <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p>
                            <label><input name="rememberme" type="checkbox" id="paynocchio_rememberme" value="forever" /> Remember me</label>
                        </p>
                        <p>
                            <input type="submit" name="wp-submit" id="paynocchio_wp-submit" class="paynocchio_button cfps-w-full paynocchio_colored" value="Log in" />
                            <input type="hidden" name="cfps_cookie" value="1" />
                            <?php wp_nonce_field( 'paynocchio-ajax-login-nonce', 'loginsecurity' ); ?>
                        </p>
                    </form>
                    <div id="login_messages" style="display: none;"></div>
                    <p class="cfps-mt-8 cfps-text-center">Forgot your password? <a class="cfps-underline" href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>lost-password/">Recover</a></p>
                    <p class="cfps-mt-4 cfps-text-center">First time here? <a class="form-toggle-a">Sign Up</a></p>
                </div>

                <div class="paynocchio_form paynocchio_register_form">
                    <h2 class="cfps-text-center">Sign Up</h2>
                    <p class="cfps-mb-8 cfps-text-center">Enter your username and <br>email address to sign up.</p>
                    <form name="registerform" id="registerform" novalidate="novalidate">
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
                        <?php wp_nonce_field( 'ajax-registration-nonce', 'ajax-registration-nonce' ); ?>
                        <p class="submit">
                            <button type="button" id="wp-submit-registration" class="paynocchio_button cfps-w-full cfps-cursor-pointer paynocchio_colored">
                                Sign Up
                            </button>
                        </p>
                    </form>
                    <div id="register_messages" style="display: none;"></div>
                    <p class="cfps-text-center !cfps-mt-8">Already have an account? <a class="form-toggle-a">Log in</a></p>
                </div>
            </div>
        </div>
    </section>
<?php