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
if(empty($_SESSION['user_name']) && !($_SESSION['user_is_logged_in']))
{
  #header('Location: index.php');
}
?>
<!--
##########
# Header #
##########
-->

<head>
<title>
MInotauR:"Run to the passage while he storms, ’tis well that thou descend.."
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/buttonStyle.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
</head>
<body style="background: url(images/frnak.png) bottom left no-repeat fixed;">
<center>
<!--
###########################
# Formatting Box & Legend #
###########################
-->
<style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
    }
</style>
<div>
<fieldset class="fieldset-auto-width">
<legend>
<h3>
<!img src="/favicon.png" alt="fRNAk" width="24" height="24"> 
Welcome to fRNAkenstein!
<!img src="/favicon.png" alt="fRNAk" width="24" height="24">
</h3>
</legend>

<table style="margin: 0px;">
<tr>
<td colspan="3" valign="middle" bgcolor="#d0eace" style="border:outset;padding-top:24px;padding-bottom:24px;padding-left:24px;padding-right:12px;width:500px;height=160px;">

A lightweight, clean, and easy-to-use interface for RNA sequencing 
and differential expression using state-of-the-art tools to turn
your data into visible results.
<div align="right">-The fRNAkenstein Team </div>

</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:24px;padding-left:8px;width:50px;">
<a href="instructions.php" class="fRNAkbutton">Instructions</a>

</td>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
<b>Step 1:</b> Learn about fRNAkenstein's included tools and 
how to use the front-end interface step-by-step.
</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="mapcount.php" class="fRNAkbutton">MapCount</a>

</td>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
<b>Step 2:</b> Align RNA sequencing reads to the reference file 
using Tophat and Cufflinks from the Tuxedo Suite.
</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="diffexpress.php" class="fRNAkbutton">DiffExpress</a>
</td>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
<b>Step 3:</b> Calculate differential expression levels using an array of tools including 
Cuffdiff, EdgeR, DESeq2, and Bayseq, then combine the results.
</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="http://bigbird.anr.udel.edu/~sunliang/pathway/cyto.php" class="fRNAkbutton">Visualize Data</a>

<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
<b>Step 4:</b> Visualize differential expression 
data using a variety of visualization tools. 
</td>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="status.php" class="fRNAkbutton">Status</a>

<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
View the various output and error logs of your data runs in real-time using the run ID provided in each tool.
</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="contact.html" class="fRNAkbutton">About & Contact</a>

<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
Contact information for the fRNAkenstein team and references to the tools used.
</td>
</tr>

</table>

<!--
##########
# Footer #
##########
-->

</link>
<br>
<div style="display:inline-block;float:left;">
<!img src="images/chicken.jpg" alt="NSF" width="160" height="125" > </td>
</div>
<div style="display:inline-block;float:right;">
<br><br>
<p align="right" ><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>

</div>

</fieldset>
</div>
<br><br>
<img src="images/chicken.jpg" alt="SchmidtLab" width="160" height="125" > </td>
<img src="images/USDA.jpg" alt="USDA" width="266" height="125"> 
<img src="images/NSF.jpg" alt="NSF" width="125" height="125"> <br>
<p align="center" ><font size="1">- NSF award: 1147029 :: USDA-NIFA-AFRI: 2011-67003-30228 - </font></p>
<form method="get" action="/menu_EE.php">
    <button type="submit" style="position:absolute;right:0px;bottom:0px;background: transparent;border: none !important;width:170px;height:170px;font-size:0;"></button>
</form>
</body>



