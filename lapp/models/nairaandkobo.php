<?php

function Words_1_999($num)
 {
        $hundreds = Int($num,100);
		$remainder = $num - $hundreds * 100;
      
        if ($hundreds > 0)
		{
            $result = Words_1_19($hundreds) . " hundred and ";
		}

        if ($remainder > 0)
		{
            $result = $result . Words_1_99($remainder);
		}

        return trim($result);
}

//Return a word for this value between 1 and 19.
function Words_1_19($num)
{
	    switch ($num):
            case(1):
                return "one";
            case(2):
                return "two";
            case (3):
                return "three";
            case (4):
                return "four";
            case (5):
                return "five";
            case (6):
                return "six";
            case (7):
                return "seven";
            case (8):
                return "eight";
            case (9):
                return "nine";
            case (10):
                return "ten";
            case (11):
                return "eleven";
            case (12):
                return "twelve";
            case (13):
                return "thirteen";
            case (14):
                return "fourteen";
            case (15):
                return "fifteen";
            case (16):
                return "sixteen";
            case (17):
                return "seventeen";
            case (18):
                return "eightteen";
            case (19):
                return "nineteen";
        endswitch;
}

//Return a word for this value between 1 and 99.
function Words_1_99($num)
{
        //result As String
        //$tens As Integer

        $tens = Int($num,10);
		
        if ($tens <= 1)
		{
            // 1 <= $num <= 19
            $result = $result . " " . Words_1_19($num);
		}
        else
		{
            // 20 <= $num
            // Get the $tens digit word.
            switch ($tens):
                case (2):
                    $result = "twenty";
					break;
                case (3):
                    $result = "thirty";
					break;
                case (4):
                    $result = "forty";
					break;
                case (5):
                    $result = "fifty";
					break;
                case (6):
                    $result = "sixty";
					break;
                case (7):
                    $result = "seventy";
					break;
                case (8):
                    $result = "eighty";
					break;
                case (9):
                    $result = "ninety";
					break;
           endswitch;

            // Add the ones digit number.
            $result = $result . " " . Words_1_19($num - $tens * 10);
		}

        return trim($result);
}

//Return a string of words to represent the
//integer part of this value.
function Words_1_all($num)
{
        //Initialize the power names and values.
        $power_name[] = "trillion" ; $power_value[] = 1000000000000;
        $power_name[] = "billion" ; $power_value[] = 1000000000;
        $power_name[] = "million" ; $power_value[] = 1000000;
        $power_name[] = "thousand" ; $power_value[] = 1000;
        $power_name[] = "" ; $power_value[] = 1;

        For ($i = 0;$i<count($power_name);$i++)
		{
            //See if we have digits in this range.
            if ($num >= $power_value[$i])
			{
                //Get the digits.
				$digits = Int($num,$power_value[$i]);

                // Add the digits to the result.
                if (strlen($result) > 0) $result = $result . ", ";
                $result = $result . Words_1_999($digits) . " " . $power_name[$i];

                //Get the number without these digits.
                $num = $num - $digits * $power_value[$i];
			}
		}

       return trim($result);
}

//Return a string of words to represent this
//Decimal value in Naira and kobo.
function MoneyInWords($num)
{
        //Naira As Decimal
        //kobo As Integer
        //Naira_result As String
        //kobo_result As String
		$num=str_replace(",","",$num);
        //Naira.
        $Naira = GetNaira($num);
		
        $Naira_result = Words_1_all($Naira);
        if (strlen($Naira_result) == 0) $Naira_result = "zero";

        if ($Naira_result == "One") 
		{
			$Naira_result = $Naira_result . " Naira";
		}
        else
		{
            $Naira_result = $Naira_result . " Naira";
		}

        //kobo.
        $kobo = GetKobo($num);
        $kobo_result = Words_1_all($kobo);
        if (strlen($kobo_result) == 0) $kobo_result = "zero";

        if ($kobo_result == "One")
		{
            $kobo_result = $kobo_result . " Kobo";
		}
        else
		{
            $kobo_result = $kobo_result . " Kobo";
		}
        
        if (getLastStr($Naira_result," and Naira")==' and Naira') 
		{
            for ($a = 0;$a<=9;$a++)
			{
                //$Naira_result = Mid($Naira_result, 1, Len(Naira_result) - 1)
				$Naira_result=substr($Naira_result,0,strlen($Naira_result)-1);
			}
		}
		
        $Naira_result = $Naira_result . " Naira";		
		$Naira_result=str_replace("Naira Naira","Naira",$Naira_result);

        $res = ucfirst($Naira_result . ", " . $kobo_result);		
		$res=str_replace(", zero Kobo"," Only.",$res);     

        return $res;
}

function getLastStr($str,$substr)
{
	return substr($str,strlen($str)-strlen($substr));
}

function GetNaira($num){
	$ret=explode(".",$num);
	$result=trim($ret[0]);
	
	return $result;
}

function GetKobo($num){
	$ret=explode(".",$num);
	$result=trim($ret[1]);
	
	if (strlen($result)==1) $result=$result.'0';
	return $result;
}

function Int($num,$divisor)
{
	$remainder=$num % $divisor;
	$result=($num-$remainder)/$divisor;
	
	return $result;
}
?>