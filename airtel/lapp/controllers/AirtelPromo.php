<?php

/**
 * Created by PhpStorm.
 * User: DanDevOps
 * Date: 2017/11/02
 * Time: 11:25 AM
 */
class AirtelPromo extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('getdata_model');
    }


    public function SubscribeUser()
    {
        $network = '';
        $msisdn = '';
        $plan = '';
        $amount = '';
        $subscribe_date = '';
        $exp_date = '';
        $videos_cnt_to_watch = '';
        $duration = '';
        $email = '';
        $subscriptionId = '';

        if ($this->input->post('email')) $email = trim($this->input->post('email'));
        if ($this->input->post('network')) $network = trim($this->input->post('network'));
        if ($this->input->post('msisdn')) $msisdn = $this->input->post('msisdn');
        if ($this->input->post('plan')) $plan = trim($this->input->post('plan'));
        if ($this->input->post('duration')) $duration = trim($this->input->post('duration'));
        if ($this->input->post('autobilling')) $autobilling = trim($this->input->post('autobilling'));
        if ($this->input->post('amount')) $amount = trim($this->input->post('amount'));
        if ($this->input->post('subscribe_date')) $subscribe_date = $this->input->post('subscribe_date');
        if ($this->input->post('exp_date')) $exp_date = trim($this->input->post('exp_date'));
        if ($this->input->post('subscriptionId')) $subscriptionId = trim($this->input->post('subscriptionId'));
        if ($this->input->post('videos_cnt_to_watch')) $videos_cnt_to_watch = trim($this->input->post('videos_cnt_to_watch'));
        $cpTid = date('YmdHis') . '_' . $msisdn;

        if (!$autobilling) $autobilling = '0';

        $tm = date('H:i:s');

        $subscribe_date .= ' ' . $tm;
        $exp_date .= ' ' . $tm;

        $Msg = '';
        $dt = date('Y-m-d H:i');

        #Check if number is blacklisted
        $rt = $this->getdata_model->CheckForBlackList($network, $msisdn);

        if ($rt == true) {

            $ret = 'We are sorry, the phone number, <b>' . $msisdn . '</b>, cannot subscribe to this service.';

            $Msg = "Phone number, " . $msisdn . ", cannot be subscribed. It has been blacklisted.";

        } else {
            //Check if record exists
            $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "') AND (TRIM(plan)='" . $this->db->escape_str($plan) . "')";

            $query = $this->db->query($sql);

            $ret = '';
            $watched = 0;
            $maxwatch = 0;
            $expdt = '';
            $activeplan = '';
            $flag = false;
            $ex = '';
            $sub_status='';

            if ($query->num_rows() > 0)#There is active subscription
            {
                $row = $query->row();

                if ($row->exp_date) {
                    $expdt = date('d M Y @ H:i', strtotime($row->exp_date));
                    $ex = date('Y-m-d H:i', strtotime($row->exp_date));
                }

                if ($row->plan) $activeplan = trim($row->plan);
                if ($row->videos_cnt_watched) $watched = intval($row->videos_cnt_watched);
                if ($row->videos_cnt_to_watch) $maxwatch = $row->videos_cnt_to_watch;
                if ($row->subscriptionstatus) $sub_status = $row->subscriptionstatus;

                #Check if Airtel Promo plan subscription has expired
                if ($dt >= $ex)#Expired
                {
                    $flag = true;

                }elseif($sub_status == 0) {

                    $flag = true;

                } else {

                    $flag = false;
                }
            } else {

                $flag = true;
            }


            if ($flag == false) {

                $ret = "You still have an active promo plan to enjoy 15 days of comedy streaming which will expire on <b>" . $expdt . "</b>.";

            }else{

                $sql = "SELECT * FROM freetrials WHERE (TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "') AND (TRIM(promos)='" . $this->db->escape_str($plan) . "')";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0) #Customer has utilized the Airtel Promo plan
                {
                    $ret = 'Your 15 days free access to LaffHub has expired. Renew your plan to keep enjoying comedy clips';

                } else {

                    $new = $this->getdata_model->IsNewSubscriber($msisdn, $network);

                    if ($new == 1) {
                        #### Update Into new_subscriptions
                        $sql = "SELECT msisdn FROM new_subscriptions WHERE (TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                        $query = $this->db->query($sql);

                        if ($query->num_rows() > 0) {

                            $this->db->trans_start();

                            $dat = array(
                                'plan' => $this->db->escape_str($plan),
                                'subscriptiondate' => $this->db->escape_str($subscribe_date)
                            );

                            $where = "(TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                            $this->db->where($where);
                            $this->db->update('new_subscriptions', $dat);

                            $this->db->trans_complete();
                        } else {

                            $this->db->trans_start();

                            $dat = array(
                                'network' => $this->db->escape_str($network),
                                'msisdn' => $this->db->escape_str($msisdn),
                                'plan' => $this->db->escape_str($plan),
                                'email' => '',
                                'subscriptiondate' => $this->db->escape_str($subscribe_date),
                            );

                            $this->db->insert('new_subscriptions', $dat);

                            $this->db->trans_complete();
                        }
                    }

                    ### Insert freetrials table
                    $this->db->trans_start();

                    $dat = array(
                        'network' => $this->db->escape_str($network),
                        'msisdn' => $msisdn,
                        'email' => $this->db->escape_str($email),
                        'triedfree' => 1,
                        'trialdate' => $subscribe_date,
                        'trialexpire' => $this->db->escape_str($exp_date),
                        'trialdays' => $this->db->escape_str($duration),
                        'promos' => $this->db->escape_str($plan)
                    );

                    $sql = "SELECT * FROM freetrials WHERE (TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "') AND (TRIM(promos)='" . $this->db->escape_str($plan) . "')";

                    $query = $this->db->query($sql);

                    if ($query->num_rows() > 0)#There is active subscription
                    {
                        $where = "(TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "') AND (TRIM(promos)='" . $this->db->escape_str($plan) . "')";
                        $this->db->where($where);
                        $this->db->update('freetrials', $dat);

                    } else {

                        $this->db->insert('freetrials', $dat);
                    }
                    $this->db->trans_complete();


                    ########################### DAILY REPORT FUNCTIONS #######################

                    #Save Subscription Record
                    $this->db->trans_start();

                    $dat = array(
                        'subscriptionId' => $subscriptionId,
                        'email' => $this->db->escape_str($email),
                        'network' => $this->db->escape_str($network),
                        'msisdn' => $msisdn,
                        'plan' => $this->db->escape_str($plan),
                        'duration' => $this->db->escape_str($duration),
                        'amount' => $this->db->escape_str($amount),
                        'autobilling' => $this->db->escape_str($autobilling),
                        'subscribe_date' => $subscribe_date,
                        'exp_date' => $this->db->escape_str($exp_date),
                        'videos_cnt_watched' => 0,
                        'videos_cnt_to_watch' => $this->db->escape_str($videos_cnt_to_watch),
                        'subscriptionstatus' => 1
                    );

                    $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "')";

                    $query = $this->db->query($sql);

                    if ($query->num_rows() > 0)#There is active subscription
                    {
                        $row = $query->row();

                        if ($row->subscriptionstatus == 0 or $row->subscriptionstatus == 1) {

                            $where = "(TRIM(network)='" . $this->db->escape_str($network) . "') AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "')";
                            $this->db->where($where);
                            $this->db->update('subscriptions', $dat);
                        }
                    } else {
                        $this->db->insert('subscriptions', $dat);
                    }
                    $this->db->trans_complete();

                    $Msg = "Promo activation was successful. Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Email => " . $email . "; Service Plan => " . $plan . "; Duration => " . $duration . "; Amount => " . $amount . "; Subscription Date => " . $subscribe_date . "; Expiry Date => " . $exp_date;


                    #Create record in watchlists table
                    $this->db->trans_start();
                    $dat = array('subscriptionId' => $subscriptionId, 'videolist' => '');
                    $this->db->insert('watchlists', $dat);
                    $this->db->trans_complete();

                    ## Send activation message
                    $message = 'Dear customer, Your 15 Days Laffhub Free Trial plan has been activated (No Auto Renewal). Visit http://laffhub.com to enjoy our bouquet of funny videos FREE.';

                    $result_msg = $this->getdata_model->SendAirtelSubScriptionMessage($msisdn, $message);

                    if (strtoupper(trim($result_msg['Status'])) == 'OK') {

                        $ret = 'OK';
                    }


                    #Save to subscription_history table
                    $this->db->trans_start();

                    $dat = array(
                        'network' => $this->db->escape_str($network),
                        'msisdn' => $this->db->escape_str($msisdn),
                        'plan' => $this->db->escape_str($plan),
                        'email' => $this->db->escape_str($email),
                        'amount' => $this->db->escape_str($amount),
                        'subscribe_date' => $this->db->escape_str($subscribe_date),
                        'subscription_expiredate' => $this->db->escape_str($exp_date),
                        'transid' => 'Airtel Promo Plan',
                        'cptransid' => $this->db->escape_str($cpTid),
                        'sentmessage' => $this->db->escape_str($message),
                        'subscription_status' => 1,
                        'subscription_message' => 'Airtel Promo Activated',
                    );

                    $this->db->insert('subscription_history', $dat);

                    $this->db->trans_complete();

                    #Reset SESSION variables
                    $sdt = date('F d, Y', strtotime($subscribe_date));
                    $edt = date('F d, Y', strtotime($exp_date));

                    $_SESSION['subscribe_date'] = $sdt;
                    $_SESSION['exp_date'] = $edt;
                    $_SESSION['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';

                    $this->getdata_model->LogDetails($network . '(' . $msisdn . ')', $Msg, $msisdn, $_SESSION['LogIn'], $_SESSION['RemoteIP'], $_SESSION['RemoteHost'], 'SUBSCRIBED USER', $_SESSION['LogID']);


                }
            }
        }
        echo $ret;
    }


    public function DeletePaymentLog()
    {//{email, phone, gateway,subscriptionId}
        $email=''; $gateway=''; $phone=''; $subscriptionId='';

        if ($this->input->post('email')) $email = trim($this->input->post('email'));
        if ($this->input->post('gateway')) $gateway = trim($this->input->post('gateway'));
        if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
        if ($this->input->post('subscriptionId')) $subscriptionId = trim($this->input->post('subscriptionId'));

        $sql = "SELECT * FROM payment_log WHERE ((TRIM(phone)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."')) AND (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0 )
        {
            #Log Transaction
            #***************Save Response in payment_log table***********************
            $this->db->trans_start();

            $where = "(TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."') AND ((TRIM(phone)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."'))";

            $this->db->where($where);
            $this->db->delete('payment_log');

            $this->db->trans_complete();

        }

        echo 'OK';
    }#End DeletePaymentLog

    public function LogTrans()
    {
        $amount=''; $txn_ref=''; $email=''; $gateway=''; $customername='';
        $description=''; $phone=''; $subscribe_date=''; $exp_date='';
        $videos_cnt_to_watch=''; $subscriptionId=''; $plan=''; $network=''; $duration='';

        if ($this->input->post('network')) $network = trim($this->input->post('network'));
        if ($this->input->post('plan')) $plan = trim($this->input->post('plan'));
        if ($this->input->post('duration')) $duration = trim($this->input->post('duration'));
        if ($this->input->post('amount')) $amount = trim($this->input->post('amount'));
        if ($this->input->post('email')) $email = trim($this->input->post('email'));
        if ($this->input->post('gateway')) $gateway = trim($this->input->post('gateway'));
        if ($this->input->post('description')) $description = trim($this->input->post('description'));
        if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
        if ($this->input->post('subscribe_date')) $subscribe_date = trim($this->input->post('subscribe_date'));
        if ($this->input->post('exp_date')) $exp_date = trim($this->input->post('exp_date'));
        if ($this->input->post('subscriptionId')) $subscriptionId = trim($this->input->post('subscriptionId'));
        if ($this->input->post('videos_cnt_to_watch')) $videos_cnt_to_watch = trim($this->input->post('videos_cnt_to_watch'));

        if (floatval($amount)>0) $amount=floatval(str_replace(',','',$amount))/100;

        $dt=date('Y-m-d H:i');

        $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (subscriptionstatus=1) AND ((TRIM(msisdn)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."'))";

        $query = $this->db->query($sql);

        $ret=''; $watched=0; $maxwatch=0; $expdt=''; $activeplan=''; $flag=false; $ex='';
        #$subscriptionId=$this->getdata_model->GetGenericNumericNextID('subscriptions','subscriptionId',1);

        if ($query->num_rows() > 0 )#There is active subscription
        {
            $row = $query->row();

            if ($row->exp_date)
            {
                $expdt=date('d M Y @ H:i',strtotime($row->exp_date));
                $ex=date('Y-m-d H:i',strtotime($row->exp_date));
            }

            if ($row->plan) $activeplan=trim($row->plan);
            if ($row->videos_cnt_watched) $watched=intval($row->videos_cnt_watched);
            if ($row->videos_cnt_to_watch) $maxwatch=$row->videos_cnt_to_watch;

            #Check if subscription has expired
            if ($dt >= $ex)#Expired
            {
                $flag=true;

            }elseif (($watched >= $maxwatch) and (strtolower(trim($maxwatch)) <> 'unlimited'))#Exhausted videos
            {
                $flag=true;
            }else
            {
                $flag=false;
            }
        }else
        {
            $flag=true;
        }

        if ($flag==false)
        {
            $ret="You cannot subscribe right now. You currently have an active subscription on this service which will expire on <b>".$expdt."</b>";

            $Msg="Subscription was not successful. Subscriber has an active subscription running. Current Subscription Details: Network => ".$network."; MSISDN => ".$msisdn."; Email => ".$email."; Service Plan => ".$activeplan."; Expiry Date => ".$expdt;
        }else
        {
            $sql = "SELECT * FROM payment_log WHERE ((TRIM(phone)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."')) AND (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";

            $query = $this->db->query($sql);

            if ($query->num_rows() == 0 )#Insert
            {
                #Log Transaction
                #***************Save Response in payment_log table***********************
                $this->db->trans_start();

                $dat=array(
                    'phone' => $this->db->escape_str($phone),
                    'email' => $this->db->escape_str($email),
                    'gateway' => $this->db->escape_str($gateway),
                    'subscriptionId' => $this->db->escape_str($subscriptionId),
                    'ActualAmount' => $this->db->escape_str($amount),
                    'payment_reference' => '',
                    'payment_description' => $this->db->escape_str($description),
                    'description' => $this->db->escape_str('Payment For Subscription: '.strtoupper($description).'.'),
                    'response_code' => '',
                    'response_msg' => '',
                    'trans_status' => 'Pending',
                    'insert_date' => date('Y-m-d H:i:s')
                );

                $this->db->insert('payment_log', $dat);

                $this->db->trans_complete();

                #**********************************************************************************

                $nm=$customername; $Msg='';

                if ($this->db->trans_status() === FALSE)
                {
                    $Msg="Could Not Log Subscription With Code ".$txn_ref.".";
                    $ret = 'Could Not Log Subscription With Reference Number <b>'.$txn_ref.'</b>. Please Restart The Subscription Process.';
                }else
                {
                    $Msg="Subscription With Reference Number ".$txn_ref." Was Logged Successfully.";

                    $ret ="OK";
                }

                $remote_ip=$_SERVER['REMOTE_ADDR'];
                $remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $this->getdata_model->LogDetails($nm,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'LOGGED SUBSCRIPTION',$_SESSION['LogID']);
            }else
            {
                $ret ="OK";
            }
        }

        echo $ret;
    }


    public function VerifyTransaction()
    {//
        $email=''; $amount=''; $subscriptionId=''; $gateway=''; $phone=''; $network=''; $plan='';
        $duration=''; $subscribe_date=''; $videos_cnt_to_watch='';

        if ($this->input->post('network')) $network = $this->input->post('network');
        if ($this->input->post('plan')) $plan = $this->input->post('plan');
        if ($this->input->post('duration')) $duration = trim($this->input->post('duration'));
        if ($this->input->post('amount')) $amount = $this->input->post('amount');#in kobo
        if ($this->input->post('email')) $email=$this->input->post('email');
        if ($this->input->post('phone')) $phone=$this->input->post('phone');
        if ($this->input->post('gateway')) $gateway = $this->input->post('gateway');
        if ($this->input->post('subscriptionId')) $subscriptionId= $this->input->post('subscriptionId');
        if ($this->input->post('subscribe_date')) $subscribe_date= $this->input->post('subscribe_date');
        if ($this->input->post('exp_date')) $exp_date= $this->input->post('exp_date');
        if ($this->input->post('videos_cnt_to_watch')) $videos_cnt_to_watch = trim($this->input->post('videos_cnt_to_watch'));

        $payment_date=date('Y-m-d H:i:s');

        $tm=date('H:i:s');

        $subscribe_date .= ' '.$tm;
        $exp_date .= ' '.$tm;

        #Get PayStack Settings
        $PayStackSettings = $this->getdata_model->GetPaystackSettings();

        if (count($PayStackSettings)>0)
        {
            foreach($PayStackSettings as $row):
                if ($row->SecretKey) $SecretKey=$row->SecretKey;
                if ($row->verify_url) $VerifyUrl=$row->verify_url;

                break;
            endforeach;
        }

        if ($VerifyUrl[strlen($VerifyUrl)-1]=='/')
        {
            $url = $VerifyUrl.$subscriptionId;
        }else
        {
            $url = $VerifyUrl.'/'.$subscriptionId;
        }

        //Verify Transaction
        $result = array();
        //The parameter after verify/ is the transaction reference to be verified
        #'https://api.paystack.co/transaction/verify/'.$TxnRef;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (strtolower(trim($_SERVER['HTTP_HOST']))=='localhost')
        {
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
        }else
        {
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__."/cacert.pem");
        }


        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$SecretKey]);

        $request = curl_exec($ch);

        curl_close($ch);

        if($request)
        {
            $result = json_decode($request, true);

            if (!$result['status']) $result['status']='0';

            $response_code=$result['status'];
            $VerificationStatus=$result['status'];
            $VerificationMessage=$result['message'];
            $VerifiedAmount=$result['data']['amount']/100;
            $Currency=$result['data']['currency'];
            $TransactionStatus=$result['data']['status'];
            $GatewayResponse=$result['data']['gateway_response'];
            $Description=$result['data']['metadata']['custom_fields'][1]['value'];
            $PaymentDate=$result['data']['transaction_date'];
            $CardLast4Digits=$result['data']['authorization']['last4'];
            $TransactingBank=$result['data']['authorization']['bank'];
            $CardType=strtoupper($result['data']['authorization']['card_type']);

            if ($PaymentDate) $PaymentDate=str_replace('T',' ',substr($PaymentDate,0,19));

            $TransAmount = number_format(floatval(str_replace(',','',$VerifiedAmount)),2);

            $trans_status=ucwords(strtolower(trim($TransactionStatus)));

            $this->PaymentDetails='Subscriber Email='.$email.'; Subscriber Phone='.$phone.'; Card Type='.$CardType.'; Card Last 4 Digits='.$CardLast4Digits.'; Transacting Bank='.$TransactingBank.'; Amount='.$TransAmount.'; Subscription ID='.$subscriptionId.'; Response Description='.$TransactionStatus.'; Subscription Date='.$PaymentDate;

            $ReportDetails='Subscriber&nbsp;Email='.$email.'; Subscriber&nbsp;Phone='.$phone.'; Subscription ID='.$subscriptionId.'; Card Type='.$CardType.'; Card Last 4 Digits='.$CardLast4Digits.'; Subscription Date='.$PaymentDate;

            #***************Update payment_log table***********************
            $sql="SELECT * FROM payment_log WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."') AND ((TRIM(phone)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."'))";

            $query = $this->db->query($sql);

            if ( $query->num_rows()> 0 )
            {
                $this->db->trans_start();

                $dat=array(
                    'amount' => $this->db->escape_str(str_replace(',','',$TransAmount)),
                    'payment_date' => $this->db->escape_str($PaymentDate),
                    'payment_description' => $this->db->escape_str($Description),
                    'response_code' => $this->db->escape_str($response_code),
                    'response_msg' => $this->db->escape_str($trans_status),
                    'trans_status' => $trans_status
                );

                $where = "(TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."') AND ((TRIM(phone)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."'))";
                $this->db->where($where);
                $this->db->update('payment_log', $dat);

                $this->db->trans_complete();
            }

            if (trim(strtolower($trans_status)) == "success") $paid='1'; else $paid='0';

            ######################
            #Update subscription table - 'amountpaid' => 1,
            $this->db->trans_start();

            $dat=array(
                'email' => $this->db->escape_str($email),
                'subscriptionId' => $this->db->escape_str($subscriptionId),
                'network' => 'WIFI',
                'msisdn' => $this->db->escape_str($phone),
                'plan' => $this->db->escape_str($plan),
                'duration' => $this->db->escape_str($duration),
                'amount' => $this->db->escape_str(str_replace(',','',$TransAmount)),
                'paymentdate' => $this->db->escape_str($PaymentDate)
            );

            $this->db->insert('accounts', $dat);

            $this->db->trans_complete();

            #Save Subscription Record
            $this->db->trans_start();

            $dat=array(
                'subscriptionId' => $subscriptionId,
                'email' => $this->db->escape_str($email),
                'network' => 'WIFI',
                'msisdn' => $this->db->escape_str($phone),
                'plan' => $this->db->escape_str($plan),
                'duration' => $this->db->escape_str($duration),
                'amount' => $this->db->escape_str(str_replace(',','',$TransAmount)),
                'autobilling' => 1,
                'subscribe_date' => $this->db->escape_str($subscribe_date),
                'exp_date' => $this->db->escape_str($exp_date),
                'videos_cnt_watched' => 0,
                'videos_cnt_to_watch' => $this->db->escape_str($videos_cnt_to_watch),
                'subscriptionstatus' => 1
            );


            $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='WIFI') AND ((TRIM(msisdn)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."'))";
            $query = $this->db->query($sql);

            if ($query->num_rows() > 0 )#There is active subscription
            {
                $row = $query->row();

                if ($row->subscriptionstatus==0)
                {

                    $where = "(TRIM(network)='WIFI') AND ((TRIM(msisdn)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."'))";
                    $this->db->where($where);
                    $this->db->update('subscriptions', $dat);
                }
            }else
            {
                $this->db->insert('subscriptions', $dat);
            }


            $this->db->trans_complete();

            $Msg="Subscription was successful. Details: Network => WIFI; MSISDN => ".$phone."; Email => ".$email."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$TransAmount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;

            #Create record in watchlists table
            $this->db->trans_start();
            $dat=array('subscriptionId' => $subscriptionId, 'videolist' => '');
            $this->db->insert('watchlists', $dat);
            $this->db->trans_complete();

            $ret='OK';

            $Msg = "Subscriber with email ".$email." successfully subscribed for LaffHub with Id ".$subscriptionId.".";

            $cur=''; $emstatus='';

            if ($trans_status=='Success')
            {
                $emstatus=' was <b>successful</b>';
                $altemstatus=' was successful';
            }else
            {
                $emstatus=' <b>'.$trans_status.'</b>.';
                $altemstatus=$$trans_status.'.';
            }


            if (trim(strtoupper($Currency))=='NGN') $cur='&#8358;'; else $cur='&#36;';

            $Currency=$cur;

            $img=base_url()."images/emaillogo.png";

            $emailmsg='
			<img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" />
			<br><br>
			Dear Subscriber,<br><br>
			
			Your payment of <b>'.$cur.' '.$TransAmount.' ('.strtolower(str_replace('.','',MoneyInWords(str_replace(',','',$TransAmount)))).')</b> to <b>LaffHub</b> as subscription fee for '.$plan.' plan with subscription Id <b>'.$subscriptionId.'</b> via <b>'.$gateway.'</b> '.$emstatus.'.<br><br>
			
			Contact us <a href="mailto:support@laffhub.com">support@laffhub.com</a> for any inquiries.<br><br>
			
			LaffHub Team
			';

            $altemailmsg='
			Dear Subscriber, 
			
			Your payment of '.$TransAmount.' ('.strtolower(str_replace('.','',MoneyInWords(str_replace(',','',$TransAmount)))).') to LaffHub as subscription fee for '.$plan.' plan with subscription Id '.$subscriptionId.' via '.$gateway.' '.$altemstatus.'. 
			
			Contact us support@laffhub.com for any inquiries. 
			
			LaffHub Team
			';

            $ret=$this->getdata_model->SendEmail('admin@laffhub.com',$email,$plan.' Plan Subscription Fee','',$emailmsg,$altemailmsg,$email);

            $ret = 'OK';
        }else
        {
            $file = fopen('curl_error.txt',"a"); fwrite($file, "\n\SUBSCRIPTION FEE CURL ERROR FAILED:\n".$curlerror); fclose($file);

            $ret = $curlerror;
        }

        $remote_ip=$_SERVER['REMOTE_ADDR'];
        $remote_host=gethostbyaddr($remote_ip);

        $this->getdata_model->LogDetails($email,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'SUBSCRIPTION FEE PAYMENT',$_SESSION['LogID']);

        #$this->index();

        echo $ret;
    }

    public function LoadPlanDetails()
    {
        $network=''; $plan='';

        if ($this->input->post('network')) $network = $this->input->post('network');
        if ($this->input->post('plan')) $plan = $this->input->post('plan');

        $sql = "SELECT (SELECT price FROM prices WHERE (TRIM(prices.network)=TRIM(plans.network)) AND (TRIM(prices.plan)=TRIM(plans.plan))) AS amount,plans.* FROM plans WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(plan)='".$this->db->escape_str($plan)."')";

        $query = $this->db->query($sql);

        $response=$query->result();

        echo json_encode($response);
    }#End Of LoadMessages functions

    public function Landingpage()
    {
        $this->load->library('session');

        $data['Network']='';
        $data['Phone']='';

        if (($_SERVER['HTTP_HOST'] == 'localhost') or ($_SERVER['HTTP_HOST'] == 'localhost:8888'))  {

            $data['Network']=getenv('AIRTEL_NETWORK');
            $data['Phone']=getenv('AIRTEL_MSISDN');

        }else{

            $data['Network']=$this->getdata_model->GetNetwork();
            $data['Phone']=$this->getdata_model->GetMSISDN();
        }

        $this->getdata_model->LoadSubscriberSession($data['Phone']);

        if ($_SESSION['subscriber_email']) $data['subscriber_email']=$_SESSION['subscriber_email'];
        if ($_SESSION['subscriber_name']) $data['subscriber_name'] = $_SESSION['subscriber_name'];
        if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
        if ($_SESSION['subscriber_status']) $data['subscriber_status'] = $_SESSION['subscriber_status'];

        if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
        if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
        if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
        if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
        if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];

        $data['subscribe_date'] = ''; $data['exp_date'] = ''; $data['subscriptionstatus'] = '';
        $data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';

        $_SESSION['Network']=$data['Network'];
        $_SESSION['Phone']=$data['Phone'];

        $result=$this->getdata_model->GetSubscriptionDate($data['subscriber_email'],$data['Phone']);

        if (is_array($result))
        {
            $td=date('Y-m-d H:i:s');

            foreach($result as $row)
            {
                if ($row->subscribe_date) $dt = date('F d, Y',strtotime($row->subscribe_date));

                $data['subscribe_date'] = $dt;

                if ($row->exp_date) $edt = date('F d, Y',strtotime($row->exp_date));
                $data['exp_date'] = $edt;

                if ($td > date('Y-m-d H:i:s',strtotime($row->exp_date)))
                {
                    if ($row->subscriptionstatus==1)
                    {
                        #Update Subscription Date
                        $this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'0');
                    }
                }else
                {
                    if (!$row->subscriptionstatus)
                    {
                        $this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'1');
                        $data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
                    }else
                    {
                        $data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
                    }
                }

                break;
            }
        }

        $data['Categories']=$this->getdata_model->GetCategories();
        $ret=$data['Network'];
        $host=strtolower(trim($_SERVER['HTTP_HOST']));

        if (strtolower(trim($ret))=='airtel')
        {
            $this->load->view('airtelpromo_view',$data);

        }elseif (strtolower(trim($ret))=='mtn')
        {
            if (($host=='localhost') or ($host =='localhost:8888'))
            {
                redirect('http://localhost:8888/laffhub/public_html/mtn/Subscriberhome', 'refresh');
            }else
            {
                redirect('http://mtn.laffhub.com/Subscriberhome', 'refresh');
            }
        }elseif (strtolower(trim($ret))=='wifi')
        {
            if (($host=='localhost') or ($host =='localhost:8888'))
            {
                redirect('http://localhost:8888/laffhub/public_html/Subscriberhome', 'refresh');
            }else
            {
                redirect('http://laffhub.com/Subscriberhome', 'refresh');
            }
        }else
        {
            if (($host=='localhost') or ($host =='localhost:8888'))
            {
                redirect('http://localhost:8888/laffhub/public_html/Home', 'refresh');
            }else
            {
                redirect('http://localhost/mtnlaffhub/Subscriberhome', 'refresh');
            }
        }
    }
}