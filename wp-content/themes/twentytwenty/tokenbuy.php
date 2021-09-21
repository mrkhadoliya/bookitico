<?php
/* Template Name: token buy */

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://min-api.cryptocompare.com/data/price?fsym=BNB&tsyms=USD",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));
$response = curl_exec($curl);
curl_close($curl);
$usd=json_decode($response);
$token_price=$wpdb->get_var("select price from wp_token_price where id=1");



?>

<html>
  <head>
    <title>Token Buy</title>
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    <!-- Get some bootstrap default styles -->
    <link rel="shortcut icon" href="https://ramlogics.com/Bookit/wp-content/uploads/2021/05/twitter-logo-0002.png" type="image/x-icon">  
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <style>
        *{
            margin:0;
            padding:0;
            font-family: 'Poppins', sans-serif; 
        }
        .container-fluid {
            width: 100%;
        }
        .table>thead>tr>th { 
            border-bottom: 1px solid #ddd;
            font-weight: normal;
        }
        .btn-primary {
            color: #fff;
            /* background-color: #337ab7; */
            /* border-color: #2e6da4; */
            background-color: transparent;
            background-image: linear-gradient(90deg, #fbbf36 0%, #050708 100%);
            border-style: none;
            /* border-color: #FFFFFF; */
            border-radius: 5px 5px 5px 5px;
            padding: 10px 15px;
            text-transform: capitalize;
            margin: 5px 15px;
        }
        .banner{
            background-image: linear-gradient(rgb(0 0 0 / 55%), rgb(0 0 0 / 65%)),
            url('http://ramlogics.com/Bookit/wp-content/uploads/2021/05/49-494529_m.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100%;
            /* opacity: 50%; */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin: 0px;
            padding: 0px;
            left: 0px;
            top: 0px;
            z-index: -1;
        }
        div#prepare {
            display: grid;
            justify-content: center;
            text-align: center;
        }
        .logo, .logo_inner {
            margin: 1rem 1rem;
        }
        .logo img {
            height: 200px;
        }
        .logo_inner img{
            height:80px;
        }
        p, h3 {
            color: #fff;
        }
        h3 {
            background-color: #000000ad;
            padding: 1rem 0rem;
            margin: 0rem 1rem;
        }
        table.table.table-listing {
            overflow-x:auto;
        }
        table th, table td{
            white-space: nowrap;
        }
        
        th {
            color: #fff;
        }
        tbody#tr_data {
            color: #0748f3;
            background-color: #fff;
        }
        .details p {
            display: flex;
            justify-content: space-between;
        }
        p.wallet_buttons {
            margin: 3rem;
        }
        .logo_inner {
            padding-bottom: 20px;
        }
        .form-control {
            color: #000;
        }
        .details {
            margin: 0rem 1.4rem;
            background-color: #140410ab;
            padding: 1rem 1.5rem;
            border-radius: 5px;
        }
        .table_style{
            /*overflow-x:auto;*/
            padding: 1rem;
        }
        .form-control { 
            padding: 24px 12px; 
            color: #fff !important;
            background-color: #fff0 !important;
        }
        @media only screen and (max-width: 425px){
            .logo_inner img {
                height: 60px;
            }
            .details {
                margin: 0rem;
                padding: 1rem 0rem;
            }
            .details p {
                display: block ;
            }
           .logo img {
                height: 135px;
            }
            h3 { 
                margin: 0rem 0rem;
                font-size: 20px;
            }
            .table_style { 
                padding: 0rem;
            }
            .table-responsive { 
                border: 1px solid #f4ba3500;
            }
            span#network-name,
            span#selected-account {
                word-break: break-word;
                width: 200px;
                overflow: hidden;
            }
            p.wallet_buttons {
                margin: 0rem;
                display: flex;
                justify-content: space-between;
            }
            
        } 
          @media only screen and (max-width: 1024px){
         .table_style {
             overflow-x: auto;
                padding: 1rem;
            }
            h3 { 
                margin: 0rem 0rem;
                font-size: 20px;
            }
            .table_style { 
                padding: 0rem;
            }
            .table-responsive { 
                border: 1px solid #f4ba3500;
            }
            span#network-name,
            span#selected-account {
                word-break: break-word; 
                overflow: hidden;
            }
            p.wallet_buttons {
                margin: 0rem; 
            }
          }
        
  </style>
  <body>
    <!-- Construct a Bootstrap layout -->
    <div class="banner">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <div class="alert alert-danger" id="alert-error-https" style="display: none">
            <button class="btn btn-primary" id="btn-transaction">
              Connect wallet
            </button>
          </div>
          <div id="prepare">
            <div class="logo">
                <a href="https://ramlogics.com/Bookit/" target="_blank">
                    <img src="https://ramlogics.com/Bookit/wp-content/uploads/2021/05/twitter-logo-0002.png" alt="">
                </a>
            </div>
            <button class="btn btn-primary" id="btn-connect">
              Connect wallet
            </button>
          </div>
          <div id="connected" style="display: none">
            <div class="container" id="network">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0px">
                        <div class="logo_inner">
                            <a href="https://ramlogics.com/Bookit/" target="_blank">
                                <img src="https://ramlogics.com/Bookit/wp-content/uploads/2021/05/twitter-logo-0002.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
              
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0px">
                        <div class="input_fields">
                            <div class="form-group col-md-12 animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.4s" style="animation-delay: 0.4s; opacity: 1;">
                                <input type="number" required="required" placeholder="Number Of Token *" id="formGroupExampleInput" class="form-control" name="number" onkeyup="convert(<? echo ($usd->USD);?>)" />
                            </div>
                            <div class="form-group col-md-12 animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.6s" style="animation-delay: 0.6s; opacity: 1;">
                                <input type="disable" required="required" placeholder="Number of BNB *" id="formGroupExampleInput2" class="form-control" name="number" />
                            </div>
                            <div id="alert-msg" class="alert-msg text-center"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 px-0" style="padding:0px">
                        <div class="details">
                            <p>
                                <strong>Connected blockchain:</strong> <span id="network-name"></span>
                            </p>
                            <p class="Selecte_account">
                                <strong>Selected account:</strong> <span id="selected-account"></span>
                            </p>
                            <p>
                                <strong>Balance:</strong> <span id="selected-balance"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0px">
                        <p class="wallet_buttons">
                            <button class="btn btn-primary" id="btn-transaction"  onclick="transaction()">
                                Pay
                            </button>
                            <button class="btn btn-primary" id="btn-disconnect">
                              Disconnect wallet
                            </button>
                        </p>
                    </div>
                </div>
           
                <hr>
            </div>
            
            <div class="container-fluid" style="padding:0px">
                <h3>All BEP20 Transaction</h3>
                <div class="table_style table-responsive">
                    <table class="table table-listing">
                       <thead>
                            <tr class="" style="background-image: linear-gradient(90deg, #fbbf36 0%, #050708 100%)">
                                <th>Hash</th>
                                <th>Address</th>
                                <th>Transaction Type</th>
                                <th>Value</th>
                                <th>Token Name</th>
                                <!--<th>Transaction Time</th>-->
                            </tr>
                        </thead>
                        <tbody id="tr_data">
                            <script>
                                function display_history(address){
                                    var requestOptions = {
                                                          method: 'GET',
                                                          redirect: 'follow'
                                                        };
                                                        
            fetch("https://api-testnet.bscscan.com/api?module=account&action=tokentx&contractaddress=0xa9cf1ce6d4935a9922035504014993ec6e9c22bc&address="+address+"&page=1&offset=100&sort=asc&apikey=iq4GdYk7KNajDW9PRbIxqnIWP6hPmBKVv68eg6clCI4W3KnUiqt6aKlmJeNQAzzQ", requestOptions)
                                    .then(response => response.json())
                                    .then(result => {var message=document.createElement("div"); var mess=[]; if (result.status==0){ message.append(result.message);} 
                                    else{ mess=result.result;
                                        for(var i=0; i<mess.length; i++){
                                            let element = document.createElement('tr');
                                            element.innerHTML=("<td class='text-warning hover-primary'><a href='https://testnet.bscscan.com/tx/"+mess[i].hash+"' target='_blank'>See Hash</td><td>"+mess[i].from+"</td><td>"+mess[i].to+"</td><td>"+(mess[i].value/1000000000000000000)+"</td><td>"+mess[i].tokenName+"</td>")
                                            message.append(element);
                                        }
                                    }
                                    document.getElementById('tr_data').innerHTML=message.innerHTML; 
                                        
                                    })
                                    .catch(error => console.log('error', error));
                                }
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>

          <br>


        </div>
      </div>
    </div>
    </div>
    <!-- We use simple <template> templating for the example -->
    <div id="templates" style="display: none">
      <template id="template-balance">
        <tr>
          <th class="address"></th>
          <td class="balance"></td>
        </tr>
      </template>
    </div>
    <script type="text/javascript" src="https://unpkg.com/web3@1.2.11/dist/web3.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/web3modal@1.9.0/dist/index.js"></script>
    <script type="text/javascript" src="https://unpkg.com/evm-chains@0.2.0/dist/umd/index.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/@walletconnect/web3-provider@1.2.1/dist/umd/index.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/fortmatic@2.0.6/dist/fortmatic.js"></script>

    <!-- This is our example code -->
    <!--<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/example.js"></script>-->
    <script>
        "use strict";
const Web3Modal = window.Web3Modal.default;
const WalletConnectProvider = window.WalletConnectProvider.default;
const Fortmatic = window.Fortmatic;
const evmChains = window.evmChains;
const Binance= window.BinanceChain;
let web3Modal
let provider;
let selectedAccount;

function init() {
  if(location.protocol !== 'https:') {
    const alert = document.querySelector("#alert-error-https");
    alert.style.display = "block";
    document.querySelector("#btn-connect").setAttribute("disabled", "disabled")
    return;
  }
  const providerOptions = {
    walletconnect: {
      package: WalletConnectProvider,
      options: {
        infuraId: "4e990aac9bc9418b8112eb1ed524cf91",
        rpc: {
            56: "https://bsc-dataseed.binance.org/",
        }
      }
    }
  };

  web3Modal = new Web3Modal({
    cacheProvider: false, // optional
    providerOptions, // required
    disableInjectedProvider: false, // optional. For MetaMask / Brave / Opera.
  });
}
 async function transaction(){
     const web3 = new Web3(provider);
     const chainId = await web3.eth.getChainId(); 
     if(chainId==97 || chainId==3){
         const accounts = await web3.eth.getAccounts();
         const paymentAddress = '0x92D633d2b88a49019916DDb1d0CEAC57503E4A3D';
         const amountEth = Number(document.getElementById('formGroupExampleInput2').value);
         const tokens= Number(document.getElementById('formGroupExampleInput').value);
         selectedAccount = accounts[0];
         web3.eth.sendTransaction({
                      to: paymentAddress,
                      from:selectedAccount,
                      value: web3.utils.toWei(amountEth.toString(), 'ether')
                    }, (err, transactionId) => {
                      if  (err) {
                        swal('warning','Payment failed','warning');
                      } else {
                        $.post("https://ramlogics.com/Bookit/save/",
                        {
                          tx_id: transactionId,
                          token: tokens,
                          amt_eth: amountEth
                        },
                        function(data,status){
                          swal('success','Payment successful: '+transactionId,'success');
                        });
                        }
                    });
     }else{
         swal('warning', 'Please connect with Binance Smart chain', 'warning');
     }           
 }
async function fetchAccountData() {
  const web3 = new Web3(provider);
  const chainId = await web3.eth.getChainId();
  const chainData = evmChains.getChain(chainId);
  document.querySelector("#network-name").textContent = chainData.name;
  const accounts = await web3.eth.getAccounts();
  selectedAccount = accounts[0];
  document.querySelector("#selected-account").textContent = selectedAccount;
  const balance = await web3.eth.getBalance(selectedAccount);
  const ethBalance = web3.utils.fromWei(balance, "ether");
  const humanFriendlyBalance = parseFloat(ethBalance).toFixed(4);
  document.querySelector("#selected-balance").textContent = humanFriendlyBalance;
  setInterval(function(){display_history(selectedAccount)}, 10000);
  document.querySelector("#prepare").style.display = "none";
  document.querySelector("#connected").style.display = "block";
}
async function refreshAccountData() {
  document.querySelector("#connected").style.display = "none";
  document.querySelector("#prepare").style.display = "block";
  document.querySelector("#btn-connect").setAttribute("disabled", "disabled")
  await fetchAccountData(provider);
  document.querySelector("#btn-connect").removeAttribute("disabled")
}

async function onConnect() {
  try {
    provider = await web3Modal.connect();
  } catch(e) {
    console.log("Could not get a wallet connection", e);
    return;
  }
  provider.on("accountsChanged", (accounts) => {
    fetchAccountData();
  });
  provider.on("chainChanged", (chainId) => {
    fetchAccountData();
  });
  provider.on("networkChanged", (networkId) => {
    fetchAccountData();
  });
  await refreshAccountData();
}
async function onDisconnect() {
  if(provider.close) {
    await provider.close();
    await web3Modal.clearCachedProvider();
    provider = null;
  }
  selectedAccount = null;
  document.querySelector("#prepare").style.display = "block";
  document.querySelector("#connected").style.display = "none";
}
function convert(usd){
      var no_token=Number(document.getElementById('formGroupExampleInput').value);
      var inputtxt=document.getElementById('formGroupExampleInput');
      var numbers = /^[0-9]+/;
      if(inputtxt.value.match(numbers))
      { var no_of_eth=((no_token*Number("<? echo $token_price;?>"))/usd);
      document.getElementById('formGroupExampleInput2').value=parseFloat(no_of_eth).toFixed(5);
      document.getElementById("btn-transaction").disabled = false;
      }else{
         document.getElementById('formGroupExampleInput2').value=''; 
         document.getElementById("btn-transaction").disabled = true;
      }
  }
window.addEventListener('load', async () => {
  init();
  document.querySelector("#btn-connect").addEventListener("click", onConnect);
  document.querySelector("#btn-disconnect").addEventListener("click", onDisconnect);
});
    </script>
  </body>
</html>