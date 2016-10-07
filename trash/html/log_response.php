<?php 
####################################
# Required File Structure:         #
#                                  #
# subdirectories/                  #
#   --fastq_to_be_crunched/        #
#   --fasta_directory/             #
#   --annotation_directory/        #
#   --temp_output/                 #
#   --bash_scripts/                #
#   --mapcount_output/             #
#   --logs/                        #
#                                  #
# Modify $subdirectories to change #
#   the root of the file system    #
####################################

session_start();

if(!($_SESSION['user_name'] == 'fRNAkadmin' || $_SESSION['user_name'] == 'toast'))
{
  header('Location: index.php');
}


$subdirectories = "/var/www/subdirectories_for_interface";

$logdirectory = "$subdirectories/logs";

if(empty($_GET['log'])){
	exit("<h4>Error 6: No log file selected</h4>");
}

$logfile = strip_tags (htmlspecialchars( escapeshellcmd($_GET['log'])));

$logfilepath = "$logdirectory/$logfile";

$fh = fopen($logfilepath, 'r');
$pageText = fread($fh, filesize($logfilepath));
$pageText = preg_replace("/^Bash commands\.\.\./", "<b>Displaying subset of log ($logfile):</b>\n", $pageText);

$pageText = preg_replace('/(Command generated:)(.*)\n/',"\nCommands:\n", $pageText);

$pageText .= "\n <b>=== END OF LOG ===</b>";

#converts newlines to <br>
echo nl2br($pageText);


?>
