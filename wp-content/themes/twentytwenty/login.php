<?php /*template name:login */;
 global $wpdb;
     
            if(isset($_POST['login'])){ 
                $credentials = array();
                $credentials['user_login'] = trim($_POST['user_email']);
                $credentials['user_password'] = trim($_POST['password']);
                $user_name = trim($_POST['user_email']);
                $user_password = trim($_POST['password']);
                $get_user_id = get_user_by('login',$user_name);
                $user = get_user_by('login', $credentials['user_login']);
                if (!$user->ID) {
                $response="This username you have entered does not exist.";
            } else {
                
                $stored_token = get_user_meta($user->ID, 'wpcrl_email_verification_token', true);
                if (!$stored_token) {
                    $user = wp_signon($credentials, false);
                       if(is_wp_error($user)){
                           $response = "Username & Password Not Correct";
                       }else{
                            wp_set_auth_cookie($user->data->ID);
                            wp_set_current_user($user->data->ID, $user->data->user_login);
                            do_action('set_current_user');
                            function getBrowser() 
						{ 
							$u_agent = $_SERVER['HTTP_USER_AGENT']; 
							$bname = 'Unknown';
							$platform = 'Unknown';
							$version= "";

							//First get the platform?
							if (preg_match('/linux/i', $u_agent)) {
								$platform = 'linux';
							}
							elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
								$platform = 'mac';
							}
							elseif (preg_match('/windows|win32/i', $u_agent)) {
								$platform = 'windows';
							}

							// Next get the name of the useragent yes seperately and for good reason
							if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
							{ 
								$bname = 'Internet Explorer'; 
								$ub = "MSIE"; 
							} 
							elseif(preg_match('/Firefox/i',$u_agent)) 
							{ 
								$bname = 'Mozilla Firefox'; 
								$ub = "Firefox"; 
							}
							elseif(preg_match('/OPR/i',$u_agent)) 
							{ 
								$bname = 'Opera'; 
								$ub = "Opera"; 
							} 
							elseif(preg_match('/Chrome/i',$u_agent)) 
							{ 
								$bname = 'Google Chrome'; 
								$ub = "Chrome"; 
							} 
							elseif(preg_match('/Safari/i',$u_agent)) 
							{ 
								$bname = 'Apple Safari'; 
								$ub = "Safari"; 
							} 
							elseif(preg_match('/Netscape/i',$u_agent)) 
							{ 
								$bname = 'Netscape'; 
								$ub = "Netscape"; 
							} 

							// finally get the correct version number
							$known = array('Version', $ub, 'other');
							$pattern = '#(?<browser>' . join('|', $known) .
							')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
							if (!preg_match_all($pattern, $u_agent, $matches)) {
								// we have no matching number just continue
							}

							// see how many we have
							$i = count($matches['browser']);
							if ($i != 1) {
								//we will have two since we are not using 'other' argument yet
								//see if version is before or after the name
								if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
									$version= $matches['version'][0];
								}
								else {
									$version= $matches['version'][1];
								}
							}
							else {
								$version= $matches['version'][0];
							}

							// check if we have a number
							if ($version==null || $version=="") {$version="?";}

							return array(
								'name'      => $bname,
								'platform'  => $platform,
								'ip'=>$_SERVER['REMOTE_ADDR'],
								'login_date'=>date('Y-m-d H:i:s')
								
							);
						} 
						//$ua=getBrowser();
                            $to=$user->user_email;
                            $subject = get_option('blogname')." - successfully login";
				            $message = '<html>
								<style>
									.header {
										
										margin: auto;
										display: block;
										text-align: center;
										padding: 0px 0px 30px;
										vertical-align: middle;
										margin-bottom: 20px;
									}
									
									.footer {
										
										padding: 20px;
										text-align: center;
										
									}
								</style>
								<table style="width:400px; margin:auto; border: 1px solid #123e59;">
									<thead>
										<tr>
											<th class="header" style="color: #123e59;  padding-bottom: 30px;"><img src="" style="margin: 20px auto 0; display:block; max-height: 80px; " /></th>
										</tr>
									</thead>
									<tbody>
										<tr class="email text-center">
											<td style="color: #123e59; padding-left: 30px;padding-right: 30px; text-align: center;">
												<h2 style="color: #123e59; margin-bottom: 2px;">  Hello, '.$user->user_nicename.' </h2>
												<h3 style="color: #123e59; margin-top: 4px;"> Login Activity </h3>
											 </td> 
													
										</tr>
										<tr>
											<td style="color: #123e59; padding-left: 30px;padding-right: 30px;">Login Devices Details </td>
										</tr>


								

										  <tr>
											<td style="color: #123e59; padding-left: 30px;padding-right: 30px;">We will Always let You Know There is Any Activity on Your '.get_option('blogname').' Account </td>
										</tr>
									   
									   
										<tr>
											<td style="color: #123e59; padding-left: 30px;padding-right: 30px;">
											   
												<br>
											</td>
										</tr>
										<tr>
											<td style="color: #123e59; padding-left: 30px;padding-right: 30px; padding-bottom:40px;">'.get_option('blogname').' Team
												<br> This is automated message please don&rsquo;t reply
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td class="footer" style="text-align: center; background: #f4b51e;color: #123e59;  padding: 20px 0px;">
												<p>© Copyright 2020. All right reserved. by '.get_option('blogname').'.</p>
											</td>
										</tr>
									</tfoot>
								</table>

								</html>';
								
                                $headers = 'From: noreply <info@shoutcount.com>'. "\r\n".'Content-type: text/html;';
                        if(mail($to, $subject, $message, $headers)){ 
                            ?><script>window.location='".get_site_url()."/dashboard/';</script><?
                        }
                        else{ echo "mail not send";}
                        }
                } else {
                    $response="Account Not Activate";
                  
                }
            }
             //echo $response;
            }
            
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

        <title>Shotcountcrypto ICO Token || Log In</title>
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
                             
                            <h3>Do More With Your SHOT COUNT Token ICO</h3>
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
                                <h3>Sign into your account</h3>
                                <h5><? if(!empty($response)){echo $response;}?></h5>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="user_email" class="input-text" placeholder="Enter Username" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="input-text" placeholder="Password" />
                                    </div>
                                    <div class="checkbox clearfix">
                                        <div class="form-check checkbox-theme">
                                            <input class="form-check-input" type="checkbox" value="" id="rememberMe" required/>
                                            <label class="form-check-label" for="rememberMe">
                                                Check me
                                            </label>
                                        </div>
                                        <a href="<?php echo get_site_url(); ?>/forget/">Forgot Password</a>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn-md btn-theme btn-block" name="login" value="Login" />
                                    </div>
                                    <p class="none-2">Don't have an account?<a href="<?php echo get_site_url(); ?>/Register/"> Register here</a></p>
                                </form>
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
