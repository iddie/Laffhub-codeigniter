<?php
	set_time_limit(0);	
	error_reporting(E_ALL); ini_set('display_errors', 1);	
	#$db = new mysqli('localhost', 'root', '', 'laffhubdb');
	$db = new mysqli('localhost', 'laffhub_laffuser', 'vUzm6Nh^^y*v', 'laffhub_laffhubdb');
	
	if($db->connect_errno > 0) die('Unable to connect to database [' . $db->connect_error . ']');
	
	$time_start = microtime(true);

	$cnt=0;
	
	#$sql="SELECT DATE_FORMAT(subscribe_date,'%Y-%m-%d') AS tdt, plan,
#SUM(amount) AS revenue FROM subscriptions WHERE (TRIM(network)='Airtel') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '2017-08-01' AND '2017-08-23') GROUP BY DATE_FORMAT(subscribe_date,'%Y-%m-%d'),plan ORDER BY subscribe_date DESC";


	$sdt='2017-08-01';
	$edt=date('Y-m-d');
	
	$sql="SELECT DATE_FORMAT(subscribe_date,'%Y-%m-%d') AS tdt, plan,
SUM(amount) AS revenue FROM subscriptions WHERE (TRIM(network)='Airtel') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$sdt."' AND '".$edt."') GROUP BY DATE_FORMAT(subscribe_date,'%Y-%m-%d'),plan ORDER BY subscribe_date DESC";



	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
	$totalrevenue=0; $total20=0; $total100=0; $total200=0; $total500=0;
	$arr=array(); $rec=array(); $plan=''; $revenue='';
		
	if ($query->num_rows > 0 )#Insert
	{
		$cdt='';
		
		while($row = $query->fetch_assoc())
		{#print_r($row); echo '<br>';
			$plan=$row['plan'];
			$rev=$row['revenue'];
			$dt=$row['tdt'];
			
			if (multi_in_array($plan,$rec)===false)	$rec[$dt][$plan]=$rev;
		}
		
		if (count($rec) > 0)
		{#[2017-08-17] => Array ( [Daily] => 26200 [Monthly] => 200 ) 
			foreach($rec as $key => $item):
				$totalrevenue=0; $total20=0; $total100=0; $total200=0; $total500=0;
				
				if (is_array($item))
				{
					foreach($item as $plan => $amt):						
						if (strtolower(trim($plan))=='daily') $total20 = $amt;
						if (strtolower(trim($plan))=='weekly') $total100 = $amt;
						if (strtolower(trim($plan))=='monthly') $total200 = $amt;
						if (strtolower(trim($plan))=='unlimited') $total500 = $amt;
						
						$totalrevenue = $total20 + $total100 + $total200 + $total500;
					endforeach;
					
					########Insert into airtel_daily_revenue
							
					
					$sql="SELECT id FROM airtel_daily_revenue WHERE (DATE_FORMAT(subscribe_date,'%Y-%m-%d') = '".$key."')";

					if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
						
					if ($query->num_rows == 0 )#Insert
					{
						$db->autocommit(FALSE);
						$sql='INSERT INTO airtel_daily_revenue SET subscribe_date=?,revenue=?,N20total=?,N100total=?,N200total=?,N500total=?';				 
						$stmt = $db->prepare($sql);
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						$stmt->bind_param('sddddd',$key,$totalrevenue,$total20,$total100,$total200,$total500);				
						$stmt->execute();					
						$db->commit();	
					}else
					{
						$db->autocommit(FALSE);
						
						$sql="UPDATE airtel_daily_revenue SET revenue=?,N20total=?,N100total=?,N200total=?,N500total=? WHERE DATE_FORMAT(subscribe_date,'%Y-%m-%d')=?";
										 
						$stmt = $db->prepare($sql);
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						$stmt->bind_param('ddddds',$totalrevenue,$total20,$total100,$total200,$total500,$key);				
						$stmt->execute();					
						$db->commit();
					}
				}
			endforeach;
		}
	}
	
	$time_end = microtime(true);
		
	$execution_time = ($time_end - $time_start);
	
	#print_r($rec);
		
	echo '<br>Records Saved!<br><br><b>Total Execution Time:</b> '.$execution_time.' Sec';
	
	
	function multi_in_array($value, $array) 
	{ 
		foreach ($array as $item) 
		{ 
			if (!is_array($item)) 
			{ 
				if ($item == $value) 
				{ 
					return true;
				} 
				continue; 
			} 
	
			if (in_array($value, $item)) 
			{ 
				return true; 
			} 
			else if (multi_in_array($value, $item)) 
			{ 
				return true; 
			} 
		} 
		return false; 
	} 

?>