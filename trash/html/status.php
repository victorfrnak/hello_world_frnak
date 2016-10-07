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

session_start();

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
  header('Location: index.php');
}

$subdirectories = "/var/www/subdirectories_for_interface";

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
<fieldset class="fieldset-auto-width">
<legend>
<h3>
fRNAkenstein - Process Listing
</h3>
</legend>

<!--
#######################
# iFrame for Response #
#######################
-->
<br>
<iframe name='formresponse' src='status_response.php' style="border: outset; background-color:#d0eace " width='500px' height='500px' frameborder='0'>
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
<br> <br> 
<form action="menu.php">
    <input align = "bottom" type="submit" value="Return to Menu">
</form>

<br><br>


<!--
#######################
# Footer and clean-up #
#######################
-->

</td>
</tr>
</table>
</link>
<p align="right"><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>
</fieldset>
</body>



