<?php
session_start();
class Content extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('session');
        $this->load->library('encryption');
    }
    public function deactivate()
    {
        checkIfAuthenticated();
        //set title
        $data['title'] = 'Deactivate Videos';
        $data['content'] = 'deactivatevideo_view';
        //set js component
        $data['pagejs'] = 'components/ajax_datatable';
        //set api_token
        $data['api_token'] = $this->_generate_token();
        $session_data = retrieveSession();
        $data = array_merge($data, $session_data);
        $this->load->view('template', $data);
    }
    private function _generate_token()
    {
        return $this->encryption->encrypt($this->config->item('api-secure'));
    }
}
