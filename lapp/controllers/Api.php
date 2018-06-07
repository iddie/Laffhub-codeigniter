<?php

defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Africa/Lagos');

/**
 * Handles API calls
 * 
 * @author Adekunle <adekunle.olayinka@efluxz.com>
 */
class Api extends CI_Controller
{   
    var $validToken;
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
        if ($this->validToken) 
        {
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
        }
        else
        {
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
        }
        else{
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
        }
        else{
            $data['action'] = 'fail';
            $data['message'] = 'Invalid API token';
            $data['token']  = $this->input->post('email');
        }
        header('Content-type: application/json');
        echo json_encode($data); 
    }
    private function generate_token()
    {
        echo $this->encryption->encrypt($this->config->item('api-secure'));
    }
}
