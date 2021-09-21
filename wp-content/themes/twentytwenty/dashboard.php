<?php /*template name: Dashboard */ 
include('head.php');
global $wpdb;
$user=get_current_user_id();
$token_price=$wpdb->get_var("SELECT price FROM `wp_token_price`  where id=1");
function eth_usdd(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=ETH",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $liveprice = curl_exec($curl);
        curl_close($curl);
        $sdg=json_decode($liveprice, true);
        return number_format($sdg['ETH'],8);
    }
    function btc_usdd(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $liveprice = curl_exec($curl);
        curl_close($curl);
        $sdg=json_decode($liveprice, true);
        return number_format($sdg['BTC'],8);
    }
    function usd_usd(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=USD",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $liveprice = curl_exec($curl);
        curl_close($curl);
        $sdg=json_decode($liveprice, true);
        return number_format($sdg['USD'],2);
    }
    $conv_usd=usd_usd();
    $conv_eth=eth_usdd();
    $conv_btc=btc_usdd();
    if(isset($_POST['butToken'])){
        $input_amount = $_POST['amount'];	
		$token=str_replace(",", "", number_format($_POST['amount']*(10**18)));
		$amount_in_euro = $input_amount*$token_price;
        $amount_in_euro = bcdiv($amount_in_euro, 1, 4);
        $input_currency = $_POST['cars'];
        $in_curr=strtolower($input_currency."_balance");
		$in_add=$input_currency."_address";
         $scl_address = trim($wpdb->get_var( "SELECT eth_address FROM wp_user_address WHERE user_id = $user" ));
        if( $input_amount==null || $input_amount=='' || $input_amount<=0 ){
            $one_error_message = 'Please enter correct amount';
        } else {
            if($amount_in_euro){
                if($input_currency=='BTC' || $input_currency=='btc' || $input_currency=='ETH' || $input_currency=='eth' || $input_currency=='LTC' || $input_currency=='ltc' || $input_currency=='USD' || $input_currency=='usd'){
					if($input_currency=='BTC' || $input_currency=='btc'){$conv=$conv_btc;}else if( $input_currency=='ETH' || $input_currency=='eth'){ $conv=$conv_eth; }else if($input_currency=='LTC' || $input_currency=='ltc'){ $conv=$conv_ltc;}else{$conv=$conv_usd;}
                    $europerbtc = ($amount_in_euro*$conv);                
                    $amount = $wpdb->get_var( "SELECT $in_curr FROM wp_user_balance WHERE user_id = $user" );
                     $amount_apo = $wpdb->get_var( "SELECT shoutcounttoken_balance FROM wp_user_balance WHERE user_id = $user" );
                    $amount_left = $amount - $europerbtc;
                    if($europerbtc<=0 || $amount_left<0){
                        $one_error_message = 'Insufficient amount entered!!!';
						echo "<script>swal('warning', 'Insufficient amount entered!!!.', 'warning');  </script>";
						
                    } else { 
                        $curl = curl_init();
						curl_setopt_array($curl, array(
						  CURLOPT_URL => "http://72.167.42.251:9005/?task=TokenTransfer&ToAddress=".$scl_address."&NoToken=".$token,
						  CURLOPT_RETURNTRANSFER => true,
						  CURLOPT_ENCODING => "",
						  CURLOPT_MAXREDIRS => 10,
						  CURLOPT_TIMEOUT => 0,
						  CURLOPT_FOLLOWLOCATION => true,
						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						  CURLOPT_CUSTOMREQUEST => "GET",
						));
						$tx_hash = curl_exec($curl);
						curl_close($curl);
						if(!empty($tx_hash)){
						    $up_apo=$amount_apo+$input_amount;
                        $wpdb->update('wp_user_balance', array($in_curr => $amount_left, 'shoutcounttoken_balance'=>$up_apo),array( 'user_id' => $user ));
                        $wpdb->insert('wp_token_buy', array('user_id'=>$user, 'amount'=>$input_amount, 'sell_currency'=>$input_currency, 'buy_currency'=>'shoutcount', 'sell_amount'=>$europerbtc, 'buy_amount'=>$input_amount, 'con_rate'=>$conv, 'txid'=>uniqid(), 'tx_hash'=>$tx_hash, 'sender_address'=>$scl_address, 'status'=>'Completed'));
                        $wpdb->insert('wp_usertomerchant', array('user_id'=>$user, 'status'=>100, 'amounti'=>$input_amount, 'txid'=>$tx_hash, 'currency'=>'shoutcount'));
                        $userTable = $wpdb->get_results( "SELECT * FROM wp_users WHERE ID = ".$user."" );
                      	$email = $userTable[0]->user_email;
                        $subject="Buy Shortcount using ".$input_currency;
                        $message = '<html>
										  <table style="width:600px; margin:auto; border: 1px solid #e1e1e1;background: linear-gradient(#fcc73c,#fde39f)!important;">
											  <thead>
												<tr>
												  <th class="header" style="padding-bottom: 20px;"><img src="" style="display:block;margin: auto;padding-top: 20px; "/>
												  </th>
												</tr>
											  </thead>
											  <tbody>
											  <tr>
												<td style="padding-left: 30px;padding-right: 30px;">
												  <h6 style="height: auto; font-size: 18px; font-weight: bold;">Thank you for your recent purchase. We are honored to have clients like you.</h6>
												</td>
											  </tr>
											  <tr>
												<td style="padding-left: 30px;padding-right: 30px;">Date/Time (UTC): <b style="">'.date("Y/m/d H:i:s").'</b></td>
											  </tr>   
											  
											  <tr><td style="padding-left: 30px;padding-right: 30px;">Transaction Status: <b style="">Completed</b></td></tr>
											  <tr><td style="padding-left: 30px;padding-right: 30px;">Amount Deposit Address: <b style="">'.$scl_address.'</b></td></tr>
											  <tr><td style="padding-left: 30px;padding-right: 30px;">Amount Deposited: <b style="">'.$input_amount.'</b></td></tr>
											  <tr><td style="padding-left: 30px;padding-right: 30px;">Currency Name: <b style="">giocoin</b></td></tr>
											  <tr><td style="padding-left: 30px;padding-right: 30px;" ></td></tr>
											  <tr><td style="padding-left: 30px;padding-right: 30px; padding-top:40px;">Thanks and Regards,</td></tr>
											  <tr><td style="padding-left: 30px;padding-right: 30px;"></td></tr>
											  <tr><td style="padding-left: 30px;padding-right: 30px;padding-bottom:40px;">Short Count Team <br>
															This is an automated message. Please do not reply.</td></tr>
											  </tbody>
											  <tfoot>
											  <tr><td class="footer" style="text-align: center; padding: 10px 0px; color:black; font-size: 18px;
											font-weight: 600;"><p>Copyright © 2021 All rights reserved by Short Count Ltd.</p></td></tr>
											 </tfoot>
										  </table></html>';
                        $headers = 'From: shoutcount <support@shortcount.com>'. "\r\n".'Content-type: text/html;';
                        mail($email, $subject, $message, $headers);
						mail('plunyach@gmail.com', $subject, $message, $headers);
                        echo "<script>swal('success', 'You successfully bought short count Tokens.', 'success');  </script>";
                    }else{
						echo "<script>swal('error', 'Something went wrong.', 'error'); setTimeout(function () { window.location = '".get_site_url()."/dashboard';}, 1000); </script>";
					}
				}
                }  else {
                    echo "<script>swal('error', 'Please select Cryptocurrency or reload your page.', 'error'); setTimeout(function () { window.location = '".get_site_url()."/dashboard';}, 1000); </script>";
                }
            } else {
                echo "<script>swal('error', 'Insufficient amount entered!!!', 'error'); setTimeout(function () { window.location = '".get_site_url()."/dashboard';}, 1000); </script>";

            }
        }
}       $balance=$wpdb->get_results("select * from wp_user_balance where user_id=".get_current_user_id());

?>
  
             <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="container-full">
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-xl-4 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Deposit</h4>
                                    </div>
                                    <div class="box-body px-0 pb-0">
                                        
                                        <div class="table-responsive">
                                            <table id="example1" class="table table-striped no-border no-margin">
                                                <thead>
                                                    <tr>
                                                        <th>Currency Name<?php global $current_user; wp_get_current_user(); ?></th>
                                                        <th>Amount</th>
                                                        <th>Status</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                      <?php 
                                                            global $wpdb;
                                                            $userid = get_current_user_id();
                                        				$currency = $wpdb->get_results("SELECT * FROM wp_usertomerchant WHERE user_id = '$userid' ");
                                        				
                                        				foreach($currency as $print) {
                                                        
                                                        ?>
                                                             
                                                        <tr>
                                                               <td><?php echo $print->currency; ?></td>
                                                               <td><?php echo $print->amounti; ?></td>
                                                               <td><?php echo "success"; }?></td>
                                                        </tr>
                                                        <td><p class="no-margin text-fade"></p></td> 
                                                    </tr>
                                                    <tr>
                                                        <td><p class="no-margin text-fade"></p></td>
                                                        <td class="no-wrap"></td> 
                                                    </tr>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">withdrawal history</h4>
                                    </div>
                                    <div class="box-body py-0 px-10">
                                        <div class="table-responsive">
                                            <table class="table table-border no-margin">
                                                <thead>
                                                    <tr> 
                                                        <th>Amount</th>
                                                        <th class="text-nowrap">Request Id</th>
                                                        <th><span>Date</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                            global $wpdb;
                                                            $userid = get_current_user_id();
                                        				    $withdraw = $wpdb->get_results("SELECT * FROM wp_withdraw WHERE user_id = '$userid' ");
                                        				    foreach($withdraw as $print) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $print->amount; ?></td>
                                                            <td><?php echo $print->request_hash; ?></td>
                                                            <td><?php echo $print->time; }?></td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8 col-12"> 
                                <!--<div class="box">-->
                                        
                                <!--</div>-->
                                <div class="box">
                                    <div class="box-body">
                                        <!-- Nav tabs -->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="d-flex justify-content-between">
                                                    <h4>Token Buy</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <form class="dash-form" method="post">
                                                <select name="cars" id="cars" >
                                                  <option>Select Currency</option>
                                                  <option value="ETH">ETH</option>
                                                  <option value="BTC">BTC</option>
                                                  <option value="USD">USD</option>
                                                  
                                                </select>
                                            </div>
                                        </div>
                                        <div class="tab-content bg-lightest p-20">
                                            <div id="navpills-1" class="tab-pane active">
                                                <div class="row">
                                                    <div class="col-xl-10 col-12"> 
                                                        
                                                            <div class="form-group row">
                                                                <label class="col-md-4 col-form-label text-nowrap pr-10">From:</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" type="text" placeholder="Please enter no of tokens"  name="amount" id="amount" />
                                                                    <!--<span class="currency">BTC</span>-->
                                                                </div>
                                                            </div>
                                                                          <div class="form-group row">
                                                                <label class="col-md-4 col-form-label text-nowrap pr-10">To:</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" type="text" value=""  name="payment" id="payment" readonly/>
                                                                    <!--<span class="currency">PLN</span>-->
                                                                </div>
                                                                <input type="text"   name="hiddenvalue" id="hiddenvalue"/ style="visibility:hidden;">
                                                            </div>
                                                           
                                                            <button type="submit" value="1" class="btn btn-success btn-block " id="butToken" name="butToken">Buy</button>
                                                            
                                                          
                                                        </form>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                
                                
                                
                                
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Token Buy History</h4>
                                    </div>
                                    <div class="box-body py-0 px-10">
                                        <div class="table-responsive">
                                            <table class="table table-border no-margin">
                                                <thead>
                                                    <tr> 
                                                        <th>Amount</th>
                                                        <th>Currency</th>
                                                        <th class="text-nowrap">Price</th>
                                                        <th><span>Date</span></th>
                                                        <th><span>Balance</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                            global $wpdb;
                                                            $userid = get_current_user_id();
                                        				$tokenbuy = $wpdb->get_results("SELECT * FROM wp_token_buy WHERE user_id = '$userid' ");
                                        				foreach($tokenbuy as $print) {
                                                        
                                                        ?>
                                                             
                                                        <tr>
                                                               <td><?php echo $print->amount; ?></td>
                                                               <td><?php echo $print->sell_currency; ?></td>
                                                               <td><?php echo $print->con_rate; ?></td>
                                                               <td><?php echo $print->time; ?></td>
                                                               <td><?php echo $print->sell_amount; }?></td>

                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div> 
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- /.content -->
                </div>
            </div>
<script>
$(document).ready(function(){
  $('#amount').bind('keyup',function(){
    var token=document.getElementById('amount').value;
    if(token>0){
        var currency=document.getElementById('cars').value;
        if(currency=='ETH'){
            var amt=token*<? echo $token_price;?>*<? echo $conv_eth;?>;
            var bal=<?php echo $balance[0]->eth_balance; ?>;
            if(bal>amt){
                document.getElementById('payment').value=amt;
                $('#butToken').attr('disabled',false);
            }else{ 
                alert("Insufficient funds"); 
                $('#butToken').attr('disabled',true);
                
            }
        }else if(currency=='BTC'){
            var amt=token*<? echo $token_price;?>*<? echo $conv_btc;?>;
            var bal=<?php echo $balance[0]->btc_balance; ?>;
            if(bal>amt){
                document.getElementById('payment').value=amt;
                $('#butToken').attr('disabled',false);
            }else{ 
                alert("Insufficient funds"); 
                $('#butToken').attr('disabled',true);
                
            }
            
        }else if(currency=='USD'){
            var amt=token*<? echo $token_price;?>*<? echo $conv_usd;?>;
            var bal=<?php echo $balance[0]->usd_balance; ?>;
            if(bal>amt){
                document.getElementById('payment').value=parseFloat(amt).toFixed(2);
                $('#butToken').attr('disabled',false);
            }else{ 
                alert("Insufficient funds"); 
                $('#butToken').attr('disabled',true);
                
            }
            
        }else{
            alert("Please Choose Cryptocurrency"); 
                $('#butToken').attr('disabled',true);
        }
    }else{
    alert("The amount is less than 0");
    $('#butToken').attr('disabled',true);
    }

  });
    $('#cars').on('change',function(){
    var token=document.getElementById('amount').value;
    if(token>0){
        var currency=document.getElementById('cars').value;
        if(currency=='ETH'){
            var amt=token*<? echo $token_price;?>*<? echo $conv_eth;?>;
            var bal=<?php echo $balance[0]->eth_balance; ?>;
            alert(bal);
            if(bal>amt){
                document.getElementById('payment').value=amt;
                $('#butToken').attr('disabled',false);
            }else{ 
                alert("Insufficient funds"); 
                $('#butToken').attr('disabled',true);
                
            }
        }else if(currency=='BTC'){
            var amt=token*<? echo $token_price;?>*<? echo $conv_btc;?>;
            var bal=<?php echo $balance[0]->btc_balance; ?>;
            if(bal>amt){
                document.getElementById('payment').value=amt;
                $('#butToken').attr('disabled',false);
            }else{ 
                alert("Insufficient funds"); 
                $('#butToken').attr('disabled',true);
                
            }
            
        }else if(currency=='USD'){
            var amt=token*<? echo $token_price;?>*<? echo $conv_usd;?>;
            var bal=<?php echo $balance[0]->usd_balance; ?>;
            if(bal>amt){
                document.getElementById('payment').value=parseFloat(amt).toFixed(2);
                $('#butToken').attr('disabled',false);
            }else{ 
                alert("Insufficient funds"); 
                $('#butToken').attr('disabled',true);
                
            }
            
        }else{
            alert("Please Choose Cryptocurrency"); 
                $('#butToken').attr('disabled',true);
        }
    }else{
    alert("The amount is less than 0");
    $('#butToken').attr('disabled',true);
    }

  });
});
</script>
            
           <?php include('foot_ter.php');?> 