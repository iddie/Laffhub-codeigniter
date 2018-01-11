<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function GetUsers()
	{#username,firstname,lastname,pwd,email,phone,accountstatus,datecreated,role
	#Upload_Video,CreateUser,SetParameters,ViewLogReport
		$sql = "SELECT '' AS SN,DATE_FORMAT(datecreated,'%d %b %Y') AS date_created,CONCAT(firstname,' ',lastname) AS fullname ,userinfo.* FROM userinfo,(SELECT @s:= 0) AS s ORDER BY firstname,lastname";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
}
?>
