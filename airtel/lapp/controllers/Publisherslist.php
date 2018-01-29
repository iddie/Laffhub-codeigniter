<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Publisherslist extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('getdata_model');
    }

    public function index()
    {
        $data['Network']='';
        $data['Phone']='';

        if (($_SERVER['HTTP_HOST'] == 'localhost') or ($_SERVER['HTTP_HOST'] == 'localhost:8888'))  {

            $data['Network']=getenv('AIRTEL_NETWORK');
            $data['Phone']=getenv('AIRTEL_MSISDN');

        }else{

            $data['Network']=$this->getdata_model->GetNetwork();
            $data['Phone']=$this->getdata_model->GetMSISDN();
        }

        if ((!$data['Network']) or (!$data['Phone']))
        {
            redirect('Subscriberhome');
        }else
        {
            if ($_SESSION['subscriber_email']) $data['subscriber_email']=$_SESSION['subscriber_email'];

            $data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';

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

            $data['AdminRoot']=$this->getdata_model->GetAdminRoot();
            $data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
            $data['Categories']=$this->getdata_model->GetCategories();
            $data['Comedians']=$this->getdata_model->GetComedians();
            $data['Publishers']=$this->getdata_model->GetPublishers();

            $this->load->view('publisherslist_view',$data);
        }
    }
}
