<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Healthmsg_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function GetMessages()
	{
		$sql = "SELECT '' AS SN,msg,msg_id AS MsgID,msg_status AS MsgStatus FROM health_msgs,(SELECT @s:= 0) AS s ORDER BY msg_id";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
}
?>
