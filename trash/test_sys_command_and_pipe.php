<?php


//$descriptorspec = array(
    //0 => array("pipe", "r"),
   // 1 => array("pipe", "w"),
  //  2 => array("file", "/tmp/error-output.txt", "a") );

//$process = proc_open("time ./a a.out", $descriptorspec, $pipes);

//echo stream_get_contents($pipes[1]);
//fclose($pipes[1]);
//system_exec('/opt/apache2/frankdec/agave-cli/bin/tenants-init');
$handle = popen('/opt/apache2/frankdec/agave-cli/bin/auth-tokens-create -v -S -u frnakenstein -p Hemogoblin3! -s _vFRUb2x4gA60AIshnWf1cC03uca -k 7YRrIIGG283UWRv3C7qR4EZhx9ka>&1', "r");
//$handle = popen('/opt/apache2/frankdec/agave-cli/bin/bash GET_MY_KEY.sh');
$data = fgets($handle);
echo "> ".$data . "is data!";
?>
