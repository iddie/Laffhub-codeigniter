<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Africa/Lagos');

/**
 * Handles API calls
 *
 * @author Adekunle <adekunle.olayinka@efluxz.com>
 */
class Api extends CI_Controller
{
    public $validToken;
    /**
     * Constructor function of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('encryption');
        $this->load->model('video');
        $this->load->helper('api');
        //assign token received from request to variable
        $token = ($this->input->post('api_token')) ?
        $this->input->post('api_token')
        : $this->input->get('api_token');
        //check if the token is valid
        ($this->encryption->decrypt($token)==$this->config->item('api-secure'))
         ? $this->validToken = true
         : $this->validToken=false;
    }

    /**
     * Index function
     *
     * @return void
     */
    public function connect_videos()
    {
        if ($this->validToken) {
            //perform any custom action
            $response = content_deactivate_actions($this);
            //retrieve objects
            $retrieved_data = $this->video->get_videos();
            $data['draw'] = $this->input->post('draw');
            $data['data'] = $retrieved_data['query_data'];
            $data['recordsTotal'] = $retrieved_data['records_total'];
            $data['recordsFiltered'] = $retrieved_data['records_filtered'];
            //merge response from custom action with objects
            $data = merge($data, $response);
        } else {
            $data['customActionMessage'] = 'Invalid API token';
            $data['customActionStatus'] = 'fail';
        }
        header('Content-type: application/json');
        echo json_encode($data);
    }
    public function save_report_recipients()
    {
        if ($this->validToken) {
            $this->load->model('report_recipients');
            $data = $this->report_recipients->store();
        } else {
            $data['action'] = 'fail';
            $data['message'] = 'Invalid API token';
            $data['token']  = $this->input->post('email');
        }
        header('Content-type: application/json');
        echo json_encode($data);
    }
    public function delete_report_recipients()
    {
        if ($this->validToken) {
            $this->load->model('report_recipients');
            $data = $this->report_recipients->delete();
        } else {
            $data['action'] = 'fail';
            $data['message'] = 'Invalid API token';
            $data['token']  = $this->input->post('email');
        }
        header('Content-type: application/json');
        echo json_encode($data);
    }
    public function generate_token()
    {
        echo $this->encryption->encrypt($this->config->item('api-secure'));
    }
    public function generate_report_statistics()
    {
        $data['action'] = 'success';
        $data['message'] = 'Data is available';
        //set network
        $network = 'Airtel';
        //assign empty sub_stats;
        $subscription_stats = [];
        //assign 0 to total_count
        $subscription_total = 0;
        //assign 0 to total_revenue
        $total_revenue = 0;
        //plans
        $plans = [];
        $dt=date('Y-m-d', strtotime('-1 day'));
        $data['rdt'] = $dt;
        $this->db->select('plan, amount');
        $this->db->where(
            [
                'network'=>$network,
                'subscription_status'=>'OK'
        ]
        );
        $this->db->where("DATE_FORMAT(subscribe_date,'%Y-%m-%d') = '".$dt."'");
        $this->db->distinct();
        $query = $this->db->get('subscription_history');
        $plan_count = $query->num_rows();
        $data['plans'] = $query->result();
        if (count($data['plans']) > 0) {
            //format plans
            foreach ($data['plans'] as $plan) {
                $formatted_plan[] = $plan->plan;
            }
            $this->db->select('plan, network, msisdn, amount');
            $this->db->where('network', $network);
            $this->db->where("DATE_FORMAT(subscribe_date,'%Y-%m-%d') = '".$dt."'");
            $this->db->where('subscription_status', 'OK');
            $this->db->where_in('plan', $formatted_plan);
            $this->db->distinct();
            $new_subscribers_query = $this->db->get('subscription_history');
            $data['total_new_subscribers_count'] = $new_subscribers_query->num_rows();
            //seperate plans data into objects
            foreach ($data['plans'] as $item_plan) {
                $plan_data['plan'] = $item_plan->plan;
                $plan_data['charge_per_user'] = $item_plan->amount;
                $plan_data['network'] = $network;
                $this->db->select('plan, network, msisdn, amount');
                $this->db->where('network', $network);
                $this->db->where("DATE_FORMAT(subscribe_date,'%Y-%m-%d') = '".$dt."'");
                $this->db->where('subscription_status', 'OK');
                $this->db->distinct();
                $this->db->where('plan', $item_plan->plan);
                $plan_data['count'] = $this->db->get('subscription_history')->num_rows();
                $plan_data['revenue'] = $item_plan->amount * $plan_data['count'];
                $total_revenue += $plan_data['revenue'];
                $subscription_stats[] = $plan_data;
            }
            $data['subscription_stats'] = $subscription_stats;
            $data['total_revenue'] = $total_revenue;
            $q_r = $this->db->get_where('report_recipients', ['is_active'=>true]);
            $data['recipients'] = $q_r->result();
        }
     
        header('Content-type: application/json');
        echo json_encode($data);
    }
}
