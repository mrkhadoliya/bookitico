<? /** Template Name: save page **/
global $wpdb;
if(!empty($_POST['tx_id'])){
    $wpdb->insert('wp_transaction', array('token_no'=>$_POST['token'], 'eth_amount'=>$_POST['amt_eth'], 'tx_id'=>$_POST['tx_id']));
}
?>