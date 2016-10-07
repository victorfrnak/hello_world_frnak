<?php
$file = '/opt/apache2/frankdec/subdirectories/diffexpress_output/allenhub/analysis_December_14_test/cuffdiff_output/cummerbund_images/all.png';


if(file_exists("$file"))
{
        // Note: You should probably do some more checks 
        // on the filetype, size, etc.
        $contents = file_get_contents("$file");

        // Note: You should probably implement some kind 
        // of check on filetype
        //header('Content-type: text/plain');
        header('Content-type: image/jpeg');
        echo $contents;
}
?>
