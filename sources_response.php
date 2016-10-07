
<?php
if(file_exists("$getResults"))
{
        // Note: You should probably do some more checks 
        // on the filetype, size, etc.
        $contents = file_get_contents("$getResults");

        // Note: You should probably implement some kind 
        // of check on filetype
        header('Content-type: text/plain');
        echo $contents;
}
?>