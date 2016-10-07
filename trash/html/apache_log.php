<?php

session_start();

if(!($_SESSION['user_name'] == 'fRNAkadmin' || $_SESSION['user_name'] == 'toast'))
{
  header('Location: index.php');
}

$logfilepath = "/var/log/apache2/error.log";

$fh = fopen($logfilepath, 'r');
if($fh){
	$pageText = fread($logfilepath, filesize($logfilepath));

	#converts newlines to <br>
	echo nl2br($pageText);
} 
else
{
	echo "If this is empty, fRNAkenstein doesn't have access to the log file";
}
?>
