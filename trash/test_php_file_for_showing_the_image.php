
<?php
    header('Content-type: image/jpeg');
    $contents = file_get_contents("/opt/apache2/frankdec/subdirectories/diffexpress_output/allenhub/analysis_December_14_test/cuffdiff_output/cummerbund_images/all.jpg");
    // Note: You should probably implement some kind 
    // of check on filetype
    //header('Content-type: image/jpeg');
    echo $contents;

?>
