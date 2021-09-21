<?php /*template name: Dashboard_Header */
$user = get_current_user_id(); 
if($user==0 && empty($user)){
    echo "<script> window.location = '".get_site_url()."/login'; </script>";
}$redirect = home_url()."/login/";
    function coinpayments_api_call($curr, $cmd, $req = array()) {
      
    // Fill these in from your API Keys page
    $public_key = 'iq4GdYk7KNajDW9PRbIxqnIWP6hPmBKVv68eg6clCI4W3KnUiqt6aKlmJeNQAzzQ';
    $private_key = 'umUtWMu9ld9AMWdxrYMCDRnQJCT9008RDNVSse8HQh3WCQt0lZ5cDZs96qzWq7UM';
    
    // Set the API command and required fields
    $req['version'] = 1;
    $req['cmd'] = $cmd;
    $req['key'] = $public_key;
    $req['currency']=$curr;
    $req['format'] = 'json'; //supported values are json and xml
    
    
    // Generate the query string
    $post_data = http_build_query($req, '', '&');
    
    // Calculate the HMAC signature on the POST data
    $hmac = hash_hmac('sha512', $post_data, $private_key);
    
    // Create cURL handle and initialize (if needed)
    static $ch = NULL;
    if ($ch === NULL) {
        $ch = curl_init('https://www.coinpayments.net/api.php');
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: '.$hmac));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    
    // Execute the call and close cURL handle     
    $data = curl_exec($ch);                
    // Parse and return data if successful.
    if ($data !== FALSE) {
        if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
        } else {
            $dec = json_decode($data, TRUE);
        }
        if ($dec !== NULL && count($dec)) {
            return $dec;
        } else {
            return array('error' => 'Unable to parse JSON result ('.json_last_error().')');
        }
    } else {
        return array('error' => 'cURL error: '.curl_error($ch));
    }
}

$name = wp_get_current_user()->user_login;
$userid = get_current_user_id();
$data= array(
    "user_id" => $userid,
    "username" => $name,
    
    );
$balance_id=$wpdb->get_var("SELECT user_id FROM wp_user_balance WHERE user_id = $userid");
if(empty($balance_id) && $userid!=0){
    $wpdb->insert( 'wp_user_balance', $data );
}

$idexist = $wpdb->get_results("SELECT user_id FROM wp_user_address WHERE user_id = $userid");
if(!$idexist){
$eth=(coinpayments_api_call("ETH",'get_callback_address'));
$btc=(coinpayments_api_call("BTC",'get_callback_address'));
$xrp=(coinpayments_api_call("XRP",'get_callback_address'));
$trx=(coinpayments_api_call("TRX",'get_callback_address'));
$wpdb->insert('wp_user_address', array('user_id'=>$userid, 'user_name'=>$name, 'eth_address'=>$eth['result']['address'], 'btc_address'=>$btc['result']['address'], 'xrp_address'=>$xrp['result']['address'],
'xrp_tag'=>$xrp['result']['dest_tag'], 'trx_address'=>$trx['result']['address']));

}
 if (isset($_POST['anchor_name'])) {
         include('logout.php');
     }
     
     
     define('PAYPAL_ID', 'sb-f5aza6237789@business.example.com'); 
    define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE 
    define('PAYPAL_RETURN_URL', 'http://www.example.com/success.php'); 
    define('PAYPAL_CANCEL_URL', 'http://www.example.com/cancel.php'); 
    define('PAYPAL_NOTIFY_URL', 'http://www.example.com/ipn.php'); 
    define('PAYPAL_CURRENCY', 'USD'); 
    
    define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/dashboard_css/images/short.png"> 

        <title>SHOT COUNT Token ICO Admin || Dashboards</title>

        <!-- Vendors Style-->
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
        <!-- Style-->
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/dashboard_css/css/style.css?v=1.00" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/dashboard_css/css/skin_color.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/dashboard_css/css/crypto_icon.css" />
        <link rel="stylesheet" href="https://allienworks.github.io/cryptocoins/webfont/cryptocoins.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/dashboard_css/css/bootstrap.css" /> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body class="hold-transition dark-skin sidebar-mini theme-warning fixed">
        <div class="wrapper"> 

            <header class="main-header">
                <div class="d-flex align-items-center logo-box justify-content-start">
                    <!-- Logo -->
                    <a href="<?php echo get_site_url(); ?>/dashboard" class="logo">
                        <!-- logo-->
                        <!--<div class="logo-mini w-30">-->
                        <!--    <span class="light-logo"><img src="<?php echo get_template_directory_uri(); ?>/dashboard_css/images/7star.png" alt="logo" /></span>-->
                            <!--<span class="dark-logo"><img src="dashboard_css/images/w-sum-logo.png" alt="logo" /></span>-->
                        <!--</div>-->
                        <div class="logo-lg">
                             <img src="<?php echo get_template_directory_uri(); ?>/dashboard_css/images/short.png" alt="logo" /> 
                            <!--<span class="light-logo"><img src="dashboard_css/images/w-sum-logo.png.png" alt="logo" /></span>-->
                            <!--<span class="dark-logo"><img src="dashboard_css/images/w-sum-logo.png.png" alt="logo" /></span>-->
                        </div>
                    </a>
                </div>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <div class="app-menu">
                        <ul class="header-megamenu nav">
                            <li class="btn-group nav-item">
                                <a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light" data-toggle="push-menu" role="button">
                                    <i data-feather="align-left">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="feather feather-align-left"
                                        >
                                            <line x1="17" y1="10" x2="3" y2="10"></line>
                                            <line x1="21" y1="6" x2="3" y2="6"></line>
                                            <line x1="21" y1="14" x2="3" y2="14"></line>
                                            <line x1="17" y1="18" x2="3" y2="18"></line>
                                        </svg>
                                    </i>
                                </a>
                            </li>
                             
                        </ul>
                    </div>

                    <div class="navbar-custom-menu r-side">
                        <ul class="nav navbar-nav"> 
                         
                            <li class="dropdown user user-menu">
                            <a href="#" class="waves-effect waves-light dropdown-toggle btn-primary-light" data-bs-toggle="dropdown" title="User">
                                    <i class="fas fa-user"></i>
                                </a>
                                <ul class="dropdown-menu animated flipInX">
                                    <li class="user-body"> 
                                        <a class="dropdown-item" href="<?php echo get_site_url(); ?>/profile"><i class="fad fa-user text-muted me-2"></i> <span>  
                          <!-- User Account-->
                          <?php if ( is_user_logged_in() ) { 
                             echo 'Username: ' . $current_user->user_login; } 
                            else { wp_loginout(); } ?></span></a>
                                        <a class="dropdown-item" href="<?php echo get_site_url(); ?>/profile"><i class="ti-user text-muted me-2"></i> Profile</a>
                                        <a class="dropdown-item" href="<?php echo get_site_url(); ?>/wallet"><i class="ti-wallet text-muted me-2"></i> My Wallet</a>
                                        <a class="dropdown-item" href="<?php echo get_site_url(); ?>/setting"><i class="ti-settings text-muted me-2"></i> Reset Password</a>
                                        <div class="dropdown-divider"></div>
                                        <!--<a class="dropdown-item" href="<?php echo get_site_url();?>/dashboard-log-in/" name="anchor_name"><i class="ti-lock text-muted me-2"></i> Logout</a>-->
                                        <a class="dropdown-item" href="<?php echo wp_logout_url($redirect); ?>"><i class="fa fa-sign-out"></i> Logout</a>
                                    </li>
                                </ul>
                            </li>
 
                        </ul>
                    </div>
                </nav>
            </header>

            <aside class="main-sidebar">
                <!-- sidebar-->
                <section class="sidebar position-relative">
                    <div class="multinav">
                        <div class="multinav-scroll" style="height: 100%;">
                            <!-- sidebar menu-->
                            <ul class="sidebar-menu" data-widget="tree">
                                <li>
                                    <a href="<?php echo get_site_url(); ?>/dashboard">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Dashboard</span> 
                                    </a>
                                </li> 
                                <li>
                                    <a href="<?php echo get_site_url(); ?>/deposit">
                                        <i class="fas fa-money-bill-alt"></i>
                                        <span>Deposit</span> 
                                    </a> 
                                </li>  
                                <li>
                                    <a href="<?php echo get_site_url(); ?>/wallet">
                                        <i class="fad fa-wallet"></i>
                                        <span>Wallet</span> 
                                    </a>
                                </li> 
                                <li>
                                    <a href="<?php echo get_site_url(); ?>/withdrawal">
                                        <i class="fad fa-exchange"></i>
                                        <span>Withdrawal</span> 
                                    </a>
                                </li> 
                                <li>
                                    <a href="<?php echo get_site_url(); ?>/transaction">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span>Transactions</span> 
                                    </a> 
                                </li> 
                                
                                  <li>
                                    <a href="<?php echo get_site_url(); ?>/setting">
                                        <i class="fad fa-cog"></i>
                                        <span>Reset Password</span> 
                                    </a> 
                                </li> 
                                  
                                <!--<li class="treeview">-->
                                <!--    <a href="#">-->
                                <!--        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>-->
                                <!--        <span>Authentication</span>-->
                                <!--        <span class="pull-right-container">-->
                                <!--            <i class="fa fa-angle-right pull-right"></i>-->
                                <!--        </span>-->
                                <!--    </a>-->
                                <!--    <ul class="treeview-menu">-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo get_site_url(); ?>/dashboard-log-in/">-->
                                <!--                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Login-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo get_site_url(); ?>/dashboard-register/">-->
                                <!--                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Register-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo get_site_url(); ?>/">-->
                                <!--                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Lockscreen-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo get_site_url(); ?>/">-->
                                <!--                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Recover password-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--    </ul>-->
                                <!--</li>-->
                            </ul>
 
                        </div>
                    </div>
                </section>
            </aside>