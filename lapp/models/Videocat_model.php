<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videocat_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function GetVideoCategories()
	{
		#$sql = "SELECT @s:=@s+1 SN,category,id FROM video_categories,(SELECT @s:= 0) AS s ORDER BY category";
		$sql = "SELECT '' AS SN,category,id FROM video_categories,(SELECT @s:= 0) AS s ORDER BY category";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
}
?>
