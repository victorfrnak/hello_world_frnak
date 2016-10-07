<!--
######################################
# fRNAkenstein                       #
#   by Allen Hubbard & Wayne Treible #
#                                    #
# A front-end interface for the      #
# tuxedo pipeline including Tophat,  #
# Cufflinks, and Cuffdiff.           #
#                                    #
# Version 0.10 Updated 6/18/2014     #
######################################
-->

<?php

$ini = parse_ini_file("../config.ini.php", true);
$admin = $ini['login']['admin'];
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];

session_start();

if ( (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) || 
   (isset($_SESSION['SESSION_TIMEOUT']) && (time() - $_SESSION['SESSION_TIMEOUT'] > 5400)) ) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time      
    session_destroy();   // destroy session data in storage                 
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
echo "hello !!! \n";
####################################
# Required File Structure:         #
#                                  #
# subdirectories/                  #
#   --fastq_to_be_crunched/        #
#   --fasta_directory/             #
#   --annotation_directory/        #
#   --temp_output/                 #
#   --bash_scripts/                #
#   --thcl_output/                 #
#   --logs/                        #
#                                  #
# Modify $subdirectories to change #
#   the root of the file system    #
####################################

if(empty($_SESSION['user_name']) && !($_SESSION['user_is_logged_in']))
{
  header('Location: '.$def_path);
}

?>

<head>
<title>
fRNAkenstein - Status
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
</head>
<body>
<center>
<!--
############################
# Beginning of submit form #
############################
-->
<div>
<!fieldset class="fieldset-auto-width">
<a href="menu.php"><img src="images/frnak_banner_status.png" style="border:0;padding-right:25px;" alt="fRNAkenstein" width="600" ></a> <br><br>
<!--
#######################
# iFrame for Response #
#######################
-->

<iframe name='formresponse' src='status_response.php' style="border: none; background-color:#d0eace " width='500px' height='500px' frameborder='0'>
</iframe>

<!--
############################
# Submit and Queue Buttons #
############################
-->
<br><br>
<div class="centercontainer">
<div class='status running key'></div><div class='keytext'>= Running</div>
<div class='status queued key'> </div><div class='keytext'>= Queued</div>
<div class='status failed key'></div><div class='keytext'>= Failed</div>

<br><br>

<b>Note:</b> Elapsed time is in DD-HH:MM:SS format. <br><br>Your run could be queued for up to 5 minutes<br>after a previous  process completed due to<br>clean-up and initialization.
<br> <br> <br> 
<form action="menu.php">
    <input align = "bottom" type="submit" value="Return to Menu">
</form>

<!--
#######################
# Footer and clean-up #
#######################
-->

</td>
</tr>
</table>
</link>
</fieldset>
</body>



