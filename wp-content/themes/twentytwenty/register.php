<?php /*template name: register */
global $wpdb, $error_message, $success_message;
 if (isset($_POST['submit'])) {
            $pswrd = $_POST["password"];
            $cpswrd = $_POST["cpassword"];
            $uname = $wpdb->remove_placeholder_escape( esc_sql($_REQUEST['username']));
            if(empty($uname)) {
                $error_message = "Please enter User name"; 
            }elseif(username_exists( $uname )) {  
                $error_message = "This Username is already in use"; 
            }
            $useremail = $wpdb->remove_placeholder_escape( esc_sql($_REQUEST['email']));  
            if(!is_email($useremail)) {   
                $error_message = "Please enter a valid email";
            }elseif(email_exists( $useremail )) {  
                $error_message = "This email address is already in use"; 
            }
            if(0 === preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/', $_POST['password']) ) {  
                $error_message = "Must contain at least one number or one special character, and at least 8 or more characters";
            }
            if($pswrd != $cpswrd) {
                $error_message = "Confirm password and password are different";
            }
           
            
      if(!isset($error_message)){
            $activationlink=md5(wp_generate_password(26, true));
                $userdata = array(
                    'user_login' => apply_filters('pre_user_login', trim($_POST['username'])),
                    'user_pass' => apply_filters('pre_user_pass', trim($_POST['password'])),
                    'user_email' => apply_filters('pre_user_email', trim($_POST['email'])),
                    'first_name' => apply_filters('pre_user_first_name', trim($_POST['username'])),
                    'last_name' => apply_filters('pre_user_last_name', ""),
                    'role' => get_option('default_role'),
                    'user_registered' => date('Y-m-d H:i:s')
                );
                $user_id = wp_insert_user($userdata);
                if(is_wp_error( $user_id)){
                    $error_string = $user_id->get_error_message();
                }else{
                   
                // add_user_meta($user_id, 'wpcrl_email_verification_token', $activationlink);
				$to=$_POST['email'];
                //$error_string= get_site_url()."/login/?email=".trim($_POST['email'])."&wpcrl_email_verification_token=".$activationlink; 
                $subject = get_option('blogname')." - successfully register";
				$message = ' <body><div style="width:400px; margin:auto; border: 1px solid #123e59;">
                                    <div><div><div class="header" style="padding-bottom: 30px;    padding-top: 10px;"><img src="" style="margin: 20px auto 0; display:block; max-height: 80px; " /></div></div></div>
                                    <div>
                                    <div class="email" style="padding-left: 30px;padding-right: 30px;"><td style="color: #123e59; padding-left: 30px;padding-right: 30px;"><p style="color: #123e59; height: auto; font-size: 16px; "><b> Hello</b>,<br><br> This email is to notify you of a successful Registration  to your '.get_option('blogname').' account from <br> an IP address . </p></div>
                                    <div><div style="color: #123e59; padding-left: 30px;padding-right: 30px;">Registration Details:</div></div>   
                                    <div><div style="color: #123e59; padding-left: 30px;padding-right: 30px;">Date(UTC):<b style="margin-left: 48px;">'.date("Y/m/d H:i:s").'</b></div></div> 
                                    <div><div style="color: #123e59; padding-left: 30px;padding-right: 30px;"><br><br></div></div> 
                                    <div><div style="color: #123e59; padding-left: 30px;padding-right: 30px; padding-bottom:40px;">'.get_option('blogname').' Team <br>
                                      This is automated message please don&rsquo;t reply
                                    </div></div>
                                    </div>
                                    <div>
                                    <div><div class="footer" style="text-align: center; color: #123e59; background: #f4b51e; padding: 10px 0px;"><p>&copy; Copyright  '.get_option('blogname').' 2020 All Rights Reserved.</p></div></div>
                                    </div>
                                </div>

                                </body>';
                                $headers = 'From: noreply  <contact@shoutcount.com>'. "\r\n".'Content-type: text/html;';
                    if(mail($to, $subject, $message, $headers)){
                          $error_message = "You have registered successfully!!"; 
                    }else{
                    $error_message=" Email not sent ";
                    } 
				}
          }
        }
//   }




?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/dashboard_css/images/short.png" sizes="32x32" type="image/png" />

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/stylesheet.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/hover.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/rohitkumar.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/purple.css" />
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&display=swap" rel="stylesheet" />

        <title>ShotcountCrypto ICO Token || Log In</title>
        <style>
            p.none-2 a:hover {
                color: #fff !important;
            }
            a {
                color: #9f8e47;
            }
            p {
                color: #fff;
            }
            .login-13 .informeson {
                color: #fff;
            }
            .btn-section {
                margin-bottom: 30px;
            }
            .login-13 .informeson .active {
                background: #f0151f;
                color: #fff;
            }
            .login-13 .informeson .link-btn {
                background: #fff;
                padding: 6px 30px;
                font-size: 13px;
                border-radius: 3px;
                margin: 3px;
                letter-spacing: 1px;
                font-weight: 600;
                color: #0c195a;
            }
            .login-13 .informeson .link-btn {
                background: #fff;
                padding: 6px 30px;
                font-size: 13px;
                border-radius: 3px;
                margin: 3px;
                letter-spacing: 1px;
                font-weight: 600;
                color: #0c195a;
            }
            .login-13 .informeson h3 {
                color: #fff;
                margin-bottom: 25px;
                font-size: 31px;
            }
            .login-13 .social-box {
                bottom: 40px;
                position: absolute;
                right: 45px;
            }
            .login-13 .informeson {
                color: #fff;
            }
            .login-13 .social-box {
                bottom: 40px;
                position: absolute;
                right: 45px;
            }
            .login-13 .social-box ul {
                margin: 0;
                padding: 0;
            }
            .login-13 .informeson p {
                color: #fbfbfb;
                line-height: 30px;
                margin-bottom: 30px;
            }
            .login-13 .login-inner-form {
                max-width: 350px;
                color: #fff;
                width: 100%;
                text-align: center;
            }
            .login-13 .bg-color {
                background: #161b21;
                min-height: 100vh;
                position: relative;
                text-align: center;
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 30px;
            }
            .login-13 .login-inner-form h3 {
                margin: 0 0 25px;
                font-size: 18px;
                font-weight: 400;
                font-family: "Open Sans", sans-serif;
                color: #fff;
            }
            .login-13 .login-inner-form .form-group {
                margin-bottom: 25px;
            }
            .login-13 .login-inner-form .input-text {
                width: 100%;
                padding: 10px 15px;
                font-size: 14px;
                border: 1px solid #e8e7e7;
                outline: none;
                color: #717171;
                border-radius: 3px;
                height: 45px;
            }
            .login-13 .login-inner-form .checkbox {
                margin-bottom: 25px;
                font-size: 14px;
                width: 100%;
            }
            .login-13 .login-inner-form .form-check {
                float: left;
                margin-bottom: 0;
            }
            .form-check {
                position: relative;
                display: block;
                margin-bottom: 0.5rem;
            }
            .login-13 .login-inner-form .checkbox a {
                font-size: 14px;
                color: #fff;
                float: right;
            }
            .login-13 .login-inner-form input[type="checkbox"],
            input[type="radio"] {
                margin-right: 3px;
            }

            .login-13 .login-inner-form .form-group {
                margin-bottom: 25px;
            }
           .login-13 .login-inner-form .btn-theme {
                background: #9f8e47;
                border: none;
                color: #ffffff;
            }
            .login-13 .login-inner-form .btn-md {
                cursor: pointer;
                padding: 12px 30px 11px 30px;
                letter-spacing: 1px;
                font-size: 15px;
                font-weight: 600;
                font-family: "Open Sans", sans-serif;
                border-radius: 3px;
            }
            .login-13 .login-inner-form .checkbox a {
                font-size: 14px;
                color: #fff;
                float: right;
            }
            .login-13 .login-inner-form input[type="checkbox"],
            input[type="radio"] {
                margin-right: 3px;
            }
        </style>

    </head>

    <body>
        <div class="login-13 tab-box">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5 col-md-12 col-pad-0 bg-img none-992">
                        <div class="informeson">
                             
                            <h3>Do More With Your Shotcount Token ICO</h3>
                            <p>
                                Say Hello to the world's first scalable, decentralized blockchain cloud network.<br />
                                Future in the making.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-pad-0 bg-color align-self-center">
                        <div class="login-inner-form">
                            <div class="details">
                                <a href="#">
                                    <img src="<?php echo get_template_directory_uri(); ?>/dashboard_css/images/short.png" style="height: 119px; margin: 0 auto;" />
                                </a>
                                <h3>Create an account</h3>
                                <h5><? if(!empty($error_message)){echo $error_message;}?></h5>
                                <form action="" method="post">
                                    <div class="form-group">
                                        <input type="text" name="username" class="input-text" placeholder="User Name"  required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" class="input-text" placeholder="Email Address" required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="input-text" placeholder="Password" required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="cpassword" class="input-text" placeholder="Confirm Password" required/>
                                    </div>
                                    <div class="checkbox clearfix">
                                        <div class="form-check checkbox-theme">
                                            <input class="form-check-input" type="checkbox" value="" id="termsOfService" name="check" required/>
                                            <label class="form-check-label" for="termsOfService"> I agree to the<a href="#" class="terms">&nbsp terms of service</a> </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn-md btn-theme btn-block" name="submit">Register</button>
                                    </div>
                                </form>
                                <p class="none-2">Already a member? <a href="<?php echo get_site_url(); ?>/login/">Login here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-3.2.1.slim.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/assets/js/popper.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/assets/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    </body>
</html>
