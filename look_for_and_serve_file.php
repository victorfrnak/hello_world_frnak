<script>
//$(document).ready(function(){
document.onload{

alert("hello there");

}
</script>
<?php
    $file = '/opt/apache2/frankdec/subdirectories/diffexpress_output/allenhub/analysis_December_14_test/cuffdiff_output/cummerbund_images/all.jpeg';
    //header('Content-Type: image/jpeg');
    //header('Content-Length: ' . filesize($file));
    //echo file_get_contents($file);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
    //header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
    //header("Content-Type: application/force-download");
    //header("Content-Length: " . filesize($file));
    //header("Connection: close");
?>
