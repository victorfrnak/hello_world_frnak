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
MInotauR:"Run to the passage while he storms, 'tis well that thou descend.."
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="STYLESHEET" type="text/css" href="css_dir/buttonStyle.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
</head>
<body>
<center>
<!--
###########################
# Formatting Box & Legend #
###########################
-->
<div>
<table style="margin: 0px;">
<tr>
<th colspan="3" >
<img src="images/minotaur_banner.png" alt="MInotauR" width="550" > </td> <br> <br>

</th>
</tr>
<tr>
<td colspan="3" class="menu_header">

A lightweight, clean, and easy-to-use interface for RNA sequencing 
and differential expression using state-of-the-art tools to turn
your data into visible results.
<div align="right">-The fRNAkenstein Team </div>

</td>
</tr>
<tr>
<td class="menu_button">
<a href="instructions.php" class="minbutton">Instructions</a>

</td>
<td class="menu_description">
<b>Step 1:</b> Learn about MInotauR's included tools and 
how to use the front-end interface step-by-step.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="stage1.php" class="minbutton">Stageone</a>

</td>
<td class="menu_description">
<b>Step 2:</b> Align RNA sequencing reads to the reference file 
using Tophat and Cufflinks from the Tuxedo Suite.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="diffexpress.php" class="minbutton">DiffExpress</a>
</td>
<td class="menu_description">
<b>Step 3:</b> Calculate differential expression levels using an array of tools including 
Cuffdiff, EdgeR, DESeq2, and Bayseq, then combine the results.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="http://bigbird.anr.udel.edu/~sunliang/pathway/cyto.php" class="minbutton">Visualize Data</a>

<td class="menu_description">
<b>Step 4:</b> Visualize differential expression 
data using a variety of visualization tools. 
</td>

<tr>
<td class="menu_button">
<a href="status.php" class="minbutton">Status</a>

<td class="menu_description">
View the various output and error logs of your data runs in real-time using the run ID provided in each tool.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="contact.html" class="minbutton">About & Contact</a>

<td class="menu_description">
Contact information for the Fable team and references to the tools used.
</td>
</tr>

</table>

<!--
##########
# Footer #
##########
-->

</link>

</fieldset>
<br><br>
<img src="../images/chicken.jpg" alt="SchmidtLab" width="160" height="125" > </td>
<img src="../images/USDA.jpg" alt="USDA" width="266" height="125"> 
<img src="../images/NSF.jpg" alt="NSF" width="125" height="125"> <br>
<p align="center" ><font size="1">- NSF award: 1147029 :: USDA-NIFA-AFRI: 2011-67003-30228 - </font></p><br>
<p align="center" ><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>
</div>
</body>



