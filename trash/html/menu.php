<!--
######################################
# fRNAkenstein                       #
#   by Wayne Treible & Allen Hubbard #
#                                    #
# Version 1.00 Updated 7/28/2014     #
######################################
-->

<?php 
session_start();
if(empty($_SESSION['user_name']) && !($_SESSION['user_is_logged_in']))
{
  header('Location: index.php');
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
your data into visible results.
<div align="right">-The fRNAkenstein Team </div>
</td>
</tr>

<tr>
<td class="menu_button">
<a href="instructions.php" class="fRNAkbutton">Instructions</a>

</td>
<td class="menu_description">
<b>Step 1:</b> Learn about fRNAkenstein's included tools and 
how to use the front-end interface step-by-step.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="mapcount.php" class="fRNAkbutton">MapCount</a>

</td>
<td class="menu_description">
<b>Step 2:</b> Align RNA sequencing reads to the reference file 
using Tophat and Cufflinks from the Tuxedo Suite.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="diffexpress.php" class="fRNAkbutton">DiffExpress</a>
</td>
<td class="menu_description">
<b>Step 3:</b> Calculate differential expression levels using an array of tools including 
Cuffdiff, EdgeR, DESeq2, and Bayseq, then combine the results.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="http://bigbird.anr.udel.edu/~sunliang/pathway/cyto.php" class="fRNAkbutton">Visualize Data</a>

<td class="menu_description">
<b>Step 4:</b> Visualize differential expression 
data using a variety of visualization tools. 
</td>

<tr>
<td class="menu_button"">
<a href="status.php" class="fRNAkbutton">Status</a>

<td class="menu_description">
View the various output and error logs of your data runs in real-time using the run ID provided in each tool.
</td>
</tr>

<tr>
<td class="menu_button">
<a href="contact.html" class="fRNAkbutton">About & Contact</a>

<td class="menu_description">
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
<br>
<img src="images/chicken.jpg" alt="SchmidtLab" width="160" height="125" > </td>
<img src="images/USDA.jpg" alt="USDA" width="266" height="125"> 
<img src="images/NSF.jpg" alt="NSF" width="125" height="125"> <br>
<p align="center" ><font size="1">- NSF award: 1147029 :: USDA-NIFA-AFRI: 2011-67003-30228 - </font></p><br><br>
<p align="right" ><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>
</div>
<form method="get" action="/menu_EE.php">
    <button type="submit" style="position:absolute;right:0px;bottom:0px;background: transparent;border: none !important;width:170px;height:170px;font-size:0;"></button>
</form>
</body>



