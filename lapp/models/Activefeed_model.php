<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activefeed_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function GetRSS()
	{#[VIEW],KEY,TITLE,DESCRIPTION,SHORT-LINK,STATUS,SCHEDULE-ID,FEED-ID,LONG-ID
		$sql = "SELECT (SELECT expiredate FROM active_rss_feed WHERE active_rss_feed.feed_id=rss_feed.feed_id LIMIT 0,1) AS expiredate,(SELECT pubdate FROM active_rss_feed WHERE active_rss_feed.feed_id=rss_feed.feed_id LIMIT 0,1) AS pubdate,(SELECT schedule_id FROM active_rss_feed WHERE active_rss_feed.feed_id=rss_feed.feed_id LIMIT 0,1) AS schedule_id, rss_feed.* FROM rss_feed ORDER BY feed_id DESC";
#title,longlink,shortlink,description,STATUS,feed_id,filename,insert_date,schedule_id
		$query = $this->db->query($sql);
		
		return $query->result();
	}
}
?>
