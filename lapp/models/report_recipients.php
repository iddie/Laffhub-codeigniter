<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

date_default_timezone_set('Africa/Lagos');
class Report_recipients extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'report_recipients';
    }
    public function subscription_recipients()
    {
        $where = array('is_active'=>true,'receive_subscription'=>true);;
        $query = $this->db->get_where('report_recipients');
        $data = [];
        foreach($query->result() as $row)
        {
            $data[] = $row;
        }
        return $data;
    }
    public function store()
    {
        $to_validate = [
            'email', 'receive_subscription', '_method',
            'receive_revenue', 'is_active'
        ];
        $validCount = count($to_validate);
        $attemptValidateCount = 0;
        foreach($to_validate as $item) {
            ($this->input->post($item) != null) ? $attemptValidateCount++ : null;
        }
        if($validCount==$attemptValidateCount)
        {
            $inputs = $this->input->post();
            if($inputs['_method']=='post'){
                $query = $this->db->get_where($this->tablename, ['email'=>$inputs['email']]);
                if($query->num_rows()>0)
                {
                    $data['status'] = 'fail'; $data['message'] = 'Duplicate email entry';
                }
                else{
                    $data = $this->_create($inputs);
                }
            }
            else if($inputs['_method']=='put'){
                if ($this->input->post('id') !=null) {
                   $data = $this->_update($inputs);
                }
                else{
                    $data['status'] = 'fail';
                    $data['message'] = 'Please provide id';
                }
            }
        }
        else{
            $data['message'] = 'Please provide all required parameters';
            $data['status'] = 'fail';
        }
        return $data;
    }
    private function _create($inputs)
    {
        $data = [
            'email'=>$inputs['email'],
            'receive_subscription'=>$inputs['receive_subscription'],
            'receive_revenue'=>$inputs['receive_revenue'],
            'is_active'=>$inputs['is_active']
        ];
        $this->db->insert($this->tablename, $data);
        $query = $this->db->get_where($this->tablename, ['email'=>$inputs['email']]);
        $response['status'] = 'success';
        $response['message'] = 'Recipient created successfully';
        $response['data'] = $query->row();
        return $response;
    }
    private function _update($inputs)
    {
        $data = [
            'email'=>$inputs['email'],
            'receive_subscription'=>$inputs['receive_subscription'],
            'receive_revenue'=>$inputs['receive_revenue'],
            'is_active'=>$inputs['is_active']
        ];
        $this->db->update($this->tablename, $data, ['id'=>$inputs['id']]);
        $response['status'] = 'success';
        $response['message'] = 'Recipient modified successfully';
        return $response;
    }
    public function delete()
    {
        if($this->input->post('id') != null)
        {
            $this->db->delete($this->tablename, array('id' => $this->input->post('id'))); 
            $data['message'] = 'Report recipient deleted successfully';
            $data['status'] = 'success';
        }
        else{
            $data['message'] = 'Please provide id';
            $data['status'] = 'fail';
        } 
        return $data;
    }
}