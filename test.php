<?php

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

error_reporting(E_ALL); ini_set('display_errors', 1); 

echo GetMSISDN();

function GetMSISDN()
	{
	    $ph='';
				
		if (getenv('HTTP_X_UP_CALLING_LINE_ID'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('HTTP_X_UP_CALLING_LINE_ID')));
		}elseif (getenv('HTTP_MSISDN'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('HTTP_MSISDN')));
		}elseif (getenv('X_UP_CALLING_LINE_ID'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('X_UP_CALLING_LINE_ID')));
		}elseif (getenv('HTTP_X_MSISDN'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('HTTP_X_MSISDN')));
		}elseif (getenv('X-MSISDN'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('X-MSISDN')));
		}elseif (getenv('X_MSISDN'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('X_MSISDN')));
		}elseif (getenv('MSISDN'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('MSISDN')));
		}elseif (getenv('X-UP-CALLING-LINE-ID'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('X-UP-CALLING-LINE-ID')));
		}elseif (getenv('X_WAP_NETWORK_CLIENT_MSISDN'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('X_WAP_NETWORK_CLIENT_MSISDN')));
		}elseif (getenv('HTTP_X_HTS_CLID'))
		{
			$ph=strip_tags(CleanPhoneNo(getenv('HTTP_X_HTS_CLID')));
		}
		
		return $ph;
	}
	
	function CleanPhoneNo($phone)
	{
		if ($phone)
		{
			$new='';
			
			$first=$phone[0];
			$code=trim(substr($phone,0,4));
			
			if (($first=='+') && ($code=='+234'))
			{
				$new=str_replace('+','',$phone);
			}elseif ($first=='0')
			{
				$new='234'.substr($phone,1);
			}elseif (($first=='2') && (trim(substr($phone,0,3))=='234'))
			{
				$new=$phone;
			}
			
			//$ret=$first.' : '.$code.' => '.$new;
			
			return $new;
		}else
		{
			return '';
		}
	}
?>