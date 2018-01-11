<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videos_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function GetVideos()
	{#video_title,category,'VIDEO',video_status,streaming_link,video_id,filename
		$sql = "SELECT * FROM videos ORDER BY video_title";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
}
?>
