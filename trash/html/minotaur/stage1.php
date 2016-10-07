<?php 
######################################
# fRNAkenstein                       #
#   by Allen Hubbard & Wayne Treible #
#                                    #
# Version 0.10 Updated 6/17/2014     #
######################################



####################################
# Required File Structure:         #
#                                  #
# subdirectories/                  #
#   --fastq_to_be_crunched/        #
#   --fasta_directory/             #
#   --annotation_directory/        #
#   --temp_output/                 #
#   --bash_scripts/                #
#   --mapcount_output/             #
#   --logs/                        #
#                                  #
# Modify $subdirectories to change #
#   the root of the file system    #
####################################

$subdirectories = "/var/www/subdirectories_for_interface";

session_start();

if(empty($_SESSION['user_name']))
{
  #header('Location: index.php');
}

?>

<head>
<title>
MInotauR - Stage1 Name
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
MInotauR - Stage1 Name
</h3>
</legend>
<form id='submitform' onsubmit="return valthisform(this);" action='/mapcount_response.php' method='post' target='formresponse'>


<input type='hidden' name='submitted' id='submitted' value='1'/>

<!--
################################
# Beginning of alignment table #
################################
-->

<table>

<!--
######################
# Checkbox Validator #
######################
-->

<script language="javascript">
function valthisform()
{
	var checkboxs=document.getElementsByName("fqfilename[]");
	var okay=false;
	for(var i=0,l=checkboxs.length;i<l;i++)
	{
		if(checkboxs[i].checked)
		{
	    okay=true;
		}
	}
	if(okay){
		document.getElementById('crunch').disabled = 1
		alert("Running MapCount on Data!");
	}
	else alert("Please select a library!");
	return okay;
}

</script>

<!--
#############################
# Row for form and response #
#############################
-->

<tr style="padding:0px; margin:0px;">
<td valign="top" style="padding-top:12px;padding-left:8px;width:300px">

<!--
################################################
# Create Checkboxes for fastq files (lib nums) #
################################################
-->

<div class='container'>


<!--
################################
# Create DDBox for fasta files #
################################
-->

<?php
$fafiles = scandir("$subdirectories/fasta_directory"); 

echo "<h4>Choose a fasta:</h4>";
if(count($fafiles)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No fasta files available! (email wtreible@udel.edu)<br>";
} else {
	echo "<select name=\"fafilename\">";
	foreach ($fafiles as $fafile)
	{
	  if (($fafile != ".") and ($fafile != ".."))
	  { 
	    echo "<option value=\"$fafile\">$fafile</option>";
	  }
	} 
	echo "</select>";
}


?>

<!--
#####################################
# Create DDBox for annotation files #
#####################################
-->

<?php

$afiles = scandir("$subdirectories/annotation_directory");

echo "<h4>Choose an annotation file:</h4>";

if(count($afiles)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No annotation files available! (email wtreible@udel.edu)<br>";
} else {
	echo "<select name=\"afilename\">";
	foreach ($afiles as $afile) 
	{
	  if(($afile != ".") and ($afile != ".."))
	  { 
	    echo "<option value=\"$afile\">$afile</option>";
	  }
	} 
	echo "</select>";
}

?>

<!--
#######################
# Annotation Selector #
#######################
-->

<h4> Annotation Type: </h4>
<div class="frnakRadio">
<div class="checkname">NCBI</div>
<input type="radio" id="frnakRadioInput" name="annotationtype" value="ncbi" checked>
<label for="frnakRadioInput"></label></div>

<div class="frnakRadio">
<div class="checkname">Ensembl</div>
<input type="radio" id="frnakRadioInput2" name="annotationtype" value="ensembl" >
<label for="frnakRadioInput2"></label></div>

<br>
<!--
###########################
# Submit and Menu Buttons #
###########################
-->

<div class='container'>
<button class="crunch" type="submit">fRNAkenstein, Crunch!</button>

<br> <br> <br>
</form>
<form action="menu.php">
    <input align="bottom" type="submit" value="Return to Menu">
</form>
</div>
</td>

<!--
#######################
# iFrame for Response #
#######################
-->

<td valign="top" style="padding-left:0px;align:left">
<br>
<iframe name='formresponse' src='placeholder_response.html' style="border: outset; background-color:#A69066" width='500px' height='500px' frameborder='0'>
</iframe>

<!--
#######################
# Footer and clean-up #
#######################
-->

</td>
</tr>
</table>
</link></form>
<p align="right"><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>
</fieldset>
</body>

