<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

date_default_timezone_set('Africa/Lagos');
class Video extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_videos()
    {
        $columns =
            [
            'id',
            'publisher_email',
            'video_title',
            'category',
            'comedian',
            'video_status',
            'play_status',
            'date_created',
            'featured'
            ];
        //set default limit;
        $limit = 10;
        //set default offset;
        $offset = 0;
        ($this->input->post('length')) ? $limit=$this->input->post('length') : null;
        ($this->input->post('start')) ? $offset=$this->input->post('start') : null;
        //for the sake of statistics
        $data['records_total'] = $this->db->count_all('videos');
        
        //filter results below
        $this->_filter($columns);
        //check if retrieve-all was received
        ($limit==-1) ? $limit = $data['records_total'] : null;
        
        $this->db->from('videos');
        $data['records_filtered'] = $this->db->count_all_results();
        $this->db->select($columns);
        //filter results below
        $this->_filter($columns);
        $this->_order($columns);
        $this->db->limit($limit, $offset);
        $query = $this->db->get('videos');
        foreach($query->result() as $item) {
            $item->DT_RowId = $item->id;
            $data['query_data'][] = $item;
        }
        (!isset($data['query_data'])) ? $data['query_data'] = [] : null;
        return $data;
    }
    public function _order($columns)
    {
        $order = $this->input->post('order')[0];
        $column = $columns[$order['column']-1];
        $dir = strtoupper($order['dir']);
        $this->db->order_by($column, $dir);
    }
    private function _filter($columns)
    {
        if($this->input->post('action'))
        {
            switch ($this->input->post('action')){
                case 'filter':
                    $id_from = trim($this->input->post('id_from'));
                    $id_to = trim($this->input->post('id_to'));
                    (strlen($id_from)>0 && strlen($id_to) > 0) 
                    ? $this->db->where(sprintf("id between %s and %s",$id_from
                    ,$id_to )) : null;
                    for($x=1; $x<count($columns); $x++) {
                        $filter_value = trim($this->input->post($columns[$x]));
                        if($columns[$x] !='date_created' && strlen($filter_value)>0)
                        {
                           $this->db->like($columns[$x], $this->input->post($columns[$x]));
                        }  
                    }
                    $date_from = trim($this->input->post('order_date_from'));
                    $date_to = trim($this->input->post('order_date_to'));
                    (strlen($date_from)>1&&strlen($date_to>1)) ? 
                    $this->db->where(sprintf("date_created between '%s' and '%s'",$date_from,$date_to)) :null;
                    break;
                default:
                    break;
            }
        }
    }
}