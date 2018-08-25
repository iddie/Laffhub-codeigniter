<?php
	date_default_timezone_set('Africa/Lagos');
	
	#Mon 26 Sep 2016 13:20:01 +0100 
	
	#echo date('D d M Y H:i:s O');
	
	// movie.avi.... name of the movie you want to take a screenshot from 
// 00:00:00.... Where in the movie do you wanna take your screenshot, 10 seconds from start? ex: 00:00:10 
// picname..... name your generated pic 

#$vid="video_files/bigbuck.mp4";
#$thumb_png="video_files/bigbuck.png";
#$thumb_jpg="video_files/bigbuck.jpg";

#exec("START C:\CodeIgniter\ffmpeg\bin -vcodec png -i ".$vid." -ss  00:00:00 -vframes frames ".$thumb_png);
#exec("START C:\CodeIgniter\ffmpeg\bin -vcodec jpg -i ".$vid." -ss  00:00:00 -vframes frames ".$thumb_jpg);
#$tm=date("Y-m-d H:i:s");
#$secs=strtotime($tm);

#$j=0;
#for($i=0; $i<1; $i++) sleep(3);

#$tdt=date("Y-m-d H:i:s");

#$td=strtotime($tdt);

#echo $tm.'<br>'.$tdt.' <br> '.$secs.'<br>'.$td.'<br>'.($td-$secs);

require 'SimpleImage.php';

$video='mov.mp4';
$thumbnail='mov.jpg';


#$cmd =  "ffmpeg -ss 00:00:15 an -y -i $video -vf scale=200:-1 -vframes 1 $thumbnail";

#$cmd="ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vframes 1 -vf fps=1 -f mjpeg $thumbnail 2>&1";
$cmd="ffmpeg -i $video -ss 00:00:03.75 -vf scale=200:-1 -q:v 4 -y -strict experimental -threads 1 -an -r 1 -vframes 1 $thumbnail 2>&1";
#$cmd="ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:02 -r 1 -y -vframes -f png $thumbnail 2>&1";

if (shell_exec($cmd))
{
	#Reduce size to 150
	#ResizeImage($thumbnail,200);
	
	echo 'Thumbnail Created';
}else
{
	echo 'Error Creating Thumbnail!';
}

function ResizeImage($img,$newWidth)#Width In Pixels
{
	//Resize very large images to 400
	 $image_info = getimagesize($img);//index 0 is width, index 1 is heigth
					  
	 $width=$image_info[0];
	 $height=$image_info[1];
	#$file = fopen('a_idong.txt',"a"); fwrite($file,$matno." => ".$width."\n"); fclose($file);
	//Determine is image is Portrait or Landscape
	 if ($width > $newWidth)//Resize
	 { 
		$imgW = new SimpleImage();
		$imgW->load($img);	
				
		$imgW->resizeToWidth($newWidth);
		$imgW->save($img);
	}
}
?>