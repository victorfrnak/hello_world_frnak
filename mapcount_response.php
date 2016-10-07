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
$key = $ini['login']['key'];
echo $key . " is the key !! \n \n";
$secret = $ini['login']['secret'];

session_start();

$token = $_SESSION['access_token'];
$time_out = $_SESSION['SESSION_TIMEOUT'];
$user = $_SESSION['user_name'];


echo $token;
echo "is the token !!! \n ";

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

<?php
##################################
# Grab values from HTML elements #
##################################
$fqarray = $_POST['fqfilename'];
$procs = strip_tags (htmlspecialchars( escapeshellcmd(htmlentities($_POST['procs']))));
$genome = strip_tags (htmlspecialchars( escapeshellcmd(htmlentities($_POST['genome']))));
$strandedArray = $_POST['stranded'];

###Keep track of the local Cyverse path the user's data
$CyVerseBasePath = "https://agave.iplantc.org:443/files/v2/listings/schmidtc/Transcriptome_Delaware";

###Json Base ###
$jsonBase = "{
    \"jobName\": \"kallisto_linux-v0.43.0\", \n
    \"softwareName\": \"kallisto_linux-v0.43.0\", \n
    \"processorsPerNode\": 16, \n
    \"requestedTime\": \"01:00:00\", \n
    \"memoryPerNode\": 32, \n
    \"nodeCount\": 1, \n
    \"batchQueue\": \"serial\", \n
    \"archive\": false, \n
    \"archivePath\": \"\", \n 
    \"inputs\": { \n
        \"inputBam\": \"agave://data.iplantcollaborative.org/allenhub/ex1.fastq\", \n
        \"inputIndex\":\"agave://data.iplantcollaborative.org/allenhub/kallisto_index_small\" \n
        
    },\n
    \"parameters\":{ ] \n
        \"maxMemSort\":800000000, \n
        \"nameSort\":true \n 
    } \n
} \n
";
echo $jsonBase;
###Output the job submit code via the API##
#####Define Important Global Variable ###

# Temp output path
$temppath = "$subdirectories/temp_output/" . $_SESSION['user_name'];

############################################
# Some error checking redundancy on inputs #
############################################

if(empty($fqarray))
{
	echo "<h4>Error 1: No Fastq file selected</h4>";
	echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
	exit("");
}

if(empty($procs)){
	echo "<h4>Error 2: Number of proccessors error</h4>";
	echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
	exit("");
}
if(empty($genome)) {
	echo "<h4>Error 3: No genome selected</h4><br>";
	echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
	exit("");
}

//check for the issues with the naming
//test if one of the libraries are not named properly
//$files = scandir("$subdirectories/uploads/" . $_SESSION['user_name']);

$files = $fqarray;    
foreach($files as $file)
{
  if (($file != ".") & ($file != ".."))
  {     
	  
    $patternFile = '/(^[0-9]+)/';
    preg_match($patternFile, $file, $fileMatches2, PREG_OFFSET_CAPTURE);
    $fileNum = $fileMatches2[1][0];
    
    $commandsToMoveBackIfFailed  .= "cp $temppath/" . $_SESSION['user_name'] . "/$fileNum* $subdirectories/uploads" . "/" . $_SESSION['user_name'] . "\n";
  
  
  
	 // echo $fileNum . "is the file num !!";
	if(strlen($fileNum) == 0)
	{
	  echo $fileNum . "is the file num...";
	  echo "<h4>Error 5: You need to rename the file to be numeric or you won't be able to run mapcount</h4><br>";
	  echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
	  exit("");
	 
	}
      }
}

$alreadyTemp =scandir("$subdirectories/temp_output/$user");
$doneMapCount =scandir("$subdirectories/mapcount_output/$user");

$NumberOfLibsTemp = array();
foreach ($alreadyTemp as $alreadyTempNumber)
{
    if (($alreadyTempNumber != ".") & ($alreadyTempNumber != ".."))
    {
      $patternFile = '/(^[0-9]+)/';
      preg_match($patternFile, $file, $fileMatches2, PREG_OFFSET_CAPTURE);
      $fileNum = $fileMatches2[1][0];
      array_push($fileNum, $NumberOfLibsTemp);
    }

}

$NumberOfLibsMapCount = array();
foreach ($doneMapCount as $doneMapCountNumber)
{

  if (($doneMapCountNumber != ".") & ($doneMapCountNumber != ".."))
  {
    $patternFile = '/.*([0-9]+)/';
    preg_match($patternFile, $file, $fileMatches2, PREG_OFFSET_CAPTURE);
    $fileNum = $fileMatches2[1][0];
    array_push($fileNum, $NumberOfLibsMapCount);
  }    
}


$alreadyCrunchedNumbers = array_merge($NumberOfLibsTemp, $NumberOfLibsMapCount);
foreach($files as $file)
{
  //alert(file + "is the file");
  if (($file != ".") && ($file != ".."))
  {     
    //var numberOfFile = file.match(/[0-9]+/);
    $patternFile = '/(^[0-9]+)/';
    preg_match($patternFile, $file, $fileMatches2, PREG_OFFSET_CAPTURE);
    $fileNum = $fileMatches2[1][0];
    
    //alert(numberOfFile + "is the number of the file");
    foreach ($alreadyCrunchedNumbers as $crunchedNumber)
    {
      if (numberOfFile == crunched)
      {
	echo "<h4>One of your libraries has the same name as a library already crunched.  Please rename your file using the file manager</h4><br>";
	echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
	exit("");
      }
    }
  }
}




#Match GID from selected genome with one regex if it came from geco and another 
#if it came from on the server
#verify gid, but don't run if it is a fRNAk genome that is on the server

//$pattern2 = '/^([a-z]+).*fRNAk.*approved.*/';
$pattern2 = '/^([A-Za-z]+).*fRNAk.*approved.*/';
preg_match($pattern2, $genome, $matches2, PREG_OFFSET_CAPTURE);

$gid2 = $matches2[1][0];
#echo $gid2 . "is gid2";
#echo $genome . "is the genome!";

if(strlen($gid2) == 0){
	//if it did not come from on the server
	$pattern = '/(\.)*\[ID: ([0-9]*)(\.)*/';
	preg_match($pattern, $genome, $matches, PREG_OFFSET_CAPTURE);
	$gid = $matches[2][0];
#	echo $genome . "is the genome and it did not come  from the server";
}

if(strlen($gid2) != 0){
        //if it came from on the server
        $pattern = '/(\.)*\[ID: ([0-9]*)(\.)*/';
        preg_match($pattern, $genome, $matches, PREG_OFFSET_CAPTURE);
        $gid = "big_ten/" . $matches2[1][0];
 #       echo $genome . "is the genome and it came  from the server";
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

###########################################################
#Create temp output run subdirectory if not already exist	  #
###########################################################
$tempdir = "$subdirectories/temp_output/".$_SESSION['user_name'];
mkdir($tempdir, 0777, true);
chmod($tempdir, 0777);

########################
# Printing information #
########################
echo "<body >";
echo "<div id='result_div'>";
echo "<h4>Crunching library with data:</h4>";

echo "<p>";
echo "Library file(s) selected: <br>";
foreach ($fqarray as $fqfile){
	echo strip_tags (htmlspecialchars(escapeshellcmd($fqfile)))."<br>";
}
echo "</p>";

echo "<p >";
echo "# Procs: $procs";
echo "</p>";

echo "<p >";
echo "Genome selected: ".stripslashes($genome);
echo "</p>";

echo "<p>";
echo "<b>NOTE:</b><br>Running the pipeline will take a long time; <br> An email will be sent when your run completes.";
echo "</p>";
echo "</div>";



##################################
#to print via email if run fails##
##################################
$failMailText = "Your MapCount analysis with run ID: ".$mytimeid." failed please contact allenhub@udel.edu\n";
$failMailCommand = 'echo "'.$failMailText.'" | mail -s "$(echo -e "fRNAkenstein DiffExpress Run\nFrom: fRNAkbox <allenhub@raven.anr.udel.edu>\n")" '. $_SESSION['user_email'] ."\n";

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
# Begin front end script #
##########################

# Initialize output command string
$outputcommands = "";
$databasecommands = "";

# Generate a unique ID based on the time and echo it
$mytimeid = date('his.m-d-Y');
echo "<b>Your run ID is: </b> $mytimeid<br><br>";

# Create log path and initialize it
$logfile = "$subdirectories/logs/$mytimeid.mapcount.log";
$logoutput = "User: ".$_SESSION['user_name']."\n";
$logoutput .= "Bash commands...\n";

$lookForBowtie = "\n";
#command to look for bowtie indices
if(strlen($gid2) == 0)
{
  $lookForBowtie = "python $subdirectories/test_threading_run.py " . $_SESSION['user_name'] . " " . $gid . " " . $subdirectories .  "\n";
}

//curl -sk -H "Authorization: Bearer 112919b7b8c7d2f53c727eb18bffc22" -X PUT -d "action=mkdir&path=todeletandtest" 'https://agave.iplantc.org/files/v2/media/system/data.iplantcollaborative.org/allenhub?pretty=true'

/*
# For every library selected:
foreach($fqarray as $fqoriginal) {
	$authorization = "Authorization: Bearer $token";
	$ch = curl_init("https://agave.iplantc.org/files/v2/media/system/data.iplantcollaborative.org/allenhub?action=mkdir&path=makeme");
	
//	$ch = curl_init("https://agave.iplantc.org:443/files/v2/media/data.iplantcollaborative.org/allenhub?action=mkdir&path=deleteme");
	//$pf = "action=mkdir&path=allenhub/dirtodelete";

	//action=mkdir&path=$NEWDIR

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization ));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pf);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	echo "$result is the result !!! \n";
	
	/*

	$ch = curl_init();

	$authorization = "Authorization: Bearer $token";    
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization ));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "action=mkdir&path=todeletandtest");
	curl_setopt($ch, CURLOPT_URL,'https://agave.iplantc.org/files/v2/media/system/data.iplantcollaborative.org/allenhub?pretty=true');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);
	
	curl_close($ch);
	
	
	echo $server_output;
	echo " is what the server says back !! \n \n";
*/
	


	//$pf = "action=mkdir&path=allenhub/todelete";
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization ));
        //curl_setopt($ch, CURLOPT_URL, "https://agave.iplantc.org:443/files/v2/media/data.iplantcollaborative.org/allenhub");
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $pf);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	//curl_close($ch);
	
	//$result = curl_exec($ch);
	
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

	curl_close ($ch);

        echo "$result is the result !!! \n";

	$theDictionary = json_decode($result);
	
	//exec("curl -sk -H \"Authorization: Bearer $token \" -X POST -d \"action=mkdir&path=allenhub/to_delete\" https://agave.iplantc.org:443/files/v2/media/system/data.iplantcollaborative.org/allenhub/to_delete");

	//echo "we closed !!! \n \n";

	//var_dump(json_decode($result, true));
	//echo " is the dictionary !!! \n \n"; 

	//return json_decode($result);
	//echo "hello there we have decoded !!";

	$filesFromDataStore = array();

	//For cyverse integration, we may want to put them all in a bundle and ship to 

	//mkdir 
	//iles-mkdir -S allenhub/test
	//name after the first, I suppose
	

        //check to see if it is a duplicate file
	$findDup = '/.*(\(.*\))/';
	preg_match($findDup, $fqoriginal, $dupMatch, PREG_OFFSET_CAPTURE);
	$isDup = $dupMatch[1][0];
	$isStranded = 0;
	
	# Initialize moveandgunzip command
	$movecommand = "";

	# Split for double stranded
	$fqdoublestranded = explode("&", $fqoriginal);

	# don't like this but it needs to be done for clarity below
	$fq = $fqdoublestranded[0];

	# To make sure HTSeq command is stranded/unstranded as well
	$htseqstranded = "no";

	# Check if fastq file is zipped
	if (preg_match("/\.gz/", $fq)!=1 ){
		echo("<h4>Error 5: Fastq file not gzipped (.gz)</h4>");
		echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
		exit("");
	}
	if (strlen($isDup ) != 0)
	{
	    echo("<h4>It appears that you have a duplicate file name.  Please go back to the file manager and  remove one copy</h4>");
	    echo "<input type=\"button\" value=\"Try again!\" onClick=\"return reloader(this);\">";
	    exit("");
	}
	else {

		# If stranded, path is equal to both paths delimited by a space
		if (count($fqdoublestranded)==2) {
		#	$fqpath = "$subdirectories/fastq_directory/$fqdoublestranded[0]";
			$fqpath = "$subdirectories/uploads" . "/" . $_SESSION['user_name'] . "/$fqdoublestranded[0]";
		#	$fqpath2strand = "$subdirectories/fastq_directory/$fqdoublestranded[1]";
			$fqpath2strand = "$subdirectories/uploads" . "/" . $_SESSION['user_name'] . "/$fqdoublestranded[1]";
			if(preg_match("/\.gz/", $fqpath)==1 and preg_match("/\.gz/", $fqpath2strand)==1){
		
				#Now that we know that we have a paired end library, may be stranded
                                #get just the library number, check to see if among the stranded libraries
                                $libpattern = "/^s*(\d*).*/";
                                preg_match($libpattern, $fqdoublestranded[0], $matches);
                                $librarynum = $matches[1];
                                
                                
                                #check to see if the library number is in the array of stranded libraries
                                foreach($strandedArray as $strandLib)
                                {
                                  if($fqoriginal = $strandLib)
                                  {
                                    $isStranded = 1;
                                  }
                                
                                }
				
				//echo "the path to move the fastq files are: mv $fqpath $temppath/" . $_SESSION['user_name'] . "/$fqdoublestranded[0]";
				//echo "paths to move the fastq files are" . "(mv $fqpath2strand"  .  $temppath . $_SESSION['user_name']  .  "/$fqdoublestranded[1]";
				#system("mv $fqpath $temppath/" . "$fqdoublestranded[0]");
				#system("mv $fqpath2strand $temppath/" .  "$fqdoublestranded[1]");

				$movecommand .= "gunzip -f $temppath/$fqdoublestranded[0] &&\n";
				$movecommand .= "gunzip -f $temppath/$fqdoublestranded[1] &&\n";
				$rezipcommand = "gzip -f $temppath/$librarynum" . " &&\n";
				$fq = preg_replace("/.gz/","",$fq);
				$fq2 = preg_replace("/.gz/","",$fqdoublestranded[1]);
				$fqpath = "$temppath/$fq";
				$fqpath2strand = "$temppath/$fq2";
				$fqpath = $fqpath." ".$fqpath2strand;
			}

			# this is stranded for htseq also
			$htseqstranded = "yes";
		}
		# Otherwise, it's just equal to the one fastq filepath
		else{
			$fqpath = "$subdirectories/uploads" . "/" . $_SESSION['user_name'] . "/$fq";

			if(preg_match("/\.gz/", $fqpath)==1)
			{
				//just doing this for today 
				#system("mv $fqpath $temppath/");
				$movecommand .= "gunzip -f $temppath/$fq &&\n";
				// changing for fRNAk $movecommand .= "gunzip $temppath/$fq &&\n";
				$fq = preg_replace("/.gz/","",$fq);
				$fqpath = "$temppath/$fq";

			}
		}

		# Generate other file paths for annotation and fasta files
		$annopath = "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid."/*.gff ";
		$fapath = "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid."/".$gid . "/" . $gid;
		
		if(strlen($gid2) != 0)
		{
		  $fapath = "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid . "/" . $gid2 . "/" . $gid2;
		  
		}
		$pathToCheckBowtie = "$subdirectories/find_file.py";  

		#command in order to make sure that the bowtie files are computed
		$bowTieCheck = "python $pathToCheckBowtie " . $_SESSION['user_name'] . " " . $genome . " " . $subdirectories ;

		# Parse library number
		$library = preg_replace("/(_[a-zA-Z0-9]*)+(\.[a-zA-Z0-9]*)+/","",$fq);

		# Generate location for output files
		$thoutputfile = "$temppath/library_$library/tophat_out";
		$cloutputfile = "$temppath/library_$library/cufflinks_out";
		$sampath = "$temppath/library_$library/sam_output";
		$htseqpath = "$temppath/library_$library/htseq_output";

		# Generate mkdir commands for new directories
		# -p option prevents errors with pre-existing folders
		//$makedirs = "mkdir -p $temppath/library_$library &&\n";
		$makedirs = "mkdir -p $cloutputfile &&\n";
		$makedirs .= "mkdir -p $thoutputfile &&\n";
		$makedirs .= "mkdir -p $sampath &&\n";
		$makedirs .= "mkdir -p $htseqpath &&";

		# Generate commands for TH and CL
		#$thcommand = "tophat "; 
		#$thcommand = "hisat2 ";
		$thcommand = "kallisto "; 
		$clcommand = "test cufflinks --max-bundle-frags 100000000 "; 

		#add the additional command, if stranded
                if($isStranded == 1)
                {
                  #$thcommand .= " --library-type fr-firststrand -G $annopath";  
		  $clcommand .= " --library-type fr-firststrand"; 
                }

		#$thcommand .= "-p $procs -o $thoutputfile $fapath $fqpath";

		##replace the original thcommand with the path to the hiseq2 indexes
		#$thcommand .= " -x /home/allenhub/file_to_test_make/sample_tuxedo_to_test/chicken_hiseq2/chicken_bt2_index_base -S $thoutpath -U $fqpath \n";
		
		$pseudoBamPath = $thoutputfile . "/pseudobam.bam";
		$actualBamPath = $thoutputfile . "/actual_bam.bam";
		$sortedBamPath = $thoutputfile . "/sorted_bam.bam";

		$thcommand .= " quant -i /home/allenhub/kallisto_linux-v0.42.3/chicken_indexed --pseudobam -o $thoutputfile" . "/pseudobam.bam --single -l 50 -s 1 $fqpath > $pseudoBamPat/pseudobam.bam \n";

		##convert to bam
		$thcommand .= "samtools view -bS $pseudoBamPath/pseudobam.bam > $actualBamPath \n";
	
		##also convert bame to sorted bam
		#samtools sort pseudo_bam_236_sorted.bam -o pseduo_bam_to_bam_236.bam > 236_sorted_bam.bam


		$thcommand .= "samtools sort $actualBamPath -o $sortedBamPath > $sortedBamPath";

		$rezipcommand = "gzip -f $fqpath" . "* &&\n";
		$clcommand .= " -p $procs -g $annopath -o $cloutputfile $sortedBamPath";

		# Generate HTSeq commands
		
		#$htseqcommand = "samtools view -h -o $sampath/$library.sam $thoutputfile/accepted_hits.bam &&\n";
		$htseqcommand .= "test htseq-count";
    
		$CommandForOutput .= "annotation: $gid2 \n";
		$CommandForOutput .= "fasta file: $gid2 \n";
		$CommandForOutput .= "$thcommand >> output_test \n";
		$CommandForOutput .= "$clcommand \n";
		$CommandForOutput .= "samtools view -h -o /output/path/to/sam/file /path/to/bam \n";
	      
		#use different HTSeq commands if it is one of the top ten genomes
		#-t gene -i gene
		#
		#also use different command if alligator
		$alligator = '/.*(alligator).*/';
		preg_match($alligator, $genome, $alMatches, PREG_OFFSET_CAPTURE);
		$isGator = $alMatches[1][0];
		
		if((strlen($gid2) != 0) & (strlen($isGator) == 0))
		{
		  #$htseqcommand .= " -t gene -i gene -s ".$htseqstranded." $sampath/$library.sam $annopath 2>> $logfile 1> $htseqpath/$library.counts &&";
			#$pseudoBamPath
			$htseqcommand .= " -t gene -i gene -s ".$htseqstranded." $pseudoBamPath/pseudobam.bam $annopath 2>> $logfile 1> $htseqpath/$library.counts &&";  
		##use a different comamnd if it is alligator
		}
		 
		if((strlen($gid2) != 0) & (strlen($isGator) != 0))
		{
		#  $htseqcommand .= " -t gene -i Name -s ".$htseqstranded." $sampath/$library.sam $annopath 2>> $logfile 1> $htseqpath/$library.counts &&";
		 	$htseqcommand .= " -t gene -i Name -s ".$htseqstranded." $pseudoBamPath/pseudobam.bam $annopath 2>> $logfile 1> $htseqpath/$library.counts &&";
			$CommandForOutput .= "htseq-count -t gene -i Name /path/to$library.sam /path/to/annotation/$gid  \n";
		   
		}if((strlen($gid2) == 0) & (strlen($isGator) == 0)){
		  $htseqcommand .= " -t gene -i Name -s ".$htseqstranded." $pseudoBamPath/pseudobam.bam $annopath 2>> $logfile 1> $htseqpath/$library.counts &&";
		  $CommandForOutput .= "htseq-count -t gene -i Name /path/to$library.sam /path/to/annotation/$gid  \n";
		}
		
		#if it does not already exist, make the directory for the user in temp output
		$userTemp = $temppath;
		mkdir($userTemp, 0777);
		chmod($userTemp, 0777);		
		
		#if not already exists, make the directory for the user in mapcount output
		$userMap = "$subdirectories/mapcount_output/" . $_SESSION['user_name'];
		
		if(file_exists ( $userMap ) == FALSE)
		{
		  mkdir($userMap, 0777);
		  chmod($userMap, 0777);
		  exec("chmod 777 $userMap");
		}
		
		#make the directory to house the directory with the bowtie output
		#make the directory for the bowtie output
                $genomeFolder =  "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid; 

		#make the directory for the bowtie output
		$bowTieOut =  "$subdirectories/genome_directory/".$_SESSION['user_name']."/".$gid."/".$gid; 
		#echo $bowTieOut . "is the bowtie out path!!";
		
		if(file_exists ( $bowTieOut ) == FALSE)
		{
		  mkdir($bowTieOut, 0777);
		  chmod($bowTieOut, 0777);
		}
		//echo "$bowTieOut" . "is bowtie out!!!";
				

		# Move temp files to output directory only after the library has been crunched
		$mvcommand = "mv -f $userTemp/library_$library $userMap/library_$library &&\n";
	
		# Append library commands to the output command string
		$singleoutputcommand = "$makedirs\n$thcommand >> $logfile 2>&1 &&\n$clcommand >> $logfile 2>&1 &&\n$htseqcommand\n";
		$outputcommands = $outputcommands.$movecommand.$singleoutputcommand.$rezipcommand.$mvcommand;

		# Build log output
		$logoutput = $logoutput."Fastq file: $fqoriginal\n"."Command generated: ".$singleoutputcommand;
		
		//make the directory for the user_output
		$tempOutputPath = "$temppath/library_$library";
		mkdir("$tempOutputPath");
		chmod($tempOutputPath, 0777);
		
		//log to show information for user:
		$commandsForUserFile = $tempOutputPath . "/log.txt";
		#file_put_contents($commandsForUserFile, $CommandForOutput);
		chmod($commandsForUserFile, 0777);
			
	}



#$MakeLocation = "/storage2/allenhub/subdirectories/bash_scripts/allenhub";
#$makeString = "";
#$makeString = "toRun: ";


# Create bash file output directory
$bashdir = "$subdirectories/bash_scripts/".$_SESSION['user_name'];
$oldbashdir = "$subdirectories/old_bash_scripts/".$_SESSION['user_name'];
$bashfile = "$bashdir/run_$mytimeid.mapcount.sh";
$userFileInGetGenome = "$subdirectories/genome_directory/" . $_SESSION['user_name'];

# Add mv bashfile command to the end to prevent re-running
$mvbashcommand = "\nmv -f $bashfile $oldbashdir";

# Append to log output (TH and CL will redirect stderr to log file)
$logoutput = $logoutput."MapCount output...\n";

# generate the mail commands
$mailtext = "Hello ".$_SESSION['user_name'].",\n";
$mailtext .= "Your Mapcount run information for run ID $mytimeid is...\n";
$mailtext .= "\nFastq files:\n";
foreach ($fqarray as $fqfile){
	$mailtext .= "$fqfile\n";
}
$mailtext .= "\nAnnotation File:\n$gid\n";
$mailtext .= "\nFasta File:\n$$gid\n";
$mailtext .= "\nYour run will take some time to complete.\n";
$mailtext .= "You can view the status of your run using the fRNAkenstein status page.\n";
$mailtext .= "An email will be sent to you upon completion of this run.\n";
$mailtext .= "\n- fRNAkenstein Team\n";

$mailsendcommand = "\n".'echo "'.$mailtext.'" | mail -s "$(echo -e "fRNAkenstein MapCount Run\nFrom: fRNAkbox <allenhub@.udel.edu>\n")" '.$_SESSION['user_email']."\n";

$jobRunCommand = "\n" . 'echo "Your MapCount run with ID: '.$mytimeid.' completed successfully! You can now run differential expression analysis on this data!" | mail -s "$(echo -e "fRNAkenstein MapCount Successful!\nFrom: fRNAkbox <allenhub@geco.iplantc.org>\n")" '.$_SESSION['user_email'];

###also, run Modupe's database:
$databaseCommand = "\n perl /home/modupeore17/SCRIPTS/TRANSDB/pip-GECOinserttranscriptome.pl \n";

#generate header and add to the existing list of commands
$outputcommands = "#!/bin/bash\n".$failCheck.$mailsendcommand.$lookForBowtie.$outputcommands.$mvbashcommand.$jobRunCommand.$databaseCommand;

# if this user doesn't have directories, make them
mkdir($oldbashdir, 0777);
chmod($oldbashdir, 0777);

#make the directories for them in the get genome folder
mkdir($userFileInGetGenome, 0777);
chmod($userFileInGetGenome, 0777);

# Write files
file_put_contents($logfile, $logoutput);
file_put_contents($bashfile, $outputcommands, LOCK_EX);

$MakeLocation = "/storage2/allenhub/subdirectories/bash_scripts/allenhub/run_$mytimeid.makefile.sh";
$makeString = "";
$makeString = "toRun: run_$mytimeid.mapcount.sh \n";

$makeString = $makeString . "\tbash run_$mytimeid.mapcount.sh";

$makeFilePlace = $MakeLocation . "makeFile";

echo $makeFilePlace . "is the make file !! \n \n";
echo $makeString . "is the makeString !! \n \n";

file_put_contents($MakeLocation, $makeString);

#exec("makeflow $MakeLocation");

chmod($bashfile, 0777);
chmod($logfile, 0777);

//echo "$user is the user!!!!!!\n\n";

#have commands to email if the genome cannot be pulled!
$FileIfPullFailed = $subdirectories . "/genome_directory/" . $_SESSION['user_name'] . "/failed_email.sh";
//echo $FileIfPullFailed . " is the location for FileIfPullFailed \n";
$failedPullText = "Please try a fRNAk approved genome.  We apologize for the problem, but sometimes the AGAVE API's used to pull genomes for fRNAkenseq go down or certain genomes are inaccessible.  This has happened with your genome $gid.  Please try again with a new one, the fRNAk approved genomes are always accessible.  If this keeps occurring, please contact allenhub@udel.edu"; 
$failedsendPull = "\n".'echo "'.$failedPullText.'" | mail -s "$(echo -e "fRNAkenstein MapCount Run\nFrom: fRNAkbox <allenhub@.udel.edu>\n")" '.$_SESSION['user_email']."\n";
$failedsendPull .= $commandsToMoveBackIfFailed;

//echo $failedsendPull . "is the failedsendPull command !! \n";

file_put_contents($FileIfPullFailed, $failedsendPull);

//echo "$commandsToMoveBackIfFailed are the commandsToMoveBackIfFailed \n";

//echo $token . "is the access token";
//echo $_SESSION['access_token'] . " is the access token"; 
if(strlen($gid2) == 0)
{
//exec( "python ".$subdirectories."/get_genome.py ".$_SESSION['access_token']. " " .$_SESSION['user_name']." ".$gid." > /dev/null &");


//Let's change this to generate a bash script instead that we can control with the Illumina system

//I commented this out just now !!
//$theCommandToRun = python ".$subdirectories."/get_genome.py ".$_SESSION['access_token']. " " .$_SESSION['user_name']." ".$gid." > /dev/null;

file_put_contents($commandsForUserFile, $CommandForOutput);

//echo $subdirectories . "/get_genome.py ".$_SESSION['access_token']. " " .$_SESSION['user_name']." ".$gid."; 

#echo "we have run the script to get the genome";
}

?>


<input type="button" value="Run fRNAkenstein again!" onClick="return reloader(this);">

</body>
</html>
