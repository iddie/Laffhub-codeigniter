<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videos_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function GetVideoDetail($category,$files,$publisher_email)
	{#video_title,category,'VIDEO',video_status,streaming_link,video_id,filename
		$crit='';
		
		if ($category) $crit=" (TRIM(category)='".$category."')";
				
		if ($files)
		{
			$e=explode('^',$files);
			
			if (count($e)>0)
			{
				$s='';
				
				for($i=0;$i<count($e);$i++)
				{
					if (trim($s)=='') $s="'".$e[$i]."'"; else $s .=",'".$e[$i]."'";
				}
				
				if (trim($s) != '')
				{
					if (trim($crit)=='') $crit=" (filename IN (".$s."))"; else $crit .= " AND (filename IN (".$s."))";
				}
			}
		}
		
		$sql = "SELECT * FROM videos WHERE (TRIM(publisher_email)='".$publisher_email."') ";
		
		if (trim($crit)!='') $sql .= " AND ".$crit;
		
		$sql .= " ORDER BY video_title";
		
		$query = $this->db->query($sql);
		
		return $query->result_array();
	}
	
	public function GetVideos($category,$publisher_email)
	{#video_title,category,'VIDEO',video_status,streaming_link,video_id,filename
		$crit='';
		
		if ($category) $crit=" (TRIM(category)='".$category."')";
		
		#$sql = "SELECT DATE_FORMAT(date_created,'%d %b %Y %H:%i') AS DateCreated,videos.* FROM videos ";
		$sql = "SELECT DATE_FORMAT(date_created,'%d %b %Y') AS DateCreated,videos.* FROM videos WHERE (TRIM(publisher_email)='".$publisher_email."') ";
		
		if (trim($crit)!='') $sql .= " AND ".$crit;
		
		$sql .= " ORDER BY video_title";
#$file = fopen('aaa.txt',"w"); fwrite($file, $sql); fclose($file);
		$query = $this->db->query($sql);
		
		return $query->result_array();
	}
}
?>
