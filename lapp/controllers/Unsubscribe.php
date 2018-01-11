<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Unsubscribe extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
		
	public function UnsubscribeUser()
	{
		$network=''; $msisdn=''; $plan=''; $email=''; $subscriptionId='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('msisdn')) $msisdn = $this->input->post('msisdn');
		if ($this->input->post('email')) $email = $this->input->post('email');
		if ($this->input->post('subscriptionId')) $subscriptionId = $this->input->post('subscriptionId');
		if ($this->input->post('plan')) $plan = trim($this->input->post('plan'));
							
		$Msg=''; $message=''; $ret='';
		
		$msisdn=$this->getdata_model->CleanPhoneNo($msisdn);
		
		//Check if record exists
		$sql="SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0 )#There is active subscription
		{
			#Unsubscribe
			$this->db->trans_start();

			$where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
			$this->db->where($where);
			$this->db->delete('subscriptions');
			
			$this->db->trans_complete();
			
			#Remove watchlist entry
			$this->db->trans_start();
			
			$this->db->where('subscriptionId',$subscriptionId);
			$this->db->delete('watchlists'); 
			
			$this->db->trans_complete();
			
			################ Update Daily Revenue Report ##################
			$cancelled='0';
				
			#CANCELLED
			$sql = "SELECT * FROM daily_revenue WHERE DATE_FORMAT(subscribe_date,'%Y-%m-%d')='".date('Y-m-d')."'";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				$row = $query->row();
			
				if ($row->cancelled) $cancelled=$row->cancelled;
				
				$cancelled += 1;
			
				$this->db->trans_start();

				$dat=array('cancelled' => $this->db->escape_str($cancelled));
				
				$this->db->where("DATE_FORMAT(subscribe_date,'%Y-%m-%d')=",date('Y-m-d'));
				$this->db->update('daily_revenue', $dat); 	
				
				$this->db->trans_complete();
			}else
			{
				$cancelled='1';
				
				$this->db->trans_start();

				$dat=array(
					'cancelled' => $this->db->escape_str($cancelled),
					'subscribe_date' => date('Y-m-d H:i:s')
				);
											
				$this->db->insert('daily_revenue', $dat); 	
				
				$this->db->trans_complete();
			}
			
			#INSERT INTO optouts table
			$this->db->trans_start();
			
			$dat=array(
				'network' => $this->db->escape_str($network),
				'msisdn' => $msisdn,
				'lastplan' => $this->db->escape_str($plan),
				'optout_date' => date('Y-m-d H:i:s')
			);
										
			$this->db->insert('optouts', $dat); 	
			
			$this->db->trans_complete();				
			
			
			$Msg='Subscriber with email, '.$email.', has unsubscribed from Laffhub service successfully.';
			
			$ret='OK';
			
			#Reset SESSION variables
			$_SESSION['subscribe_date']='';
			$_SESSION['exp_date']='';
			$_SESSION['subscriptionstatus']='<span style="color:#9E0911;">Not Subscribed</span>';
			
			$this->getdata_model->LogDetails($network.' LaffHub',$Msg,$email,$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'UNSUBSCRIBED USER',$_SESSION['LogID']);
		}else
		{
			#$message = "Your attempt to opt out from Laffhub failed. You have no active subscription on Laffhub service. Text YES to 2001 to activate 7dys/15 videos. Service costs N100";
				
			$ret="Your attempt to unsubscribe from Laffhub ".strtoupper($plan)." plan failed. You have no active subscription on Laffhub service.";
			
			$Msg="Unsubscription was not successful. Subscriber does not have an active subscription running.";
		}
		
		echo $ret;
	}#End Of SubscribeUser functions
	
	public function index()
	{
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
			
			$data['Network']=$this->getdata_model->GetNetwork();
			$data['Phone']=$this->getdata_model->GetMSISDN();
			
			$data['subscribe_date'] = ''; $data['exp_date'] = ''; 
			
			$data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
			$data['status'] = '0';
				
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
						
			#Get Subscription Details
			$result=$this->getdata_model->GetSubscriptionDetails($data['Network'],$data['Phone'],$data['subscriber_email']);

			if (count($result)>0)
			{
				foreach($result as $row)
				{
					if ($row['plan']) $data['plan'] = $row['plan'];	
					if ($row['duration']) $data['duration'] = $row['duration'];
					
					if ($row['videos_cnt_to_watch']) $data['videos_cnt_to_watch'] = $row['videos_cnt_to_watch'];
					
					if ($row['subscriptionId']) $data['subscriptionId'] = $row['subscriptionId'];
					
					if ($row['amount']) $data['amount'] = number_format($row['amount'],2);
					if ($row['subscribe_date']) $data['SubscriptionDate'] = date('d M Y @ H:i:s',strtotime($row['subscribe_date']));
					if ($row['exp_date']) $data['ExpiryDate'] =  date('d M Y @ H:i:s',strtotime($row['exp_date']));
				
					if ($row['subscriptionstatus']==1)
					{
						$data['status'] = '1';
					}else
					{
						$data['status'] = '0';
					}
					
					$data['Watched']='';
					
					#Get Watch Count - $data['Watched']
					if ($data['subscriptionId'])
					{
						$videolist=$this->getdata_model->GetWatchList($data['subscriptionId']);
						
						if ($videolist <> '')
						{
							$arrTotalWatched=explode('^',$videolist);
							
							$Watched=count($arrTotalWatched);
							
							$data['Watched']=number_format($Watched,0);
						}
					}
										
					break;
				}
			}
			
			$data['Categories']=$this->getdata_model->GetCategories();
			$this->load->view('unsubscribe_view',$data);#Fail Page
		}else
		{
			redirect("Home");
		}	
	}
}
