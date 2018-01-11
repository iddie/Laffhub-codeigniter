<?php
set_time_limit(0);	
error_reporting(E_ALL); ini_set('display_errors', 1);
	
include('common.php');

$network='Airtel';
$dt=date('Y-m-d');

CreateFile($db,$network,$dt);

function CreateFile($db,$network,$dt)
{
	$time_start = microtime(true);
	
	$fn='renew.txt';	
	
	$sql="SELECT `LoginDate`, `Name`, `Activity`, `ActionDate`, `Username`, `Operation` FROM loginfo";

	if (!$qry = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$rows=0;
	
	while($row = $qry->fetch_assoc()):
		$ldt=''; $nm=''; $act=''; $adt=''; $un=''; $op='0';
		
		if ($row['LoginDate']) $ldt=trim($row['LoginDate']);
		if ($row['Name']) $nm=trim($row['Name']);		
		if ($row['Activity']) $act = trim($row['Activity']);
		if ($row['ActionDate']) $adt = $row['ActionDate'];
		if ($row['Username']) $un = trim($row['Username']);
		if ($row['Operation']) $op = $row['Operation'];
		
		$file = fopen($fn,"a"); fwrite($file, $ldt.",".$nm.",".$act.",".$adt.",".$un.",".$op."\n"); fclose($file);	
		
		$rows++;
	endwhile;
	
	$execution_time = (microtime(true) - $time_start);
	
	echo '<br>Total Records='.number_format($rows,0).'<br><b>Total Execution Time:</b> '.($execution_time/60).' Min';
}

?>