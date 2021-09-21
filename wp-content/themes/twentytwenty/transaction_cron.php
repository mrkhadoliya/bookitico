<?php /* Template Name: Transaction cron */
$connec = mysqli_connect("localhost", "ramlogic_bookit", "E}bPM,&wQK!H", "ramlogic_bookit");
if (!$connec) {
        die("Connection failed: " . mysqli_connect_error());
    }
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api-testnet.bscscan.com/api?module=account&action=txlist&address=0x92D633d2b88a49019916DDb1d0CEAC57503E4A3D&startblock=0&endblock=99999999&sort=asc&apikey=iq4GdYk7KNajDW9PRbIxqnIWP6hPmBKVv68eg6clCI4W3KnUiqt6aKlmJeNQAzzQ",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Cookie: __cfduid=d5c0f0a5b0e3d8d73730de26b61810cda1603961939"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
$response =(json_decode($response));
$a[]=$response->result;
$i=0;
$eth_address='0x92D633d2b88a49019916DDb1d0CEAC57503E4A3D';
foreach($response->result as $value){
    if($value->value!=0){
        if($value->to==$eth_address || $value->to==strtolower($eth_address)) {
            $ammt=($value->value/(10**18));
            $trans_status=$connec->query("select * from wp_transaction where `tx_id`='".$value->hash."' AND `eth_amount`='".$ammt."' AND 'tx_status'=0");
            $status=$trans_status->fetch_array();
            if($status['tx_status']==0){
                $connec->query("UPDATE `wp_transaction` SET `tx_status`=1,`send_address`='".$value->from."' WHERE `tx_id`='".$value->hash."' AND `eth_amount`='".$ammt."'");
            }
        } sleep(2);
            $token_send=$connec->query("select * from wp_transaction where `tx_id`='".$value->hash."' AND `eth_amount`='".$ammt."' AND tx_status=1");
            $send_status=$token_send->fetch_array();
                if($send_status['tx_status']==1 && $send_status['token_transfer_status']==0){
                    echo $send_status['id']."<br>";
                    $token=str_replace(",", "", number_format($send_status['token_no']*(10**18)));
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "http://localhost:9008/?task=TokenTransfer&ToAddress=".$send_status['send_address']."&NoToken=".$token,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "GET",
                    ));
                    $response = curl_exec($curl);
                    echo $response;
                    curl_close($curl);
                    if(!empty($response)){
                        $connec->query("UPDATE `wp_transaction` SET `token_transfer_status`=1,`send_address`='".$value->from."', `send_tx_id`='".$response."' WHERE `tx_id`='".$value->hash."' AND `eth_amount`='".$ammt."'");
                    }
                }
        }
    }