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
if(empty($_SESSION['user_name']))
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
<title>
fRNAkenstein:"Beware; for I am fearless, and therefore powerful."
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="STYLESHEET" type="text/css" href="css_dir/buttonStyle.css">
<!link rel="STYLESHEET" type="text/css" href="css_dir/background.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
</head>
<body >
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
<h3 style="background-color:white;">
<!img src="/favicon.png" alt="fRNAk" width="24" height="24"> 
Welcome to fRNAkenstein!
<!img src="/favicon.png" alt="fRNAk" width="24" height="24">
</h3>
</legend>

<table style="margin: 0px;">
<tr>
<td colspan="3" valign="middle" bgcolor="#d0eace" style="border:outset;padding-top:24px;padding-bottom:24px;padding-left:24px;padding-right:12px;width:500px">

"One man's life or death were but a small price to pay for the <br>
acquirement of the knowledge which I sought, for the dominion I should <br>
acquire and transmit over the elemental foes of our race."

</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="mapcount.php" class="fRNAkbutton">MapCount</a>

</td>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
"Life, although it may only be an accumulation of <br>
anguish, is dear to me, and I will defend it."
</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="diffexpress.php" class="fRNAkbutton">DiffExpress</a>
</td>

<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
"...learn from my miseries, and do not seek to <br>
increase your own."
</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="#" class="fRNAkbutton">Visualize Data</a>

<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
"There is something at work in my soul, which I <br>
do not understand."
</td>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="status.php" class="fRNAkbutton">Status</a>

<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
"Thus strangely are our souls constructed, and <br>
by slight ligaments are we bound to prosperity and ruin."
</td>
</tr>

<tr>
<td valign="middle"  style="padding-top:12px;padding-left:8px;width:50px;">
<a href="contact.html" class="fRNAkbutton">About & Contact</a>

<td valign="middle"  style="padding-top:12px;padding-left:8px;width:400px">
"It is true, we shall be monsters, cut off from all <br>
the world; but on that account we shall be more <br>
attached to one another."
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

<p align="right" ><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>
</fieldset>
</div>
</body>

