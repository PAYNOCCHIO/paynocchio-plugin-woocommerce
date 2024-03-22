import kopybara_logo from "../../img/kopybara-logo.png";

export default function RegistrationBlock() {
    return(
        <section className="paynocchio">
            <div className="paynocchio-embleme">
                <img
                    src={kopybara_logo}
                    className="!cfps-block !cfps-mx-auto"/>
            </div>
            <div id="paynocchio_auth_block" className="cfps-max-w-4xl cfps-mx-auto cfps-mb-4">
                <div className="paynocchio_login_form visible">
                    <h2 className="cfps-text-center">Please, login before contunue!</h2>
                    <p className="cfps-mb-8 cfps-text-center">Not registered? <a className="form-toggle-a">Sign up!</a>
                    </p>
                    <form name="loginform" id="paynocchio_loginform" action="/wp-login.php" method="post">
                        <div className="row">
                            <div className="col">
                                <label htmlFor="log">Login</label>
                                <input type="text" name="log" className="paynocchio_input" id="paynocchio_user_login"/>
                            </div>
                            <div className="col">
                                <label htmlFor="pwd">Password</label>
                                <input type="password" name="pwd" className="paynocchio_input"
                                       id="paynocchio_user_pass"/>
                            </div>
                        </div>
                        <p className="row">
                            <label><input name="rememberme" type="checkbox" id="paynocchio_rememberme"
                                          value="forever"/> Remember me</label>
                        </p>
                        <p className="row">
                            <input type="submit" name="wp-submit" id="paynocchio_wp-submit"
                                   className="paynocchio_button" value="Log in"/>
                            <input type="hidden" name="cfps_cookie" value="1"/>
                        </p>
                    </form>
                </div>

                <div className="paynocchio_register_form">
                    <h2 className="cfps-text-center">Join Us for your convenience!</h2>
                    <p className="cfps-text-center cfps-mb-8">Already registered? <a className="form-toggle-a">Log
                        in!</a></p>
                    <form name="registerform" id="registerform" noValidate="novalidate" action={'/wp-login.php?action=register'} method={'post'}>
                        <div className="row">
                            <div className="col">
                                <label htmlFor="user_login" className="for_input">Username</label><br/>
                                <input type="text" name="user_login" id="user_login" className="paynocchio_input"
                                       value="" autoCapitalize="off" autoComplete="username" required="required" />
                            </div>
                            <div className="col">
                                <label htmlFor="user_email" className="for_input">Email</label><br/>
                                <input type="email" name="user_email" id="user_email" className="paynocchio_input"
                                       value="" autoComplete="email" required="required" />
                            </div>
                        </div>
                        <p id="reg_passmail" className="row">
                            Registration confirmation will be emailed to you.
                        </p>
                            <p className="submit row">
                                <input type="submit" name="wp-submit" id="wp-submit" className="paynocchio_button"
                                       value="Register" />
                            </p>
                    </form>
                </div>
            </div>
        </section>
    );
}