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

$ini = parse_ini_file("../config.ini.php", true);
$admin = $ini['login']['admin'];
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];

session_start();

if ( (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) || 
   (isset($_SESSION['SESSION_TIMEOUT']) && (time() - $_SESSION['SESSION_TIMEOUT'] > 5400)) ) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time      
    session_destroy();   // destroy session data in storage                 
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if(!($_SESSION['user_name'] == 'fRNAkadmin' || $_SESSION['user_name'] == 'toast'))
{
  header('Location: '.$def_path);
}

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
