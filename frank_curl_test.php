<?php

$ini = parse_ini_file("../config.ini.php", true);
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];
#echo $subdirectories . "is subdirectories !!!";
#echo "hello this message is working !!";
$key = $ini['login']['key'];
$secret = $ini['login']['secret'];

session_start();

$token = $_SESSION['access_token'];
//echo $token . "is token";
$time_out = $_SESSION['SESSION_TIMEOUT'];
$user = $_SESSION['user_name'];

echo $token . " is the token !! \n \n";

//$token = $_SESSION['access_token'];


echo "hello !!! \n \n";

/** 
* Send a GET requst using cURL 
* @param string $url to request 
* @param array $get values to send 
* @param array $options for cURL 
* @return string 
*/


$authorization = "Authorization: Bearer $token";

$ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/schmidtc/Transcriptome_Delaware");

echo "made the change !! \n \n";

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    //echo "$result is the result !!! \n";

    curl_close($ch);
    $theDictionary = json_decode($result);
    echo "we closed !!! \n \n";

   var_dump(json_decode($result, true));
   echo " is the dictionary !!! \n \n"; 

    //return json_decode($result);
    echo "hello there we have decoded !!";
   //echo $theDictionary[1] . " is the dictionary at 1 !! \n \n"; 
//   foreach($theDictionary as $item) 
//   { //foreach element in $arr
//   	echo "$item is the item !!! \n \n"; 
//   	$uses = $item["raw"]; //etc
//   	echo "$uses is the list of the names !! \n \n";
 //  }

//   foreach($result as $obj){
//   echo $obj->name;
//   echo "is from the object !! \n";

 // }


//foreach ( $result-> result as $trend )
//{
//	echo {$trend-> name };
//	echo " is the name !! \n \n";
//}

//make an array for the list of the fastq.gz files coming from the API
$filesFromDataStore = array();

//$arrayOfLibsAndGenome =  array_merge($arrayOfLibsAndGenome,$libNumber);

$obj = json_decode($result, TRUE);
for($i=0; $i<count($obj["result"]); $i++)
{
	echo "Rating is " . $obj["result"][$i]["name"];
	$file = "";
	$file = $obj["result"][$i]["name"];
	echo $file . " is the file !!! \n \n";
	array_push($filesFromDataStore, $file);

// . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";
}

echo "hello well we made it to where we could go through the loop !! \n \n";
echo count($filesFromDataStore) . " is the length of the files from the data store !! \n \n";

foreach($filesFromDataStore as $aFileToDisplay)
{
	echo $aFileToDisplay . "is from the data store \n \n";
}

echo "hi \n";
$username='ABC';
$password='XYZ';
$URL='<URL>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$URL);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
$result=curl_exec ($ch);
curl_close ($ch);









$ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/schmidtc/Transcriptome_Delaware");
curl_setopt($ch,CURLOPT_HTTPHEADER,array("\"Authorization: $token\""));

//$fp = fopen("example_homepage.txt", "w");

//curl_setopt($ch, CURLOPT_FILE, $fp);
//curl_setopt($ch, CURLOPT_HEADER, "\"Authorization: Bearer $token\"");

//echo $ch . " the curl command \n ";

curl_exec($ch);
curl_close($ch);
//fclose($fp);
echo "did curl changed again !!! \n \n";

/**
$url = "https://agave.iplantc.org:443/files/v2/listings/schmidtc/Transcriptome_Delaware";

function curl_get($url, array $get = NULL, array $options = array()) 
{    
    $defaults = array( 
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
        CURLOPT_HEADER => "Authorization: Bearer $token", 
        CURLOPT_RETURNTRANSFER => TRUE, 
        CURLOPT_TIMEOUT => 4 
    ); 
    
    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 

    echo "hello is the hello we are in the function to do the query  !! \n"; 
    return $result; 
} 

echo $result . " is the result !!! \n \n";

*/
?>
