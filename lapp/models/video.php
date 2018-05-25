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

        //check if retrieve-all was received
        ($limit==-1) ? $limit = $data['records_total'] : null;
        
        $this->db->from('videos');
        $data['records_filtered'] = $this->db->count_all_results();
        $this->db->select($columns);
        $this->db->limit($limit, $offset);
        $query = $this->db->get('videos');
        foreach($query->result() as $item) {
            $data['query_data'][] = $item;
        }
        //$this->log_inputs();
        return $data;
    }
    public function log_inputs()
    {
        log_message('debug', json_encode($_POST));
    }
}