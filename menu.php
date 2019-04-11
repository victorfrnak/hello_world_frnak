<!--
######################################
# fRNAkenstein                       #
#   by Wayne Treible & Allen Hubbard #
#                                    #
# Version 1.00 Updated 7/28/2014     #
######################################
-->

<?php

$ini = parse_ini_file("../config.ini.php", true);
$admin = $ini['login']['admin'];
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];
session_start();
//echo "modified, doing scan !!";
$user = $_SESSION['user_name'];
$token = $_SESSION['access_token'];

if ( (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) || 
   (isset($_SESSION['SESSION_TIMEOUT']) && (time() - $_SESSION['SESSION_TIMEOUT'] > 5400)) ) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time      
    session_destroy();   // destroy session data in storage                 
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if(empty($_SESSION['user_name']) && !($_SESSION['user_is_logged_in']))
{
  header('Location: '.$def_path);
}

//make the user's bash directory
$bashdir = "$subdirectories/bash_scripts/".$_SESSION['user_name'];

if (file_exists($bashdir) == FALSE)
{
	mkdir($bashdir, 0777);
	chmod($bashdir, 0777);
}

//make the user's directory and their top ten directory
$topTenDir = "$subdirectories/genome_directory/".$_SESSION['user_name']."/big_ten";
$userDir = "$subdirectories/genome_directory/".$_SESSION['user_name'];
if (file_exists($userDir) == FALSE)
{
	mkdir($bashdir, 0777);
	chmod($bashdir, 0777);
}
if (file_exists($topTenDir) == FALSE)
{
	exec("cp -r $subdirectories/genome_directory/big_ten $topTenDir");
        //mkdir($bashdir, 0777);
        //chmod($bashdir, 0777);
}

$userDiffExpress = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'];
mkdir($userDiffExpress, 0777);
chmod($userDiffExpress, 0777);
if (file_exists($userDiffExpress) != 1)
{
        mkdir($userDiffExpress, 0777, true);
        chmod($userDiffExpress, 0777);
	
}

$authorization = "Authorization: Bearer $token";

$ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/$user");

//echo "made the change !! \n \n";
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
//echo "$result is the result !!! \n";

curl_close($ch);
$theDictionary = json_decode($result);
$filesFromDataStore = array();
$obj = json_decode($result, TRUE);

for($i=0; $i<count($obj["result"]); $i++)
{
//        echo "Rating is " . $obj["result"][$i]["name"];
        $file = "";
        $file = $obj["result"][$i]["name"];
        echo $file . " is the file about to added \n \n";
//        echo $file . " is the file !!! \n \n";
        array_push($filesFromDataStore, $file);

// . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";
}


echo "scanning the file !!! \n \n";
if (in_array("1_and_others_in_job", $filesFromDataStore)) {
    echo "Got It";
}
else
{
	//we'll need to make the frnak directories
	echo "we'll need to make the directories !!!";

	///make mapcount
	curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/$user?pretty=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=$user/mapcount_output");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        $headers = array();
        $headers[] = "Authorization: Bearer $token";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



	//make genome
        curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/$user?pretty=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=$user/genome_directory");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        $headers = array();
        $headers[] = "Authorization: Bearer $token";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	//make diffexpress
	curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/$user?pretty=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=$user/diffexpress_output");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        $headers = array();
        $headers[] = "Authorization: Bearer $token";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



}

?>

<!--
##########
# Header #
##########
-->

<head>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="STYLESHEET" type="text/css" href="css_dir/buttonStyle.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>

<title>
fRNAkenstein:"Beware; for I am fearless, and therefore powerful."
</title>
</head>
<body>
<center>
<!--
###########################
# Formatting Box & Legend #
###########################
-->
<div class='container'>

<table>
<th colspan="3" >
<img src="images/frnak_banner.png" alt="fRNAkenstein" width="600" > </td> <br> <br>

</th>
<tr>
<td colspan="3" class="menu_header">

A lightweight, clean, and easy-to-use interface for RNA sequencing 
and differential expression using state-of-the-art tools to turn
data into visible results.
<div align="right">-The fRNAkenstein Team <a href="contact.html">(About)</a></div>
<a href="logout.php">Logout</a>
</td>
</tr>

<tr>
<td class="menu_button">
<a href="instructions.php" class="fRNAkbutton">Instructions</a>

</td>
<td class="menu_description">
<b>Step 1:</b> Learn about fRNAkenstein's included tools and 
how to use the front-end interface step-by-step.  Includes links to original papers and their citations.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="upload/" class="fRNAkbutton">File Manager</a>

</td>
<td class="menu_description">
<b>Step 2:</b> Upload and manage your experiment files.
</td>  
</tr>

<tr>
<td class="menu_button">
<a href="mapcount.php" class="fRNAkbutton">MapCount</a>

</td>
<td class="menu_description">
<b>Step 3:</b> Align RNA sequencing reads to the reference file 
using Tophat and Cufflinks from the Tuxedo Suite.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="diffexpress.php" class="fRNAkbutton">DiffExpress</a>
</td>
<td class="menu_description">
<b>Step 4:</b> Calculate differential expression levels using an array of tools including 
Cuffdiff, EdgeR, DESeq2, and Bayseq, then combine the results.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="visualize_menu.php" class="fRNAkbutton">Retrieve Data</a>

<td class="menu_description">
<b>Step 5:</b> Visualize differential expression 
data using a variety of visualization tools. 
</td>

<tr>
<td class="menu_button"">
<a href="status.php" class="fRNAkbutton">Status</a>

<td class="menu_description">
View the various output and error logs of your data runs in real-time using the run ID provided in each tool.
</td>
</tr>
<!--
<tr>
<td class="menu_button">
<a href="contact.html" class="fRNAkbutton">About & Contact</a>

<td class="menu_description">
Contact information for the fRNAkenstein team and references to the tools used.
</td>
</tr>
-->
</table>

<!--
##########
# Footer #
##########
-->

</link>
<br>
<br>
<img src="images/chicken.jpg" alt="SchmidtLab" width="160" height="125" > </td>
<img src="images/USDA.jpg" alt="USDA" width="266" height="125"> 
<img src="images/NSF.jpg" alt="NSF" width="125" height="125"> <br>
<!img src="images/CoGe.png" alt="CoGe" width="200" height="100">
<!img src="images/iPlant.png" alt="iPlant" width="500" height="125" style="padding-left:30px;"> <br>

<p align="center" ><font size="1">- NSF award: 1147029 :: USDA-NIFA-AFRI: 2011-67003-30228 - </font></p><br><br>
<p align="right" ><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>
</div>


<form method="get" action="/menu_EE.php">
    <button type="submit" style="position:absolute;right:0px;bottom:0px;background: transparent;border: none !important;width:170px;height:170px;font-size:0;"></button>
</form>
</body>



