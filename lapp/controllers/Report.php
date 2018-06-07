<?php
session_start();
class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('session');
        $this->load->library('encryption');
        $this->load->model('report_recipients');
    }
    
    /**
     * Display view for managing subscription recipients
     *
     * @return void
     */
    public function recipients()
    {
        checkIfAuthenticated();
        //set title
        $data['title'] = 'Manage Report Recipients';
        $data['content'] = 'subscription_recipients';
        //set js component
        $data['pagejs'] = 'components/report_recipients';
        //set api_token
        $data['api_token'] = $this->_generate_token();
        //retrieve subscription report recipients
        $data['subscription_recipients'] = $this->report_recipients->subscription_recipients();
        $session_data = retrieveSession();
        $data = array_merge($data, $session_data);
        $this->load->view('template', $data);
    }
    private function _generate_token()
    {
        return $this->encryption->encrypt($this->config->item('api-secure'));
    }
}
