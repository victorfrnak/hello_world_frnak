<?php
$analysis = strip_tags (htmlspecialchars(escapeshellcmd($_GET['analysis'])));
$getResults = strip_tags (htmlspecialchars(escapeshellcmd($_GET['fetchResults'])));
//echo "$analysis is the analysis \n";
//var_dump($_POST);
//echo $getResults;
//echo "hello there, we are downloading now !!";

$name = "$analysis" . ".txt";
//echo "$analysis is the analysis";
if(file_exists("$getResults"))
{
        // Note: You should probably do some more checks 
        // on the filetype, size, etc.
//        $contents = file_get_contents("$getResults");
	


        // Note: You should probably implement some kind 
//        // of check on filetype
  //      header("Content-type: text/plain; filename=test_this");
//        echo $contents;
//	$contents = file_get_contents("$getResults");
//	header('Content-Disposition: filename='.$analysis);


//	header('Content-Disposition: attachment; filename='.$analysis);
//	echo $contents;

//	header('Content-Disposition: attachment; filename="downloaded.txt"');
	header('Content-Disposition: attachment; filename='."\"$name\"");
	readfile($getResults);
}

/*
$toScan = "$subdirectories_for_interface/diffexpress_output/$analysis/images";
$files = scandir("$toScan");
$imagePath = 'frnak_analysis_images';


#make the directory for  the output if not exists
if (!file_exists($imagePath))
{
    mkdir($imagePath, true);
}

foreach($files as $file)
{
	if ($file !== "." and $file !== "..")
	{
		$pattern = "/(.*).png/";
		
		preg_match($pattern, $file, $matches);
		$title = $matches[1];
		system("cp $toScan/$file /var/www/html/frnakenstein/$imagePath");
		if(strlen($title) > 0)
		{				
			echo "<h2><center>".$title."</center></h2>";
			echo "<td><img src=\"$imagePath/$file\" alt=\"fRNAkenstein\" width=\"480\" > </td> <br> <br>";
			echo "<a href=\"$imagePath/$file\" download=\"$title\" title=\"Download\"><center>Download this image</center></a><br><br><hr>";
		}	
	}
}

#http://localhost/diag_test_diagram
*/
?>
 
