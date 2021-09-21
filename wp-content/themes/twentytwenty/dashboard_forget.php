<?php /*Template name: dashboard-forget */ 
if(isset($_POST['password-reset']))
{
    global $wpdb;
    $emailId = $_POST['email'];
    $tokenuser = $wpdb->get_results("SELECT ID FROM wp_users WHERE user_email LIKE '%".$emailId."%' ");
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = '';
    if ($tokenuser){
            for ($i = 0; $i < 12; $i++) { 
                $index = rand(0, strlen($characters) - 1); 
                $randomString .= $characters[$index]; 
            }
            
            $applicationUrl = get_site_url().'/reset-password/';
            $tokenuserr = $tokenuser[0]->ID;
            update_user_meta( $tokenuserr, 'token', $randomString );
            $subject = 'Password reset';
            $message = ' <body> <p>Click on password reset link</p>
                            <p><a href="' . $applicationUrl . '?token='.$randomString. '&email=' . $emailId .  '">reset</a></p>
                                        </body>';
            $headers = 'From: noreply  <contact@shotcounttoken.com>'. "\r\n".'Content-type: text/html;';
        
            if(mail($emailId, $subject,$message,$headers)){$message= "Password reset link sent to your registered email.";}else{$message= "Something went wrong.";}
        }
            
    else{
        $message= "Email not exist";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!--<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/dashboard_css/img/favicon.png" sizes="32x32" type="image/png" />-->
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

        <title>Shot Count Token || Forget Password</title>
        <style>
             p.none-2 a:hover {
                color: #fff !important;
            }
            a {
                color: #ffb400;
            }
            h2 {
    font-size: 26px;
}
           .btn-theme {
                background: #ffb400;
                border: none;
                color: #ffffff;
                    padding: 10px 0;
            }
           .bg-img {
                background: rgba(0, 0, 0, 0.04) url(https://www.ramlogics.com/shortcountico/wp-content/themes/twentytwenty/assets/img/20business.png) top left no-repeat;
                background-size: cover;
                background-position: center;
                height: 100vh;
                z-index: 999;
                background-attachment: fixed;
                overflow: auto;
            }
            .bg-img.none-992::after { 
                background: #001e4200 !important 
            }
           .bg-img.none-992 {
                position: relative;
            }
           .sign-in-from {
                    padding: 28px 45px;
                    width: 55%;
                margin: 50px auto;
                position: relative;
                z-index: 9;
                border-radius: 25px;
                background-color: #fff;
            }
            .form-control {
                height: 45px;
                line-height: 45px;
                background: #e9edf4;
                border: 0px solid #d7dbda;
                font-size: 14px;
                color: #777D74;
            }
            .sign-info {
                    border-top: 1px solid #cdd1f3;
                    margin-top: 17px;
                    padding-top: 8px;
                }
                            
                            label {
                    font-size: 14px;
                    color: #374948;
                }
                .form-group {
                    margin: 0px 0;
                }
            
            @media(max-width:767px) { 
                .sign-in-from {
                    padding: 20px;
                    width: 85%;
                }
            }
            .btn-theme {
    background: #CEB557;
}
a {
    color: #CEB557;
}
body{
    background-color:#161b21;
}
        </style>
    </head>

    <body>
        <div class="bg-img none-992">
    <!--        <div class="container-fluid">-->
    <!--            <div class="row">-->
    <!--                <div class="col-lg-5 col-md-12 col-pad-0 bg-img none-992">-->
    <!--                    <div class="informeson">-->
                             
    <!--                        <h3>Do More With Your UCC Token ICO</h3>-->
    <!--                        <p>-->
    <!--                            Say Hello to the world's first scalable, decentralized blockchain cloud network.<br />-->
    <!--                            Future in the making.-->
    <!--                        </p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--        <div class="col-lg-7 col-md-12 col-pad-0 bg-color align-self-center">-->
    <!--            <div class="login-inner-form">-->
    <!--                <div class="details">-->
    <!--                    <a href="#">-->
    <!--                        <img src="<?php echo get_template_directory_uri(); ?>/dashboard_css/images/ucctoken-logo.png" style="height: 119px; margin: 0 auto;" />-->
    <!--                    </a>-->
    <!--                    <h3>Recover your password</h3>-->
    <!--                    <h5><? if(!empty($message)){ echo $message;}?></h5>-->
    <!--                    <form action="" method="post">-->
    <!--                        <div class="form-group">-->
    <!--                            <input type="email" name="email" class="input-text" placeholder="Email Address">-->
    <!--                        </div>-->
    <!--                        <div class="form-group">-->
    <!--                            <button type="submit" class="btn-md btn-theme btn-block" name="password-reset">Send Me Email</button>-->
    <!--                        </div>-->
    <!--                    </form>-->
    <!--                    <p class="none-2">Already a member? <a href="<?php echo get_site_url(); ?>/login/">Login here</a></p>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="container p-0">
                <div class="row no-gutters">
                    <div class="col-sm-12 align-self-center">
                        <div class="sign-in-from">
                            <!--<img src="https://www.ramlogics.com/shortcountico/wp-content/uploads/2021/05/ucc_token-1.png" class="float-right" width="50" alt="FIT" title="FIT" />-->
                            <img src="<?php echo get_template_directory_uri(); ?>/dashboard_css/images/short.png" class="float-right" width="50" alt="FIT" title="FIT" />
            
                            <h2 class="mb-0">Send Me Email</h2>
                            <p>Enter your details to access dashboard.</p>
                            <h5><? if(!empty($message)){ echo $message;}?></h5>
                            <form action="" method="post" name="login" id="login" class="mt-4">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" maxlngth="20" required="" />
             
                                </div> 
                                
                                <div class="d-inline-block w-100 mt-3">
                                    <button type="submit" class="btn  btn-theme btn-block" name="password-reset">Send Me Email</button>
                                </div>
                                <div class="sign-info">
                                    <span class="dark-color d-inline-block line-height-2">Don't have an account? <a href="<?php echo get_site_url(); ?>/login/">Login</a></span>
                                     
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</body>

</html>