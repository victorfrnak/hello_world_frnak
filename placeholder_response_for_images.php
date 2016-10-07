<?php

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


$file = '/opt/apache2/frankdec/subdirectories/diffexpress_output/allenhub/analysis_December_14_test/cuffdiff_output/cummerbund_images/all.png';

//get the files as a session variable

if(file_exists("$file"))
{
        // Note: You should probably do some more checks 
        // on the filetype, size, etc.
        $contents = file_get_contents("$file");

        // Note: You should probably implement some kind 
        // of check on filetype
        header('Content-type: text/plain');
        //header('Content-type: image/jpeg');
        echo $contents;
}
?>
