<?php
echo "about to send !! ";
//exec("bash /opt/apache2/frankdec_dev/new_agave/foundation-cli/bin/files-upload -v -F picksumipsumII.txt -S data.iplantcollaborative.org allenhub");
echo "put the file through";


// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/allenhub?pretty=true");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=mystuff");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");


$headers = array();
$headers[] = "Authorization: Bearer 112919b7b8c7d2f53c727eb18bffc22";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

echo  "$result !!"; 



?>
