<?php
session_start();
//echo "<html>";
//<center>
//<br>
//<!--<h4 style="opacity:0.3;">Process Output</h4>-->
//<!--
//Author of this image:
//http://en.wikipedia.org/wiki/User:Zephyris
//-->
//<!--
//<img src="http://upload.wikimedia.org/wikipedia/commons/d/da/A-DNA_orbit_animated.gif">
//-->


//</center>
//</body>

$ini = parse_ini_file("../config.ini.php", true);
$def_path = $ini['login']['default'];
$var_value = $_SESSION['varname'];
$subdirectories = $ini['filepaths']['subdirectories'];
$user = $_SESSION['user_name'];
$analysis = $_SESSION['analysis'];

$file = "$subdirectories/diffexpress_output/$user/$analysis/cuffdiff_output/cummerbund_images/all.jpg";

 $contents = file_get_contents("$file");

        // Note: You should probably implement some kind 
        // of check on filetype
  header('Content-type: text/plain');
  //      header('Content-type: image/jpeg');
  echo $contents;

?>
