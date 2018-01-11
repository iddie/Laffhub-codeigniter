<?php
date_default_timezone_set('Africa/Lagos');

require_once 'smpp/smppclient.class.php';
require_once 'smpp/gsmencoder.class.php';
require_once 'smpp/sockettransport.class.php';

//Construct transport and client
$transport = new SocketTransport(array('172.24.4.12'),31110);
$transport->setRecvTimeout(10000);
$smpp = new SmppClient($transport);

// Activate binary hex-output of server interaction
$smpp->debug = true;
$transport->debug = true;

// Open the connection
$Username='LAFFHUB';
$Password='S@Wc#5v';

$transport->open();
$smpp->bindTransmitter($Username,$Password);

// Optional connection specific overrides
//SmppClient::$sms_null_terminate_octetstrings = false;
//SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
//SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_SMSC_BOTH;

// Prepare message
$message = "You have successfully activated your Daily Laffhub subscription. Watch 3 videos at www.laffhub.com valid for 1day. NO DATA COST. To opt out, text OUT to 2001";
$msisdn='2348023351689';

$encodedMessage = GsmEncoder::utf8_to_gsm0338($message);
$from = new SmppAddress('LaffHub Test',SMPP::TON_ALPHANUMERIC);
$to = new SmppAddress($msisdn,SMPP::TON_INTERNATIONAL,SMPP::NPI_E164);

// Send
$smpp->sendSMS($from,$to,$encodedMessage,$tags);

// Close connection
$smpp->close();
echo 'Message Send';
?>