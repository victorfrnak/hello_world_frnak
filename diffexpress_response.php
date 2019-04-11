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
#   --diffexpress_output/          #
#   --logs/                        #
#                                  #
# Modify $subdirectories to change #
#   the root of the file system    #
####################################

$ini = parse_ini_file("../config.ini.php", true);
$admin = $ini['login']['admin'];
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];
$subdirectoriesAgave = $ini['filepaths']['agaveSubdirectories'];

session_start();
$token = $_SESSION['access_token'];
$user = $_SESSION['user_name'];

echo "hello !!!";
if ( (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) || 
   (isset($_SESSION['SESSION_TIMEOUT']) && (time() - $_SESSION['SESSION_TIMEOUT'] > 5400)) ) {  
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}


$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if(empty($_SESSION['user_name']) && !($_SESSION['user_is_logged_in']))
{
  header('Location: '.$def_path);
}

////Command for the listing of the data files from the data store
// ./files-list /schmidtc/Transcriptome_Delaware

##################################
# Grab values from HTML elements #
##################################
$controlcondition = strip_tags (htmlspecialchars( escapeshellcmd($_POST['controlcondition'])));
$controllibs = $_POST['controlfilename'];
$expcondition = strip_tags (htmlspecialchars( escapeshellcmd($_POST['expcondition'])));
$explibs = $_POST['expfilename'];
$procs = strip_tags (htmlspecialchars( escapeshellcmd(htmlentities($_POST['procs']))));
#$anno = strip_tags (htmlspecialchars( escapeshellcmd(htmlentities($_POST['afilename']))));
#$fa = strip_tags (htmlspecialchars( escapeshellcmd(htmlentities($_POST['fafilename']))));
$genome = strip_tags (htmlspecialchars( escapeshellcmd(htmlentities($_POST['genome']))));
$analysisname = strip_tags (htmlspecialchars( escapeshellcmd($_POST['analysisname'])));

$analysisname = preg_replace('/\s+/', '_', $analysisname);
echo $analysisname . " is the analysis name !! \n ";

###Now that we know the analysis name, let's make the analysis folder

$diffExpressFolder = "diffexpress_output";
$analysisPathsAgave = "$diffExpressFolder/$analysisname";
$analysisPathsHTSeq = "$analysisPathsAgave/htseq_output";

echo $token;
echo "\n \n is the token !!";
echo "$user is the user !! \n";
$ch = curl_init();

echo $analysisPaths . " is the analysis paths !! ";

$theStringMkDiffArray = "https://agave.iplantc.org/files/v2/media/" . $user . "?pretty=true";
echo $theStringMkDiffArray  . " is the string for the command";

###Make the general folder for the analysis name
//#curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/" . $user . "?pretty=true");
curl_setopt($ch, CURLOPT_URL, $theStringMkDiffArray);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=$analysisPathsAgave");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

$headers = array();
$headers[] = "Authorization: Bearer $token";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
echo "is the makedir result !!!";
 if (curl_errno($ch)) 
{
	echo 'Error:' . curl_error($ch);
}

curl_close ($ch);
echo "$result is the result !!! \n";

###Make the folder for the htseq count data
$theStringMkHtSeqDir = "https://agave.iplantc.org/files/v2/media/" . $user . "?pretty=true";
echo $analysisPathsHTSeq . " is the new htseq directory";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $theStringMkHtSeqDir);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=$analysisPathsHTSeq");
curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=$analysisPathsHTSeq");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

$headers = array();
$headers[] = "Authorization: Bearer $token";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
       
 if (curl_errno($ch)) 
{
        echo 'Error:' . curl_error($ch);
}

curl_close ($ch);
echo "$result is the result form htseq directory !!! \n";

####Now move all of the other htseqFiles into there ###
$lines = file($subdirectories . "/old_bash_scripts/" . $_SESSION['user_name'] ."/output_of_grep.txt");
$arrayOfLibsAndGenome = array();
foreach ($lines as $line_num => $line) {
  
  //echo $line . "is the line!! \n";
    ##get the library and the 
   
    $libPattern = "/.*\/genome_directory\/(.*)\*.gff.*\/" . $_SESSION['user_name'] . "\/(library_.*).*\/cufflinks_out.*/";
    preg_match($libPattern, $line, $matches);
    
    $arrayToAdd = array();
    $genomeToLookUp = $matches[1];
    $libraryToLookUp = $matches[2];
    $arrayOfLibsAndGenome =  array_merge($arrayOfLibsAndGenome,array($libraryToLookUp => "$genomeToLookUp"));
}

//test all of the libraries selected to see if any of them conflict with requirements
$allSelected = array_merge($explibs, $controllibs);



/*
NOTE WELL COPIED OUT THE METADATA COMPARISON SECTION
WILL NEED TO WORK ON THIS ONE FIGURED OUT IN THE CONTEXT OF CYVERSE
DIRECTORY STRUCTURE

foreach ($allSelected as $library)
{
  //echo "$arrayOfLibsAndGenome[$library] is the annotation used for this genome";
  
  if(strlen($arrayOfLibsAndGenome[$library]) == 0)
  {
    echo "<h4>Error! You have selected a file whose analysis is not properly named/cannot be associated with a proper genome.  Pleases contact the fRNAk admin allenhub@udel.edu</h4>";
    echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
    exit("");
  }
  
  //compare againsty each other
  foreach ($allSelected as $libraryII)
  {
    if($library != $libraryII)
    {
      if(($arrayOfLibsAndGenome[$library]) != ($arrayOfLibsAndGenome[$libraryII]))
      {
      
	echo "hello";
	echo "<h4>Error! You have selected to do diffexpress with libraries that were analyzed with different annotations.  This causes file conflicts, you will need to select the run with libraries analyzed with the same annotation</h4>";
	echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
	exit("");
	
      }
     
    }
  }  
}
*/


############################################
# Some error checking redundancy on inputs #
############################################
if(empty($controlcondition)){
        exit("<h4>Error 6: No control condition entered</h4>");
}
if(empty($controllibs)){
        exit("<h4>Error 7: No control libraries selected</h4>");
}
if(empty($expcondition)){
        exit("<h4>Error 8: No experimental condition entered</h4>");
}
if(empty($explibs)){
        exit("<h4>Error 9: No experimental libraries selected</h4>");
}
if(empty($procs)){
        exit("<h4>Error 10: Number of proccessors error</h4>");
}
if(empty($genome)){
	echo "<h4>Error 11: No genome selected (use the search feature)</h4><br>";
        echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
        exit("");
}
if(empty($analysisname)){
        exit("<h4>Error 12: No analysis name entered</h4>");
}

if(count($explibs) < 2){
        exit("<h4>Error 13: No Replicates provided.  You will not be able to run diffespress analyses because of no replicates in the experimental condition. Please refresh the page and resubmit</h4>");
}

if(count($controllibs) < 2){
        exit("<h4>Error 13: No Replicates provided.  You will not be able to run diffespress analyses because of no replicates in the experimental condition.  Please refresh the page and resubmit </h4>");
}


# Fix Analysis Name and Condition Names
//echo "$analysisname is analysisname";

//echo "$analysisname is analysisname again";
$controlcondition = preg_replace('/\s+/', '_', $controlcondition);
$expcondition = preg_replace('/\s+/', '_', $expcondition);

$expcondition = preg_replace('/[ )]+/', '_', $expcondition);
$controlcondition = preg_replace('/[ )]+/', '_', $controlcondition);

$expcondition = preg_replace('/[ (]+/', '_', $expcondition);
$controlcondition = preg_replace('/[ (]+/', '_', $controlcondition);

$controlcondition = preg_replace('/[ ,]+/', '_', $controlcondition);
$expcondition = preg_replace('/[ ,]+/', '_', $expcondition);

$analysisname = preg_replace('/[^a-zA-Z0-9]+/', '_', $analysisname);
$controlcondition = preg_replace('/[^a-zA-Z0-9]+/', '_', $controlcondition);
$expcondition = preg_replace('/[^a-zA-Z0-9]+/', '_', $expcondition);

#Match GID from selected genome with one regex if it came from geco and another 
#if it came from on the server
#verify gid, but don't run if it is a fRNAk genome that is on the server
//$pattern2 = '/^approved/';
//$pattern2 = '/(\.)*\approved*)(\.)*/';
$pattern2 = '/^([a-z]+).*fRNAk.*approved.*/';
preg_match($pattern2, $genome, $matches2, PREG_OFFSET_CAPTURE);
$gid2 = $matches2[1][0];

if(strlen($gid2) == 0){
	//if it came from on the server
	$pattern = '/(\.)*\[ID: ([0-9]*)(\.)*/';
	preg_match($pattern, $genome, $matches, PREG_OFFSET_CAPTURE);
	$gid = $matches[2][0];
	//echo $genome . "is the genome and it came from the server";
}

if(strlen($gid2) != 0){
        //if it came from on the server
        $pattern = '/(\.)*\[ID: ([0-9]*)(\.)*/';
        preg_match($pattern, $genome, $matches, PREG_OFFSET_CAPTURE);
        $gid = "big_ten/" . $matches2[1][0];
}

if(strlen($gid2) == 0){
	$json = file_get_contents('https://geco.iplantc.org/coge/api/v1/genomes/'.$gid);
	$coge_response = json_decode($json);

	if(!is_numeric ($gid) || array_key_exists("error", $coge_response))
	{
		echo "<h4>Error 4: No valid genome selected (use the search feature)</h4>";
		echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
		exit("");
	}
}


####################
# get the genome !!#
###################
if(strlen($gid2) == 0)
{
  exec( "python ".$subdirectories."/get_genome.py ".$_SESSION['access_token']." ".$_SESSION['user_name']." ".$gid." > /dev/null &");
}
##############################################
# Set Analysis Path and MapCount Output Path #
##############################################

$analysispath = "$subdirectories/temp_output/". $_SESSION['user_name'] . "/analysis_$analysisname";
$analysispathAgave = $analysisPaths;


//$mapcountPathAgave = "$subdirectoriesAgave/".$_SESSION['user_name'];
$mapcountpathAgave = "mapcount_output";


$mapcountpath = "$subdirectories/mapcount_output/".$_SESSION['user_name'];

$diffdir = "$subdirectories/diffexpress_output/".$_SESSION['user_name'];

if(file_exists ( $analysispath )){
	//echo $analysispath . "is the analysis path !!";
	exit("<h4>Error: Analysis name already in use!</h4>");
	//echo $analysispath . "is the analysis path !!";
}
/*
if (file_exists ( $diffdir )){
	echo $diffdir . "is the diffdirectory";
        exit("<h4>Error: Analysis name already in use!</h4>");
	#echo $diffdir . "is the diffdirectory";
}
*/
###########################################
# Create diffexpress dirs if non existant #
###########################################

mkdir($analysispath, 0777, true);
chmod($analysispath, 0777);

mkdir($diffdir, 0777, true);
chmod($diffdir, 0777);

########################
# Printing information #
########################
echo "<body >";
echo "<div id='result_div'>";
echo "<h4>Crunching library with data:</h4>";

echo "<p >";
echo "Control condition: $controlcondition";
echo "</p>";
$mytimeid = date('his.m-d-Y');
$bashdir = "$subdirectories/bash_scripts/".$_SESSION['user_name'];
//echo "$bashdir" . "is the bash directory !!";
$bashfile = "$bashdir/run_$mytimeid.diffexp.sh";
//echo "bash file  is !!" . $bashfile;

echo "<p>";
echo "Control library file(s) selected: <br>";
foreach ($controllibs as $controllib){
	echo $controllib."<br>";
}
echo "</p>";

echo "<p >";
echo "Experimental condition: $expcondition";
echo "</p>";

echo "<p>";
echo "Experimental library file(s) selected: <br>";
foreach ($explibs as $explib){
	echo $explib."<br>";
}
echo "</p>";

echo "<p >";
echo "# Procs: $procs";
echo "</p>";

echo "<p >";
echo "Genome: ".stripslashes($genome);
echo "</p>";

echo "<p>";
echo "<b>NOTE:</b><br>Running the pipeline will take a long time!";
echo "</p>";

echo "</div>";

#################################
# Log initialization and run ID #
#################################

# Generate a unique ID based on the time and echo it
$mytimeid = date('his.m-d-Y');
echo "<b>Your run ID is: </b> $mytimeid<br><br>";
//echo "$mytimeid is my time id!!";
# Create log path and initialize it
$logfile = "$subdirectories/logs/$mytimeid.diffexp.log";
//exit("done...");

#######################
# Initialize Commands #
#######################

$commands = "";
$mytimeid = date('his.m-d-Y');
#############################
# Merge ctrl and exp arrays #
#############################

//this is what it was!!!!
//$libs = array_merge($explibs, $controllibs);

//this is what we want it to be !!!
$libs = array_merge($controllibs,$explibs);


############################
# Build Cuffmerge Manifest #
############################

$manifest = "";
$manifestpath = "$analysispath/manifest.txt";

//echo "$analysispath" . "is the analysis path !!";
//echo $_SESSION['user_name'] . "is the user name !!";

foreach($libs as $lib)
{
	preg_match("/library_(.*)/",$lib,$match);
	$lib = $match[1];
	echo $lib . " is the lib !!";
	$diffExpressFolder = "$user/diffexpress_output";
	$diffExpressFolderAgave = "";

	$theMovedName = $lib . ".counts";

	$theToMoveFromPath = "$user/mapcount_output/" . "library_" . $lib . "/htseq_output/" . $lib . ".counts";
	$theToMoveFromPathAgave = "";

	$theToMoveFromPathCuffLinks = "$user/mapcount_output/" . "library_" . $lib . "/cufflinks_out/genes.fpkm_tracking";
	$theToMoveFromPathTophat = "$user/mapcount_output/" . "library_" . $lib . "/tophat_out/accepted_hits.bam";

	###make the moved to paths for htseq, cufflinks and bam
	$theToMoveToPath = "$user/$analysisPathsHTSeq/$theMovedName";
	$theMoveToTophat = "$user/$analysisPathsHTSeq/" . $lib . ".bam";
	$theMoveToCufflinks = "$user/$analysisPathsHTSeq/" . $lib . "genes.fpkm_tracking";

	$theToMoveToPathAgave = "";
	echo $lib . " is the library !!";

	echo "$theToMoveToPath is the to move to path !! \n \n";
	echo "\n $theToMoveFromPath is the to move from path !! \n";

	$manifest .= "$mapcountpath/$lib/cufflinks_out/transcripts.gtf\n";
	$manifesAgave = "$mapcountpathAgave/$lib/cufflinks_out/transcripts.gtf\n";

        /*
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
	$ch = curl_init();
        echo $pathToDataStoreJobDir . " is where move !! \n";
        curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/" . $theToMoveFromPath . "?pretty=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=copy&path=$theToMoveToPath");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                        
         echo "$pathToDataStoreJobDir is jobdir !!";
         $headers = array();
         $headers[] = "Authorization: Bearer $token";
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         $result = curl_exec($ch);
         echo $result . "is the copy result !!! \n \n";
             
	if (curl_errno($ch)) {
         	echo 'Error:' . curl_error($ch);
         }
	curl_close ($ch);
	
	###Also copy over the genes.fpkm_tracking
	###However, need to know that this works first really
	echo $theToMoveFromPathCuffLinks . " \n is the to move from cufflinks";
	echo $theMoveToCufflinks . " \n is the to move to cufflinks !!";

	$ch = curl_init();
        echo $pathToDataStoreJobDir . " is where move !! \n";
        curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/" . $theToMoveFromPathCuffLinks . "?pretty=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=copy&path=$theMoveToCufflinks");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                        
         echo "$pathToDataStoreJobDir is jobdir !!";
         $headers = array();
         $headers[] = "Authorization: Bearer $token";
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         $result = curl_exec($ch);
         echo $result . "is the copy for the cufflinks result !!! \n \n";
             
        if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
         }
        curl_close ($ch);
	
	echo $theToMoveFromPathTophat . " is theToMoveFromPathTophat";
	echo $theMoveToTophat . " is theMoveToTophat \n \n";

	####Also, we'll want to bring in bam files
	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/" . $theToMoveFromPathTophat . "?pretty=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=copy&path=$theMoveToTophat");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                        
         echo "$pathToDataStoreJobDir is jobdir !!";
         $headers = array();
         $headers[] = "Authorization: Bearer $token";
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         $result = curl_exec($ch);
         echo $result . "is the copy result for the tophat files !!! \n \n";
             
        if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
         }
        curl_close ($ch);
	*/

 	###Actually, the last thing we'll want to do is lift the manifest up to Agave
	###Put that code here !!!
 	
       ###Put it in temp_output/analysis




}


//upload the manifest into the user's temp directory
// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org/files/v2/media/allenhub?pretty=true");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

$headers = array();
$headers[] = "Authorization: Bearer $token";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

echo $ch . " is the result of ch for the upload !!!";
$initialcommand = "";
$initialcommand = "mkdir -m 777 -p $analysispath &&\n";
$initialcommand .= "mkdir -m 777 -p $analysispath/images &&\n";
$initialcommand .= "echo \"$manifest\" > $manifestpath &&\n";

##############################
# Annotation and fasta paths #
##############################

$annopath = "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid."/*.gff";
$fapath = "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid."/".$gid ."/$gid.fa";

if(strlen($gid2) != 0)
{
  $fapath = "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid . "/" . $gid2 . "/" . "$gid2.fa";
  //echo "we made it into the loop showing that we have a fRNAk approved genome !!";
}


#echo $fapath ."si the fapath and " . $gid . "is the gid";


###########################
# Build CuffMerge Command #
###########################

$cmoutputpath = "$analysispath/cuffmerge_output";
$cmoutputpathAgave = "$analysisPathsAgave/cuffmerge_output";

$cmcommand = "mkdir -m 777 -p $cmoutputpath &&\ncuffmerge -p $procs -g $annopath -o $cmoutputpath -s $fapath $manifestpath &>> $logfile &&\n";

###################
# Path for Images #
###################

$imagepath = "$analysispath/images";

##################################
#to print via email if run fails##
##################################
$failMailText = "Your DiffExpress analysis ".$analysisname." with run ID: ".$mytimeid." failed please contact allenhub@udel.edu\n";
$failMailCommand = 'echo "'.$failMailText.'" | mail -s "$(echo -e "fRNAkenstein DiffExpress Run\nFrom: fRNAkbox <wtreible@raven.anr.udel.edu>\n")" '. $_SESSION['user_email'] ."\n";

$failCheck = "function test {\n";
$failCheck .= "\"$@\"\n";
$failCheck .= "local status=\$?\n";
$failCheck .= "if [ \$status -ne 0 ]; then\n";
$failCheck .= "echo \"error with \$1\" >&2\n";
$failCheck .= "$failMailCommand \n";
$failCheck .= "fi\n";
$failCheck .= "return \$status\n";
$failCheck .= "}\n";

//echo $failCheck  . "is fail check!!"; 
##########################
# Build CuffDiff Command #
##########################

$cdoutputpath = "$analysispath/cuffdiff_output";
$cdoutputpathAgave="$analysispathsAgave/cuffdiff_output";

$bampaths = "";
$bampathsAgave = "";
$bamPathsAgaveControl = "";
$folderPathsControl = "";


$count = 0;
foreach($controllibs as $controllib)
{
  $bamPathsAgave = $bamPathsAgave. " $mapcountpathAgave/$controllib/tophat_out/accepted_hits.bam";
  $bamPathsAgaveControl =$bamPathsAgaveControl . "$mapcountpathAgave/$controllib/tophat_out/accepted_hits.bam";
  $bampaths = $bampaths."$mapcountpath/$controllib/tophat_out/accepted_hits.bam";
  $folderPathsControl = $folderPathsControl . "library_" . "$controllib"; 


  $count = $count + 1;
  if ($count < count($controllibs))
  {
    $bampaths = $bampaths.",";
    $bamPathsAgaveControl = $bamPathsAgaveControl . ",";
    $folderPathsControl = $folderPathsControl . ",";

  }
}
$bampaths = $bampaths." ";

$count = 0;
$bamPathsAgaveExp = "";
$folderPathsExp = "";

foreach($explibs as $explib)
{
  $bampaths = $bampaths."$mapcountpath/$explib/tophat_out/accepted_hits.bam";
  $bamPathsAgaveExp = $bamPathsAgaveControl . "$mapcountpathAgave/$explib/tophat_out/accepted_hits.bam";
  $folderPathsExp = $folderPathsExp . "library_" . "$explib"; 

  $count = $count + 1;
  if ($count < count($explibs))
  {
    $bampaths = $bampaths.",";
    $bamPathsAgaveExp = $bamPathsAgaveExp . ",";
    $folderPathsExp = $folderPathsExp . ",";

  }
}

##Commend out for the agave stuff ###
#$cdcommand = "mkdir -m 777 -p $cdoutputpath &&\ntest cuffdiff -p $procs --max-bundle-frags 10000000 -o $cdoutputpath -L $controlcondition,$expcondition $cmoutputpath/merged.gtf $bampaths &>> $logfile &&\n";
$cdcommand = "cuffdiff -p $procs --max-bundle-frags 10000000 -o $cdoutputpathAgave -L $controlcondition,$expcondition $cmoutputpath/merged.gtf $bamPathsAgave &>> $logfile &&\n";


echo $cdcommand . " isthe cdcommand !!!! \n";

######################
# Build count matrix #
######################

$htseqpath = "$analysispath";

$countmatrixcommand = "touch $htseqpath/count_matrix.txt &&\n";
$countmatrixcommand .= "python $subdirectories/generate_count_matrix.py ";
$cpcommand = "mkdir -m 777 -p $analysispath/htseq_output &&\n";

foreach($libs as $lib)
{
	preg_match("/library_(.*)/",$lib,$match);
	$library = $match[1];
 	$countmatrixcommand .= "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/library_$library/htseq_output/$library.counts ";
	$cpcommand .= "cp $subdirectories/mapcount_output/" .$_SESSION['user_name'] . "/library_$library/htseq_output/$library.counts $htseqpath &&\n";
}

$countmatrixcommand .= "> $htseqpath/count_matrix.txt &&\n".$cpcommand;

######################
# R Programs Section #
######################

$rpath = "$analysispath/r_output";
mkdir($rpath, 0777);
chmod($rpath, 0777);

###################
# BaySeqVariables #
###################
$notNullModel1 = "";
$notNullModel2 = "";
$nullModel = "";

$controllist = "\"".rtrim(implode("\",\"", $controllibs), ",\"")."\"";
$explist = "\"".rtrim(implode("\",\"", $explibs), ",\"")."\"";

$controlcount = 0;
$expcount = 0;

#when we make the list, we want to add the comma after each entry except the last one
foreach ($controllibs as $library)
{
	if ($controlcount < count($controllibs)-1)
	{
		$notNullModel1 = $notNullModel1."1".",";
	}
	else
	{
		$notNullModel1 = $notNullModel1."1";
	}

	$controlcount += 1;
}

foreach ($explibs as $library)
{
	if ($expcount < count($explibs)-1)
	{
		$notNullModel2 = $notNullModel2."2".",";
	}
	else
	{
		$notNullModel2 = $notNullModel2."2";
	}

	$expcount += 1;
}


########################
# Generate BaySeq info #
########################
$count = 0;
foreach ($libs as $library)
{
	if ($count < count($libs)-1)
	{
		$nullModel = $nullModel . "1" . ",";
	}
	else
	{
		$nullModel = $nullModel . "1";
	}
	$count += 1;
}

####################
# DESeq2 Variables #
####################
$greparray = "";
$count = 0;
foreach ($libs as $libraryname)
{
  	preg_match("/library_(.*)/",$libraryname,$match);
	$librarynum = $match[1];

	if ($count == 0)
	{
		$greparray = "(grep(\"$librarynum\",list.files(\"$htseqpath\"),value=TRUE))";
	}
	else
	{
		$greparray .= ",(grep(\"$librarynum\",list.files(\"$htseqpath\"),value=TRUE))";
	}

	$count += 1;
}
######################
# Generate R Command #
######################

###generate the R commands for the user log-file output###
$commandsForUserFile = "";


//$rcommand = ".libPaths(\"/var/www/R/x86_64-pc-linux-gnu-library/3.1\")\n";
$rfilename = "command_$mytimeid.r";
$rcommandpath = "";
$rcommandpath = "$analysispath/$rfilename";

//$rcommand .= "source(\"http://bioconductor.org/biocLite.R\") \n";
//$rcommand .= "biocLite() \n";
//$rcommand .= "biocLite(\"baySeq\") \n";
//$rcommand .= "biocLite(\"DESeq2\") \n";
//$rcommand .= "biocLite(\"edgeR\") \n";
$rcommand .= "library(DESeq2) \n";
$rcommand .= "library(baySeq) \n";
$rcommand .= "library(edgeR) \n";

###################
# BaySeq Commands #
###################
$rcommand .= "all = read.delim(\"$htseqpath/count_matrix.txt\", header=TRUE, sep=\"\\t\")\n";
$rcommand .= "replicates <- c($notNullModel1,$notNullModel2) \n";
$rcommand .= "groups <- list(NDE = c($nullModel), DE = c($notNullModel1,$notNullModel2))\n";
$rcommand .= "cname <- all[,1] \n";
$rcommand .= "all <- all[,-1] \n";
$rcommand .= "all = as.matrix(all) \n";
$rcommand .= "CD <- new(\"countData\", data = all, replicates = replicates, groups = groups) \n";
$rcommand .= "libsizes(CD) <- getLibsizes(CD)\n";
$rcommand .= "library(parallel) \n";
$rcommand .= "CD@annotation <- as.data.frame(cname) \n";
$rcommand .= "cl <- NULL \n";
$rcommand .= "CDP.NBML <- getPriors.NB(CD, samplesize = 1000, estimation = \"QL\", cl = cl) \n";
$rcommand .= "CDPost.NBML <- getLikelihoods.NB(CDP.NBML, pET = 'BIC', cl = cl) \n";
$rcommand .= "CDPost.NBML@estProps \n";
$rcommand .= "topCounts(CDPost.NBML, group=2) \n";
$rcommand .= "NBML.TPs <- getTPs(CDPost.NBML, group=2, TPs = 1:100) \n";
$rcommand .= "topCounts(CDPost.NBML, group=2)\n";
$rcommand .= "blah <- topCounts(CDPost.NBML,group=\"DE\",FDR=1) \n";
$rcommand .= "as.matrix(blah) \n";
$rcommand .= "colnames(blah) <-c(\"geneName\",$controllist,$explist,\"likelihood\",\"Ordering\",\"False Discovery Differential Expression\",\"Family Error Rate Differential Expression\") \n";
$commandsForOutput = $rcommand;
$rcommand .= "write.csv(blah, file=\"$rpath/bayseq.txt\") \n";

###################
# DESEQ2 Commands #
###################

$rcommand .= "directory <- \"$htseqpath\" \n";
$rcommand .= "sampleFiles <- c($greparray) \n";  #CREATE THE DESEQ2 object
$rcommand .= "sampleCondition <- c(rep(\"C\",$controlcount),rep(\"T\",$expcount)) \n";
$rcommand .= "sampleTable<- data.frame(sampleName=sampleFiles, fileName=sampleFiles, condition=sampleCondition) \n";
$rcommand .= "ddsHTSeq<-DESeqDataSetFromHTSeqCount(sampleTable=sampleTable, directory=directory, design=~condition) \n";
$rcommand .= "dds<-DESeq(ddsHTSeq) \n";
$rcommand .= "res<-results(dds) \n";
$rcommand .= "res<-res[order(res\$padj),] \n";
$rcommand .= "head(res) \n";
$rcommand .= "write.table (as.data.frame(res), file=\"$rpath/deseq2.txt\") \n";

##################
# EdgeR Commands #
##################
$rcommand .= "library(edgeR) \n";
$rcommand .= "group <- c($notNullModel1,$notNullModel2) \n";
$rcommand .= "y <- DGEList(counts=all, group= group) \n";
#make the R object from the list of counts and the annotation
#vector from previous analyses
$rcommand .= "dge <- DGEList(counts=y, group=group, genes = cname ) \n";
$rcommand .= "dge <- calcNormFactors(dge) \n";
$rcommand .= "dge = estimateCommonDisp(dge) \n";
$rcommand .= "de.com = exactTest(dge)   \n";
$rcommand .= "topTags(de.com)  \n";
$rcommand .= "goodList = topTags(de.com, n=\"good\") \n";
$rcommand .= "write.table (as.data.frame(goodList), file=\"$rpath/edger.txt\") \n";

###########################################
# Cufflinks database for graphing command #
###########################################

#$rcommand .= "library(cummeRbund, lib.loc = \"/home/allenhub/R/x86_64-pc-linux-gnu-library/3.1\")\n";
$rcommand .= "library(cummeRbund)\n";
$rcommand .= "cuff<-readCufflinks(dir =\"$cdoutputpath\", gtfFile = \"$cmoutputpath/merged.gtf\", genome = \"build3.1\")\n";

$commandsForOutput .= "sampleCondition <- c(rep(\"C\",$controlcount),rep(\"T\",$expcount)) \n" . "sampleCondition <- c(rep(\"C\",$controlcount),rep(\"T\",$expcount)) \n" ."sampleTable<- data.frame(sampleName=sampleFiles, fileName=sampleFiles, condition=sampleCondition) \n" . "ddsHTSeq<-DESeqDataSetFromHTSeqCount(sampleTable=sampleTable, directory=directory, design=~condition) \n" . "dds<-DESeq(ddsHTSeq) \n" . "res<-results(dds) \n" . "res<-res[order(res\$padj),] \n" . "head(res) \n" . "library(edgeR) \n" . "group <- c($notNullModel1,$notNullModel2) \n" . "y <- DGEList(counts=all, group= group) \n" . "dge <- DGEList(counts=y, group=group, genes = cname ) \n" . "dge <- calcNormFactors(dge) \n" . "dge = estimateCommonDisp(dge) \n" . "de.com = exactTest(dge)   \n" . "topTags(de.com)  \n" . "goodList = topTags(de.com, n=\"good\")";

//log to show information for user:
$commandsForUserFile = $analysispath . "/log.txt";
#echo "$commandsForOutput is commands for output";
#echo "$commandsForUserFile is where the commands going in";
file_put_contents($commandsForUserFile, $commandsForOutput);
chmod($commandsForUserFile, 0777);

###more r rommands

//these are things that we are working on
//$rcommand .= "png(\"$subdirectories/temp_output$analysis_name/heatname.png\")\n";
//$rcommand .= "genes(cuff)\n";
//$rcommand .= "dev.off()\n";
//$rcommand .= "png(\"$subdirectories/temp_output/$analysis_name/heatname.png\")\n";
//$rcommand .= "csDistHeat(genes(cuff))\n";
//$rcommand .= "dev.off()\n";
//$rcommand .= "png(\"$imagepath/sample_density.png\")\n";
//$rcommand .= "csDensity(genes(cuff))\n";
//$rcommand .= "dev.off()\n";

#heatmap analysis
//$rcommand .= "png(\"$imagepath/heatmap.png\")\n";
//$rcommand .= "csDistHeat(genes(cuff))\n";
//$rcommand .= "dev.off()\n";

#PCA analysis
//$rcommand .= "png(\"$imagepath/PCA.png\")\n";
//$rcommand .= "PCAplot(genes(cuff),\"PC1\",\"PC2\")\n";
//$rcommand  .= "dev.off()\n";

//PCAplot(genes(cuff),"PC1","PC2")
#hPCA analysis
//$rcommand .= "png(\"$imagepath/MDS.png\")\n";
//$rcommand .= "MDSplot(genes(cuff),replicates = \"T\")\n";
//$rcommand .= "dev.off()\n";

//volcano Plot
//$rcommand .= "png(\"$imagepath/volcano.png\")\n";
//$rcommand .= "csVolcanoMatrix(genes(cuff))\n";
//$rcommand .= "dev.off()\n";

//dendrogram
//$rcommand .= "png(\"$imagepath/dendrogram.png\")\n";
//$rcommand .= "csDendro(genes(cuff))\n";
//$rcommand .= "dev.off()\n";

//boxplot
//$rcommand .= "png(\"$imagepath/boxplot.png\")\n";
//$rcommand .= "csBoxplot(genes(cuff),replicates=T)\n";
//$rcommand .= "dev.off()\n";

//dispersion plot
//$rcommand .= "png(\"$imagepath/dispersion.png\")\n";
//$rcommand .= "dispersionPlot(genes(cuff))\n";
//$rcommand .= "dev.off()\n";

//svm plot
//$rcommand .= "png(\"$imagepath/fpkmSCVPlot.png\")\n";
//$rcommand .= "fpkmSCVPlot(genes(cuff))\n";
//$rcommand .= "dev.off()\n";

##############################
# Single Output File Command #
##############################
#this is pretty hack right now/not a good solution...we're getting rid of the count matrix output from the script by putting it into the log file, with the exception of the fourth run
#have the if statement in the python script only make the count matrix if the argument for the number of tools is 4!
#$singlefilecommand = "python $subdirectories/output_combiner.py $analysispath/out.txt $analysispath/cuffdiff_output/gene_exp.diff $rpath/edger.txt $rpath/deseq2.txt $rpath/bayseq.txt &&\n";
#$manyfilecommand .= "\nCOUNTER_FILE=\"/home/allenhub/fRNAkenstein_log_file.txt\"";
#$manyfilecommand .= "\necho \"we have made it to right before the python commands \" >>\$COUNTER_FILE \n";
$manyfilecommand .= "python $subdirectories/wayne_combiner_into_matrix.py 0 $analysispath/genes_sig_in_at_least_0.txt $analysispath/cuffdiff_output/gene_exp.diff $rpath/edger.txt $rpath/deseq2.txt $rpath/bayseq.txt &>> $logfile &&\n";
$manyfilecommand .= "python $subdirectories/wayne_combiner_into_matrix.py 1 $analysispath/genes_sig_in_at_least_1.txt $analysispath/cuffdiff_output/gene_exp.diff $rpath/edger.txt $rpath/deseq2.txt $rpath/bayseq.txt &>> $logfile &&\n";
$manyfilecommand .= "python $subdirectories/wayne_combiner_into_matrix.py 2 $analysispath/genes_sig_in_at_least_2.txt $analysispath/cuffdiff_output/gene_exp.diff $rpath/edger.txt $rpath/deseq2.txt $rpath/bayseq.txt &>> $logfile &&\n";
$manyfilecommand .= "python $subdirectories/wayne_combiner_into_matrix.py 3 $analysispath/genes_sig_in_at_least_3.txt $analysispath/cuffdiff_output/gene_exp.diff $rpath/edger.txt $rpath/deseq2.txt $rpath/bayseq.txt  &>> $logfile &&\n";
$manyfilecommand .= "python $subdirectories/wayne_combiner_into_matrix.py 4 $analysispath/genes_sig_in_at_least_4.txt $analysispath/cuffdiff_output/gene_exp.diff $rpath/edger.txt $rpath/deseq2.txt $rpath/bayseq.txt > matrix.txt &&\n";
#$manyfilecommand .= "echo \"we have made it to right after all of the python commands \" >>\$COUNTER_FILE \n";

# Keep Compiling all commands together
$commands .= $cmcommand.$cdcommand;
$commands .= $countmatrixcommand;

# Create bash file output directory which will be referenced by the commands
$bashdir = "$subdirectories/bash_scripts/".$_SESSION['user_name'];
$oldbashdir = "$subdirectories/old_bash_scripts/".$_SESSION['user_name'];
$bashfile = "$bashdir/run_$mytimeid.diffexp.sh";
$rFile = "$bashdir/r_$mytimeid.R";

//echo $bashdir . "is the bash directory !! \n";


#keep compiling all commands together
$commands .= "mkdir -m 777 -p $rpath &&\n";
$commands .= "test R --vanilla < $rFile &&\n";

# Create results path
$resultspath = "http://raven.anr.udel.edu/frnakenstein/results.php?analysis=analysis_$analysisname";

# Combine into one file
$commands .= $manyfilecommand;

# Move folder from temp ...the commented out line will important for scaling up to iPlant
# HOWEVER...at the moment we want anyone in the lab to be able to work with anyone's data,
# so we will move to a collective diffexpress output
#$commands .= "mv -f $analysispath $subdirectories/".$_SESSION['user_name']."\n";

$commands = "";
$commands .= "mv -f $analysispath $diffdir/analysis_$analysisname \n";

# TinyURL Generation for the analysis
#function createTinyUrl($strURL) {
#    $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=" . $strURL);
#    return $tinyurl;
#}
#$new_url = createTinyUrl($resultspath);

# generate the mail commands
$premailtext = "Your DiffExpress analysis ".$analysisname." with run ID: ".$mytimeid." has been started!\n";
$premailtext .= "The estimated completion time for this run assuming no server load or queue is about 2 hours.\n";
$premailtext .= "You can view the status of your run using the fRNAkenstein status page and an email will be\n";
$premailtext .= "sent upon the completion of your run.\n\n-The fRNAkenstein Team";
$premailtext .= "Your control libraries are: ";

#go through the control and experimental libraries now 
foreach ($controllibs as $library)
{
	$premailtext .= $library . "";
}

$premailtext .= "\n";
$premailtext .= "and your experimental libraries are";
foreach ($explibs as $library)
{
        $premailtext .= $library . "";
}

$premailtext .= "\n";
$premailcommand = 'echo "'.$premailtext.'" | mail -s "$(echo -e "fRNAkenstein DiffExpress Run\nFrom: fRNAkbox <wtreible@raven.anr.udel.edu>\n")" '. $_SESSION['user_email'] ."\n";

#$postmailcommand = 'if [ $? -eq 0 ]; then
$postmailcommand = 'echo "Your DiffExpress run with ID: '.$mytimeid.' completed successfully! You can view and download your data on the visualize page." | mail -s "$(echo -e "fRNAkenstein DiffExpress Successful\nFrom: fRNAkbox <wtreible@raven.anr.udel.edu>\n")" '.$_SESSION['user_email'].'
#else
#	echo "Your DiffExpress run with ID: '.$mytimeid.' was unsuccessful! Please email an administrator with your run ID and subject line \"fRNAkenstein error\"" | mail -s "$(echo -e "fRNAkenstein DiffExpress Unsuccessful\nFrom: fRNAkbox <wtreible@raven.anr.udel.edu>\n")" '.$_SESSION['user_email'].'
#fi';

//$commands = $failCheck.$premailcommand.$initialcommand.$commands.$postmailcommand;

$commands = $failCheck.$premailcommand.$commands.$postmailcommand;

#$commands .= "\nCOUNTER_FILE=\"/home/allenhub/fRNAkenstein_log_file.txt\"";
#$commands .= "\n echo \"we have made it to right before the final move command \" >>\$COUNTER_FILE \n";

# Finally, move bash file to temp folder
$commands .= "\nmv $subdirectories/bash_scripts/" . $_SESSION['user_name'] . "/r_$mytimeid.R $oldbashdir";
$commands .= "\nmv $bashfile $oldbashdir";
#$commands .= "\necho \"we have made it to right after the move make library directory in temp \" >>\$COUNTER_FILE\n";

#if this user doesn't have directories, make them
mkdir($bashdir, 0777);
mkdir($oldbashdir, 0777);

file_put_contents($bashfile, $commands, LOCK_EX);
//echo $bashfile . " is the bash file";
chmod($bashfile,0777);
file_put_contents($rFile, $rcommand, LOCK_EX);
chmod($rFile,0777);
//echo "$rFile is the r file !!!";


###Note well, that we will want to modify this !! ###
$jsonBase = "{
    \"jobName\": \"allen_diffexpress_test-0.1.19\", \n
    \"softwareName\": \"allen_diffexpress_test-0.1.19\", \n
    \"processorsPerNode\": 16, \n
    \"requestedTime\": \"01:00:00\", \n
    \"memoryPerNode\": 32, \n
    \"nodeCount\": 1, \n
    \"batchQueue\": \"serial\", \n
    \"archive\": false, \n



    \"archivePath\": \"\", \n 


    \"inputs\": { \n
        \"manPath\": [$fqPathAgave], \n

        \"annoPath\":\"agave://data.iplantcollaborative.org/$user/coge_data/$gid\", \n
        \"faPath\": \"$jsonIrod\", \n


         \"fpkmDirs\": \"agave://data.iplantcollaborative.org/allenhub/diffexpress_output/analysis_july_28/htseq_output \", \n
    	 \"outPut\": \"agave://data.iplantcollaborative.org/allenhub/diffexpress_output \", \n

    	\"ControlCuffDiffPaths\": \"agave://data.iplantcollaborative.org/allenhub/diffexpress_output/analysis_july_28/htseq_output/469.bam,agave://data.iplantcollaborative.org/allenhub/di \", \n
    	\"controllibs\":[\"$folderPathsControl\"], \n
    	\"explibs\": [\"$folderPathsExp\"], \n

    	\"explibsBam\":[\"$bamPathsAgaveExp\"], \n
    	\"controllibsBam\":[\"$bamPathsAgaveControl\"], \n
    	\"testMoveDir\": [\"agave://data.iplantcollaborative.org/allenhub/mapcount_output/library_469\"]
    },\n
    \"parameters\":{ \n
        \"analysisName\": \"$analysisname\" \n 
    } \n
} \n
";

echo $jsonBase;
echo "is the jsonBase !!! \n \n";
echo "should be echoing !!!";

?>


<!--
#########################
# fRNAkenstein Reloader #
#########################
-->

<script language="javascript">
function reloader()
{
        /* Reload window */
        parent.location.reload();
        /* Set iFrame to empty */
        window.location.assign("about:blank");

}
</script>
<br> <br>
<input type="button" value="Run fRNAkenstein again!" onClick="return reloader(this);">


</body>
</html>
