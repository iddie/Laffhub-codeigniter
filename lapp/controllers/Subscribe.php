<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(E_ALL); ini_set('display_errors', 1); 

class Subscribe extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	
	public function LoadSubscriptionHistory()
	{
		$startdate=''; $enddate=''; $email=''; $network='';
		
		if ($this->input->post('startdate')) $startdate = trim($this->input->post('startdate'));
		if ($this->input->post('enddate')) $enddate = trim($this->input->post('enddate'));
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		
		$sql = "SELECT network,email,plan,amount,subscription_status,subscribe_date,subscription_expiredate FROM subscription_history WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND  (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') AND (TRIM(subscription_status)='OK') AND (TRIM(email)='".$this->db->escape_str($email)."')";
				
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
		$amount=''; $txn_ref=''; $email=''; $gateway=''; $customername=''; $currency='';
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
		if ($this->input->post('currency')) $currency = trim($this->input->post('currency'));

		if (floatval($amount)>0) $amount=floatval(str_replace(',','',$amount))/100;
		
		$phone=$this->getdata_model->CleanPhoneNo($phone);
		
		$dt=date('Y-m-d H:i');
		
		#Check if number is blacklisted
		#$rt=$this->getdata_model->CheckForBlackList($network,$email);
		
		if ($rt==true)
		{
			#$ret='We are sorry, the phone number, <b>'.$msisdn.'</b>, cannot subscribe to this service.';
			
			#$Msg="Phone number, ".$msisdn.", cannot be subscribed. It has been blacklisted.";
		}else
		{
			
		}
				
		$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (subscriptionstatus=1) AND (TRIM(email)='".$this->db->escape_str($email)."')";
		
#$file = fopen('aaa.txt',"w"); fwrite($file, $sql); fclose($file);		
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
			$sql = "SELECT * FROM payment_log WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
								
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
					$Msg="Could Not Log Subscription With Id ".$subscriptionId.".";
					$ret = 'Could Not Log Subscription With Id <b>'.$subscriptionId.'</b>. Please Restart The Subscription Process.';
				}else
				{			
					$Msg="Subscription With Id ".$subscriptionId." Was Logged Successfully.";
						
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
	{
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
		
		$phone=$this->getdata_model->CleanPhoneNo($phone);
		
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
			$sql="SELECT * FROM payment_log WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";			
			
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
				
				$where = "(TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";				
				
				$this->db->where($where);
				$this->db->update('payment_log', $dat);	
				
				$this->db->trans_complete();	
			}	
			
			$new=$this->getdata_model->IsNewSubscriberEmail($email,$network);
			
			if (trim(strtolower($trans_status)) == "success")
			{
				$paid='1';
			
				######################
				#Update subscription table - 'amountpaid' => 1,
				$this->db->trans_start();
		
				$dat=array(
					'email' => $this->db->escape_str($email),
					'subscriptionId' => $this->db->escape_str($subscriptionId),
					'network' => $this->db->escape_str($network),
					'msisdn' => $this->db->escape_str($phone),
					'plan' => $this->db->escape_str($plan),
					'duration' => $this->db->escape_str($duration),
					'amount' => $this->db->escape_str(str_replace(',','',$TransAmount)),
					'paymentdate' => $this->db->escape_str($PaymentDate)
				);
											
				$this->db->insert('accounts', $dat); 	
				
				$this->db->trans_complete();
				
				
				########################### DAILY REPORT FUNCTIONS #######################
				$amt=str_replace(',','',$TransAmount);
				
				#NEW - Captured at subscription (Portal and SMS)				
				if ($new==1)
				{
					#### Insert Into new_subscriptions
					$this->db->trans_start();
		
					$dat=array(
						'network' => $this->db->escape_str($network),
						'msisdn' => $this->db->escape_str($phone),
						'plan' => $this->db->escape_str($plan),
						'email' => $this->db->escape_str($email),
						'subscriptiondate' => $this->db->escape_str($payment_date)
					);	
												
					$this->db->insert('new_subscriptions', $dat); 	
					
					$this->db->trans_complete();
				}				
							
				########################### DAILY REPORT FUNCTIONS #######################
				
				
				
				#Save Subscription Record
				$this->db->trans_start();
	
				$dat=array(
					'subscriptionId' => $subscriptionId,
					'email' => $this->db->escape_str($email),
					'network' => $this->db->escape_str($network),
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
								
				$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
				
				$query = $this->db->query($sql);
				
				
				if ($query->num_rows() > 0)
				{
					$row = $query->row();
					
					if ($row->subscriptionstatus==0)
					{
						$where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";	
						
						$this->db->where($where);
						$this->db->update('subscriptions', $dat); 
					}
				}else
				{
					$this->db->insert('subscriptions', $dat); 	
				}
				
				$this->db->trans_complete();
				
				$Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$phone."; Email => ".$email."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$TransAmount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;
				
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
				
				#Save to subscription_history table
				$this->db->trans_start();
	
				$dat=array(
					'network' => $this->db->escape_str($network),
					'msisdn' => $this->db->escape_str($phone),
					'plan' => $this->db->escape_str($plan),
					'email' => $this->db->escape_str($email),
					'amount' => $this->db->escape_str(str_replace(',','',$TransAmount)),
					'subscribe_date' => $this->db->escape_str($subscribe_date),
					'subscription_expiredate' => $this->db->escape_str($exp_date),
					'transid' => $this->db->escape_str($subscriptionId),				
					'cptransid' => '',
					'sentmessage' => 'You have renewed your '.$plan.' Laffhub Subscription.',
					'subscription_status' => 'OK',				
					'subscription_message' => 'Successful',
					'errorcode' => ''
				);
											
				$this->db->insert('subscription_history', $dat); 	
				
				$this->db->trans_complete();
	
				
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
				
				$rt=$this->getdata_model->SendEmail('admin@laffhub.com',$email,$plan.' Plan Subscription Fee','',$emailmsg,$altemailmsg,$email);
				
				$ret = 'OK';
			}else
			{
				########################### DAILY REPORT FUNCTIONS #######################
				#NEW - Captured at subscription (Portal and SMS)				
				if ($new==1)
				{					
					#Insert into failed activation table
					$this->db->trans_start();

					$dat=array(
						'network' => $this->db->escape_str($network),
						'msisdn' => $this->db->escape_str($msisdn),
						'plan' => $this->db->escape_str($plan),
						'email' => $this->db->escape_str($email),
						'activationdate' => $this->db->escape_str($subscribe_date)
					);	
												
					$this->db->insert('failed_activations', $dat); 	
					
					$this->db->trans_complete();
				}else #Old
				{
					#Insert into cust_failed_charging table
					$this->db->trans_start();

					$dat=array(
						'network' => $this->db->escape_str($network),
						'msisdn' => $this->db->escape_str($msisdn),
						'plan' => $this->db->escape_str($plan),
						'email' => $this->db->escape_str($email),
						'chargingdate' => date('Y-m-d H:i:s')
					);	
												
					$this->db->insert('cust_failed_charging', $dat); 	
					
					$this->db->trans_complete();
				}
							
				########################### DAILY REPORT FUNCTIONS #######################
				
				$paid='0';
				
				$Msg="Subscription was not successful. Details: Network => WIFI; MSISDN => ".$phone."; Email => ".$email."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$TransAmount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date."; Subscription Id => ".$subscriptionId;
				
				$ret='Payment was not successful. Subscription Id is '.$subscriptionId;
				
				#Save to subscription_history table
				$this->db->trans_start();
	
				$dat=array(
					'network' => $this->db->escape_str($network),
					'msisdn' => $this->db->escape_str($phone),
					'plan' => $this->db->escape_str($plan),
					'email' => $this->db->escape_str($email),
					'amount' => $this->db->escape_str(str_replace(',','',$TransAmount)),
					'subscribe_date' => $this->db->escape_str($subscribe_date),
					'subscription_expiredate' => $this->db->escape_str($exp_date),
					'transid' => $this->db->escape_str($subscriptionId),				
					'cptransid' => '',
					'sentmessage' => $ret,
					'subscription_status' => 'Failed',				
					'subscription_message' => $ret,
					'errorcode' => ''
				);
											
				$this->db->insert('subscription_history', $dat); 	
				
				$this->db->trans_complete();
			}							
			#if (trim(strtolower($trans_status)) == "success") $paid='1'; else $paid='0';			
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
	
	public function index()
	{

		$_SESSION['subscribe_url'] = $_SERVER['REQUEST_URI'];

	    if ($_SESSION['subscriber_email'])
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			
			if ($_SESSION['subscriber_name']) $data['subscriber_name'] = $_SESSION['subscriber_name'];
			if ($_SESSION['subscriber_pwd']) $data['subscriber_pwd'] = $_SESSION['subscriber_pwd'];
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['subscriber_status']) $data['subscriber_status'] = $_SESSION['subscriber_status'];
			if ($_SESSION['facebook_id']) $data['facebook_id'] = $_SESSION['facebook_id'];

			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
			if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
			if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
			if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
			
			$data['subscribe_date'] = ''; $data['exp_date'] = ''; $data['subscriptionstatus'] = '';
			$data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
			$data['Network']=$this->getdata_model->GetNetwork();
			$data['Phone']=$this->getdata_model->GetMSISDN();
		
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
						$data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
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
			$this->load->view('subscribe_view',$data);#Fail Page
		}else
		{
			redirect("Home");
		}	
	}
}
