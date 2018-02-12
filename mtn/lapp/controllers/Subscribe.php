<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribe extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('getdata_model');
        $this->load->library('session');
    }

    public function LoadSubscriptionHistory()
    {
        $msisdn=''; $startdate=''; $enddate=''; $network=''; $data=array();

        if ($this->input->post('startdate')) $startdate = trim($this->input->post('startdate'));
        if ($this->input->post('enddate')) $enddate = trim($this->input->post('enddate'));
        if ($this->input->post('network')) $network = trim($this->input->post('network'));
        if ($this->input->post('msisdn')) $msisdn = trim($this->input->post('msisdn'));

        $sql = "SELECT network,msisdn,plan,amount,subscription_status,subscribe_date,subscription_expiredate FROM subscription_history WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND  (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') AND (TRIM(subscription_status)='OK') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."')";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            $sn=0;

            while ($row = $query->unbuffered_row('array')):
                $sn++; $edt=''; $sdt=''; $sta='Failed';
                #
                if ($row['subscribe_date']) $sdt=date('d M Y @ H:i',strtotime($row['subscribe_date']));
                if ($row['subscription_expiredate']) $edt=date('d M Y @ H:i',strtotime($row['subscription_expiredate']));
                if ($row['subscription_status']) $sta=strtolower(trim($row['subscription_status']));

                if ($sta=='ok') $sta='Successful';
//SN,Network,Plan,Amount,SubscriptionDate,ExpiryDate,SubscriptionStatus
                $tp=array($sn,$network,$row['plan'],number_format($row['amount'],2),$sdt,$edt,$sta);

                $data[]=$tp;
            endwhile;

            echo json_encode($data);
        }else
        {
            print_r(json_encode($data));
        }

    }#End Of LoadSubscriptionJSON functions

    public function SubscribeUser()
    {
        $network=''; $msisdn=''; $plan=''; $amount=''; $subscribe_date=''; $exp_date='';
        $videos_cnt_to_watch=''; $duration=''; $email=''; $subscriptionId=''; $data['Phone']='';

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
        if (!$autobilling) $autobilling='0';

        $_SESSION['network'] = $network;
        $_SESSION['msisdn'] = $msisdn;
        $_SESSION['plan'] = $plan;
        $_SESSION['duration'] = $duration;
        $_SESSION['amount'] = $amount;
        $_SESSION['subscribe_date'] = $subscribe_date;
        $_SESSION['exp_date'] = $exp_date;
        $_SESSION['subscriptionId'] = $subscriptionId;
        $_SESSION['videos_cnt_to_watch'] = $videos_cnt_to_watch;

        $phone= $msisdn;

        $data['Network']= $network;
        $data['Phone']= $phone;

        $tm=date('H:i:s');

        $subscribe_date .= ' '.$tm;
        $exp_date .= ' '.$tm;

        $Msg=''; $dt=date('Y-m-d H:i');

        #Check if number is blacklisted
        $rt=$this->getdata_model->CheckForBlackList($network,$msisdn);

        if ($rt==true)
        {
            $ret='We are sorry, the phone number, <b>'.$msisdn.'</b>, has been blacklisted and cannot subscribe to this service.';

        }else {
            //Check if record exists
            $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='" . $this->db->escape_str($network) . "') AND (subscriptionstatus=1) AND (TRIM(msisdn)='" . $this->db->escape_str($msisdn) . "')";

            $query = $this->db->query($sql);

            $ret = '';
            $watched = 0;
            $maxwatch = 0;
            $expdt = '';
            $activeplan = '';
            $flag = false;
            $ex = '';
            #$subscriptionId=$this->getdata_model->GetGenericNumericNextID('subscriptions','subscriptionId',1);

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

                #Check if subscription has expired
                $exist = trim($this->getdata_model->MTNCheckStatus($data['Phone']));

                if (strtolower($exist) == 'active') {
                    $flag = false;
                } else#Expired
                {
                    $flag = true;
                }
            } else {

                $flag = true;
            }

            if ($flag == false) {
                $ret = "You cannot subscribe right now. You currently have an active subscription on this service";
                $Msg = "Subscription was not successful. Subscriber has an active subscription running. Current Subscription Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Email => " . $email . "; Service Plan => " . $activeplan . "; Expiry Date => " . $expdt;
            } else {

                $result = $this->getdata_model->MTNBilling($msisdn);

                if (trim(strtoupper($result)) === 'PROCESSING') {
                    $ret = 'Processing';

                }elseif(trim(strtoupper($result)) === 'PENDING REQUEST') {
                    $ret = "You currently have a pending subscription request. Complete your subscription by dialing <span><b> *560*1# </b><span> then click on Continue ";

                }elseif(trim(strtoupper($result)) === 'ALREADY ACTIVE'){
                    $ret = "You have an active subscription. Enjoy comedy clips on Laffhub";
                } else#Failed

                {
                    $ret = "Sorry, subscription was not successful, please try again";
                }
            }
        }
        echo $ret;

    }#End Of SubscribeUser functions


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

    public function index()
    {
        $data['Network']='';
        $data['Phone']='';

        if (($_SERVER['HTTP_HOST'] == 'localhost') or ($_SERVER['HTTP_HOST'] == 'localhost:8888'))  {

            $data['Network']=getenv('MTN_NETWORK');
            $data['Phone']=getenv('MTN_MSISDN');

        }else{

            $data['Network']=$this->getdata_model->GetNetwork();
            $data['Phone']=$this->getdata_model->GetMSISDN();
        }

        $host='';

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

        #Get PayStack Settings
        $PayStackSettings = $this->getdata_model->GetPaystackSettings();

        if (count($PayStackSettings)>0)
        {
            foreach($PayStackSettings as $row):
                if ($row->PublicKey) $data['PublicKey']=$row->PublicKey;
                if ($row->payment_currency) $data['payment_currency']=$row->payment_currency;

                break;
            endforeach;
        }

        $data['Categories']=$this->getdata_model->GetCategories();

        $ret=$data['Network'];

        #$this->load->view('subscribe_view',$data);#Fail Page
        if (strtolower(trim($ret))=='airtel')
        {
            if ($host=='localhost')
            {
                redirect('http://localhost:8888/laffhub/public_html/airtel/Subscriberhome', 'refresh');
            }else
            {
                redirect('http://airtel.laffhub.com/Subscriberhome', 'refresh');
            }
        }elseif (strtolower(trim($ret))=='mtn')
        {
            $this->load->view('subscribe_view',$data);

        }elseif (strtolower(trim($ret))=='wifi')
        {
            if ($host=='localhost')
            {
                redirect('http://localhost:8888/laffhub/public_html/Home', 'refresh');
            }else
            {
                redirect('https://laffhub.com/Home', 'refresh');
            }
        }else
        {
            if ($host=='localhost')
            {
                redirect('http://localhost:8888/laffhub/public_html/Home', 'refresh');
            }else
            {
                redirect('https://laffhub.com/Home', 'refresh');
            }
        }
    }

    public function confirmation() {

        $autobilling=1; $watched =0;

        if ($this->input->post('msisdn')) $msisdn = trim($this->input->post('msisdn'));
        if ($this->input->post('network')) $network = trim($this->input->post('network'));
        $plan = $_SESSION['plan'];
        $duration = $_SESSION['duration'];
        $amount = $_SESSION['amount'];
        $subscribe_date = $_SESSION['subscribe_date'];
        $exp_date = $_SESSION['exp_date'];
        $subscriptionId = $_SESSION['subscriptionId'];
        $videos_cnt_to_watch = $_SESSION['videos_cnt_to_watch'];

        $phone= $msisdn;

        $data['Network']= $network;
        $data['Phone']= $phone;

        $confirmed=trim($this->getdata_model->MTNCheckStatus($phone));

        if (trim(strtoupper($confirmed)) === 'ACTIVE')
        {
            #Check if new subscriber
            $new = $this->getdata_model->IsNewSubscriber($msisdn, $network);

            ########################### DAILY REPORT FUNCTIONS #######################
            #NEW - Captured at subscription (Portal and SMS)
            if ($new==1)
            {
                #### Update Into new_subscriptions
                $sql="SELECT msisdn FROM new_subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0 )
                {
                    $this->db->trans_start();

                    $dat=array(
                        'plan' => $this->db->escape_str($plan),
                        'subscriptiondate' => $this->db->escape_str($subscribe_date)
                    );

                    $where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";

                    $this->db->where($where);
                    $this->db->update('new_subscriptions', $dat);

                    $this->db->trans_complete();
                }else
                {
                    $this->db->trans_start();

                    $dat=array(
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

            #### Update  successful_charging
            $sql="SELECT msisdn FROM successful_charging WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";

            $query = $this->db->query($sql);

            if ($query->num_rows() > 0 )
            {
                $this->db->trans_start();

                $dat=array(
                    'plan' => $this->db->escape_str($plan),
                    'subscriptiondate' => $this->db->escape_str($subscribe_date)
                );

                $where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";

                $this->db->where($where);
                $this->db->update('successful_charging', $dat);

                $this->db->trans_complete();
            }else
            {
                $this->db->trans_start();

                $dat=array(
                    'network' => $this->db->escape_str($network),
                    'msisdn' => $this->db->escape_str($msisdn),
                    'plan' => $this->db->escape_str($plan),
                    'email' => '',
                    'subscriptiondate' => $this->db->escape_str($subscribe_date),
                );

                $this->db->insert('successful_charging', $dat);

                $this->db->trans_complete();
            }
            ########################### DAILY REPORT FUNCTIONS #######################

            #Save Subscription Record
            $this->db->trans_start();

            $dat=array(
                'subscriptionId' => $subscriptionId,
                'network' => $this->db->escape_str($network),
                'msisdn' => $msisdn,
                'plan' => $this->db->escape_str($plan),
                'duration' => $this->db->escape_str($duration),
                'amount' => $this->db->escape_str($amount),
                'autobilling' => $this->db->escape_str($autobilling),
                'subscribe_date' => $subscribe_date,
                'exp_date' => $this->db->escape_str($exp_date),
                'videos_cnt_watched' => $watched,
                'videos_cnt_to_watch' => $this->db->escape_str($videos_cnt_to_watch),
                'getstatus_from_network' => 0,
                'subscriptionstatus' => 1
            );


            $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."')";
            $query = $this->db->query($sql);

            if ($query->num_rows() > 0 )#There is subscription
            {
                $row = $query->row();

                if ($row->subscriptionstatus==0)
                {

                    $where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."')";
                    $this->db->where($where);
                    $this->db->update('subscriptions', $dat);
                }
            }else
            {
                $this->db->insert('subscriptions', $dat);
            }

            $this->db->trans_complete();

            #Create record in watchlists table
            $this->db->trans_start();
            $dat=array('subscriptionId' => $subscriptionId, 'videolist' => '');
            $this->db->insert('watchlists', $dat);
            $this->db->trans_complete();

            $Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;

            #Reset SESSION variables
            $sdt = date('F d, Y',strtotime($subscribe_date));
            $edt = date('F d, Y',strtotime($exp_date));

            $_SESSION['subscribe_date']=$sdt;
            $_SESSION['exp_date']=$edt;
            $_SESSION['subscriptionstatus']='<span style="color:#099E11;">Active</span>';


            #Save to subscription_history table
            $this->db->trans_start();

            $transid=date('YmdHis').'_'.$msisdn;

            $dat=array(
                'network' => $this->db->escape_str($network),
                'msisdn' => $this->db->escape_str($msisdn),
                'plan' => $this->db->escape_str($plan),
                'amount' => $this->db->escape_str($amount),
                'subscribe_date' => $this->db->escape_str($subscribe_date),
                'subscription_expiredate' => $this->db->escape_str($exp_date),
                'transid' => $this->db->escape_str($transid),
                'cptransid' => '',
                'sentmessage' => '',
                'subscription_status' => 1,
                'subscription_message' => $this->db->escape_str($Msg)
            );

            $this->db->insert('subscription_history', $dat);

            $this->db->trans_complete();

            $ret = 'OK';

        }else #User didn't confirm request

        {#return array('Status' => 'FAILED','errorCode' => $code,'errorMessage' => $msg);
            $errorCode = "ERROR 202 Confirmation Pending";
            #FAILED ACTIVATIONS
            $new = $this->getdata_model->IsNewSubscriber($msisdn, $network);

            if ($new==1)
            {
                $sql="SELECT msisdn FROM failed_activations WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(activationdate,'%Y-%m-%d')='".date('Y-m-d')."')";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0 )
                {
                    $this->db->trans_start();

                    $dat=array(
                        'plan' => $this->db->escape_str($plan),
                        'activationdate' => $this->db->escape_str($subscribe_date)
                    );

                    $where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(activationdate,'%Y-%m-%d')='".date('Y-m-d')."')";
                    $this->db->where($where);
                    $this->db->update('failed_activations', $dat);

                    $this->db->trans_complete();
                }else
                {
                    #Insert into failed activation table
                    $this->db->trans_start();

                    $dat=array(
                        'network' => $this->db->escape_str($network),
                        'msisdn' => $this->db->escape_str($msisdn),
                        'plan' => $this->db->escape_str($plan),
                        'activationdate' => $this->db->escape_str($subscribe_date)
                    );

                    $this->db->insert('failed_activations', $dat);

                    $this->db->trans_complete();
                }
            }else
            {


                $sql="SELECT msisdn FROM cust_failed_charging WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='".date('Y-m-d')."')";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0 )
                {
                    $this->db->trans_start();

                    $dat=array(
                        'plan' => $this->db->escape_str($plan),
                        'chargingdate' => date('Y-m-d H:i:s')
                    );

                    $where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='".date('Y-m-d')."')";
                    $this->db->where($where);
                    $this->db->update('cust_failed_charging', $dat);

                    $this->db->trans_complete();
                }else
                {
                    #Insert into cust_failed_charging table
                    $this->db->trans_start();

                    $dat=array(
                        'network' => $this->db->escape_str($network),
                        'msisdn' => $this->db->escape_str($msisdn),
                        'plan' => $this->db->escape_str($plan),
                        'chargingdate' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert('cust_failed_charging', $dat);

                    $this->db->trans_complete();
                }
            }

            $Msg="Subscription was not successful.You are yet to confirm your subscription. Dial *560*1# to confirm";

            $subscription_message= $Msg;

            #Save to subscription_history table
            $this->db->trans_start();

            $transid =date('YmdHis').'_'.$msisdn;

            $dat=array(
                'network' => $this->db->escape_str($network),
                'msisdn' => $this->db->escape_str($msisdn),
                'plan' => $this->db->escape_str($plan),
                'amount' => $this->db->escape_str($amount),
                'transid' => $this->db->escape_str($transid),
                'cptransid' => '',
                'sentmessage' => '',
                'subscription_status' => 0,
                'subscription_message' => $this->db->escape_str($subscription_message),
                'errorcode' => $this->db->escape_str($errorCode)
            );

            $this->db->insert('subscription_history', $dat);

            $this->db->trans_complete();

            $ret = $Msg;

            $_SESSION['subscriptionstatus']='<span style="color:#9E0911;">Not Active</span>';
        }

        $this->getdata_model->LogDetails($network.'('.$msisdn.')',$Msg,$msisdn,$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'SUBSCRIBED USER',$_SESSION['LogID']);

        echo $ret;

    }

    public function confirm() {

        $host='';

//        $data['Network']=$this->getdata_model->GetNetwork();
//        $data['Phone']=$this->getdata_model->GetMSISDN();

        $data['Network']= $_SESSION['Network'];
        $data['Phone']= $_SESSION['Phone'];

        $ret = $data['Network'];

        if (strtolower(trim($ret))=='mtn')
        {
            $this->load->view('confirmation_view',$data);

        }elseif (strtolower(trim($ret))=='airtel')
        {
            if ($host=='localhost')
            {
                redirect('http://localhost/airtellaffhub/Subscriberhome', 'refresh');
            }else
            {
                redirect('http://airtel.laffhub.com/Subscriberhome', 'refresh');
            }
        }elseif (strtolower(trim($ret))=='wifi')
        {
            if ($host=='localhost')
            {
                redirect('http://localhost/laffhub/Home', 'refresh');
            }else
            {
                redirect('https://laffhub.com/Home', 'refresh');
            }
        }else
        {
            if ($host=='localhost')
            {
                redirect('http://localhost/laffhub/Home', 'refresh');
            }else
            {
                redirect('https://laffhub.com/Home', 'refresh');
            }
        }
    }


}
