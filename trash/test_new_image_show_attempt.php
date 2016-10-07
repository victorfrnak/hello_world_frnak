<?php
$file = '/opt/apache2/frankdec/subdirectories/diffexpress_output/allenhub/analysis_December_14_test/cuffdiff_output/cummerbund_images/all.png';
$im = imagecreatefrompng("$file");

header('Content-Type: image/png');

imagepng($im);
echo $im;
imagedestroy($im)
?>
