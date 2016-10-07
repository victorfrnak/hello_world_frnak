<?php
$ini = parse_ini_file("../config.ini.php", true);
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];
#echo $subdirectories . "is subdirectories !!!";
#echo "hello this message is working !!";
$key = $ini['login']['key'];
$secret = $ini['login']['secret'];

session_start();

$analysis = strip_tags (htmlspecialchars(escapeshellcmd($_GET['analysis'])));
$AgaveFiles = strip_tags (htmlspecialchars(escapeshellcmd($_GET['fetchResults'])));

echo "$analysis" . "is the analysis !!";

$user = $_SESSION['user_name'];

///Scan all of the folders in the chosen analysis


//temportary comment out getResults and hard code
//$getResults = strip_tags (htmlspecialchars(escapeshellcmd($_GET['fetchResults'])));

$getResults = "/storage2/allenhub/subdirectories/allen_sam_test.txt";

//echo "$analysis is the analysis \n";
//var_dump($_POST);
//echo $getResults;
//echo "hello there, we are downloading now !!";

$name = "$analysis" . ".txt";
$token = $_SESSION['access_token'];

echo "$token";
echo "is the token now !!";

//echo "$analysis is the analysis";
if(file_exists("$getResults"))
{
        // Note: You should probably do some more checks 
        // on the filetype, size, etc
	header('Content-Disposition: attachment; filename='."\"$name\"");
	readfile($getResults);
}


//echo "<script> checkFormat(\"/opt/apache2/frankdec/subdirectories/genome_directory/\"); </script>\n";


//$fqfiles = scandir("$subdirectories/uploads" . "/" . $_SESSION['user_name']);
//$fqfiles = scandir("$subdirectories/fastq_directory");
echo $token;
echo "is the token !! \n \n";

$authorization = "Authorization: Bearer $token";

//$ch = curl_init("https://agave.iplantc.org:443/files/v2/history/allenhub/mapcount_output/mapcount_output/info_ex1.fq");

//$ch = curl_init("https://agave.iplantc.org:443/files/v2/media/allenhub/ex1.fastq");


//$ch = curl_init("https://agave.iplantc.org:443/files/v2/media/allenhub/mapcount_output/mapcount_output/library_ex1.fq/ex1.fq.sam");

$ch = curl_init("https://agave.iplantc.org:443/files/v2/media/$AgaveFiles");
echo "https://agave.iplantc.org:443/files/v2/media/$AgaveFiles";
echo " is the path to the files on agave !!! \n";

//allenhub/mapcount_output/mapcount_output/library_ex1.fq/ex1.fq.sam

//echo "made the change !! \n \n";

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
//echo "$result is the result !!! \n";

curl_close($ch);
print_r($result);

$output_filename = "/storage2/allenhub/subdirectories/allen_sam_test.txt";

$fp = fopen($output_filename, 'w');
fwrite($fp, $result);
fclose($fp);

/*
$theDictionary = json_decode($result);
//echo "we closed !!! \n \n";

//var_dump(json_decode($result, true));
//echo " is the dictionary !!! \n \n"; 

//return json_decode($result);
//echo "hello there we have decoded !!";

$filesFromDataStore = array();

$obj = json_decode($result, TRUE);
for($i=0; $i<count($obj["result"]); $i++)
{
//        echo "Rating is " . $obj["result"][$i]["name"];
        $file = "";
        $file = $obj["result"][$i]["name"];
        //echo $file . " is the file about to added \n \n";
//        echo $file . " is the file !!! \n \n";
        array_push($filesFromDataStore, $file);

// . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";
}



//once we have the analysis, let's look at their stuff

//Parse the information in the text file from the api
$authorization = "Authorization: Bearer $token";

//we know that it must be mapcount
if (strpos($analysis, 'analysis') !== false) {
    $ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/$user/mapcount_output/$analysis");
}

//we know that it's diffexpress
else
{
	$ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/$user/diffexpress_out/$analysis");
}

//echo "made the change !! \n \n";
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
//echo "$result is the result !!! \n";

curl_close($ch);
$theDictionary = json_decode($result);
//echo "we closed !!! \n \n";

//var_dump(json_decode($result, true));
//echo " is the dictionary !!! \n \n"; 

//return json_decode($result);
//echo "hello there we have decoded !!";

$filesFromDataStore = array();
$obj = json_decode($result, TRUE);
for($i=0; $i<count($obj["result"]); $i++)
{
//        echo "Rating is " . $obj["result"][$i]["name"];
        $file = "";
        //echo $file . " the file \n \n";
        $file = $obj["result"][$i]["name"];
        //echo $file . " is the file about to added \n \n";
        //echo $file . " is the file !!! \n \n";
        array_push($filesFromDataStore, $file);

// . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";
}


$libFiles = $filesFromDataStore;
echo $filesFromDataStore[1];

echo json_decode($libFiles);
echo "is what is available";



//$toScan = "$subdirectories_for_interface/diffexpress_output/$analysis/images";
//$toScan = $filesFromDataStore;

//$files = scandir("$toScan");
//$imagePath = 'frnak_analysis_images';
$files = $filesFromDataStore;

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
 
