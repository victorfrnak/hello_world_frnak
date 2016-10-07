<head>
<title>
fRNAkenstein Access Results
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<?php
#########Get Information from the config file #####
$ini = parse_ini_file("../config.ini.php", true);
$admin = $ini['login']['admin'];
$def_path = $ini['login']['default'];	
$subdirectories = $ini['filepaths']['subdirectories'];
#####################################################

session_start();
ini_set('memory_limit','200M');
$token = $_SESSION['access_token'];
$user = $_SESSION['user_name'];


###make the diffexpress and mapcount directories for the users if they do
##not already exist
$userDiffDirectory = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'];
if (file_exists($userDiffDirectory) == FALSE) 
{
        exec("mkdir $userDiffDirectory");
}

$userMapCountDirectory = "$subdirectories/mapcount_output/" . $_SESSION['user_name'];

if (file_exists($userMapCountDirectory) == FALSE) 
{
        exec("mkdir $userMapCountDirectory");
}

//Parse the information in the text file from the api
$authorization = "Authorization: Bearer $token";
$ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/$user/mapcount_output");

//echo "made the change !! \n \n";
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
//echo "$result is the result !!! \n";

curl_close($ch);
$theDictionary = json_decode($result);
//echo "we closed !!! \n \n";

//var_dump(json_decode($result, true));
//echo " is the dictionary !!! \n \n"; 

//return json_decode($result);
//echo "hello there we have decoded !!";

$filesFromDataStore = array();
$obj = json_decode($result, TRUE);
for($i=0; $i<count($obj["result"]); $i++)
{
//        echo "Rating is " . $obj["result"][$i]["name"];
        $file = "";
	//echo $file . " the file \n \n";
        $file = $obj["result"][$i]["name"];
        //echo $file . " is the file about to added \n \n";
        //echo $file . " is the file !!! \n \n";
        array_push($filesFromDataStore, $file);

// . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";
}


$libFiles = $filesFromDataStore;
echo $filesFromDataStore[1];

echo json_decode($libFiles);
echo "is what is available";

// Now, do the same thing with the diffexpress output
$ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/$user/diffexpress_output");

//echo "made the change !! \n \n";
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
//echo "$result is the result !!! \n";

curl_close($ch);
$theDictionary = json_decode($result);
//echo "we closed !!! \n \n";

//var_dump(json_decode($result, true));
//echo " is the dictionary !!! \n \n"; 

//return json_decode($result);
//echo "hello there we have decoded !!";

$filesFromDataStoreII = array();
$obj = json_decode($result, TRUE);
for($i=0; $i<count($obj["result"]); $i++)
{
//        echo "Rating is " . $obj["result"][$i]["name"];
        $file = "";
//        echo $file . " the file \n \n";
        $file = $obj["result"][$i]["name"];
        //echo $file . " is the file about to added \n \n";
//        echo $file . " is the file II !!! \n \n";
        array_push($filesFromDataStoreII, $file);

// . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";
}

$libFiles = $filesFromDataStoreII;

echo $filesFromDataStore[1];

echo json_decode($libFiles);
echo "is what is available";


?>
<script>
$(function() {

var availableTags1 = <?php echo json_encode($filesFromDataStore) ?>;
var availableTags2 = <?php echo json_encode($filesFromDataStoreII) ?>;

//availableTags2 = 


//var availableTags1 = <?php echo json_encode(scandir("$subdirectories/diffexpress_output/" . $_SESSION['user_name'])) ?>;
//var availableTags2 = <?php echo json_encode(scandir("$subdirectories/mapcount_output/" . $_SESSION['user_name'])) ?>;


var totalTags = availableTags1.concat(availableTags2);
 $( "#tags" ).autocomplete({
source: totalTags, minLength:2, delay:0
});
});

$(function(){
	var analyses=[];
	//<?php $diffexpressFiles = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'];?>
	//<?php $mapcountFiles = "$subdirectories/mapcount_output/" . $_SESSION['user_name'];?>
	
	//var diffExpressAnalyses = <?php echo json_encode(scandir($diffexpressFiles)) ?>;
	//var mapCountAnalyses = <?php echo json_encode(scandir($mapcountFiles)) ?>;
	
	var availableTags1 = <?php echo json_encode($filesFromDataStore) ?>;
	var availableTags2 = <?php echo json_encode($filesFromDataStoreII) ?>;

	var diffExpressAnalyses = availableTags1;
        var mapCountAnalyses = availableTags2;

	var totalAnalyses = diffExpressAnalyses.concat(mapCountAnalyses)
	$("#adder").click(function(){ 
		var inp = $("#tags");
		var name = inp.val();
				
		if ( inp.val().length == 0 )
		{
			test  = jQuery.inArray(totalAnalyses, name);
		
		}
		
		if(inp.val().length > 0)
		{
			if(jQuery.inArray(name, totalAnalyses) == -1)
			{
				alert(name + "is not a proper analysis, please choose another name");
				alert(analyses + "is the analysis");
				event.preventDefault();

			}
		
		}
	});
});

$( document ).ready(
function() {
	$(document).mousemove(function (e) {
		$( ".help" ).dialog("option", "position", {
        		my: "left+30 top+30-$(document).scrollTop()",
        		at: "left top",
        		of: e
      		});

	});
        $('.help').each(function(k,v){ // Go through all Divs with .box class
        	var help = $(this).dialog({ autoOpen: false });
		$(this).parent().find('.ui-dialog-titlebar-close').hide();
		//$( "#help"+(k)).mouseover(function() { // k = key from the each loop
		//
		//$("[name='nameofobject']");
		//
		//this is the next line
		//$( "[name="+"'" + (k) + "'" + " \"").mouseover(function() { // k = key from the each loop
		$( "#help"+(k)).mouseover(function() { // k = key from the each loop
                	help.dialog( "open" );
                }).mouseout(function() {
                    	help.dialog( "close" );
                });
	});
});

</script>
<body>
<!--
<div class="help" id="help0" style="" title="Select Analysis">
-->
<div class="help" name="help0" style="" title="Select Analysis">
<font size="3"><center>Here, you can download both diffexpress and MapCount output.  To access DiffExpress results, just search the name of the analysis.  To access MapCount results, just search the library number</center></font>
</div>
<div class="help" id="help1" style="" title="genes.fpkm_tracking ">
<font size="3"><center>FPKM for a given gene, inlcuding all of the associated isoforms (See link to Manual at Bottom of Page)</center></font>
</div>
<div class="help" id="help2" style="" title="isoforms.fpkm_tracking ">
<font size="3"><center>FPKM values for individual isoforms of a gene (See link to Manual at Bottom of Page)</center></font>
</div>
<div class="help" id="help3" style="" title="skipped.gtf">
<font size="3"><center>Contains information about loci that have been skipped, in the form of a gtf annotation file (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help4" style="" title="transcripts.gtf ">
<font size="3"><center>Much of the same information as the genes.fpkm.tracking file, but assembled into an easier to read gtf file (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help5" style="" title="cds_fpkm tracking ">
<font size="3"><center>Coding sequence FPKMs. Tracks the summed FPKM of transcripts sharing each p_id, independent of tss_id. (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help6" style="" title="cds exp_diff ">
<font size="3"><center>Coding sequence differential expression. Tests differences in the summed FPKM of transcripts sharing each p_id independent of tss_id.(See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help7" style="" title="gene exp_diff ">
<font size="3"><center>Gene-level differential expression. Tests differences in the summed FPKM of transcripts sharing each gene_id (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help8" style="" title="genes.fpkm_tracking ">
<font size="3"><center>Gene FPKMs. Tracks the summed FPKM of transcripts sharing each gene_id. (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help9" style="" title="promoters.diff">
<font size="3"><center>This tab delimited file lists, for each gene, the amount of overloading detected among its primary transcripts, i.e. how much differential promoter use exists between samples. Only genes producing two or more distinct primary transcripts (i.e. multi-promoter genes) are listed here.(See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help10" style="" title="isoforms_exp.diff">
<font size="3"><center>Transcript-level differential expression. (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help11" style="" title="isoforms.fpkm_tracking">
<font size="3"><center>Transcript FPKMs. (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help12" style="" title="splicing diff">
<font size="3"><center>This tab delimited file lists, for each primary transcript, the amount of isoform switching detected among its isoforms, i.e. how much differential splicing exists between isoforms processed from a single primary transcript. Only primary transcripts from which two or more isoforms are spliced are listed in this file. (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help13" style="" title="tss group exp diff">
<font size="3"><center>Primary transcript FPKMs. Tracks the summed FPKM of transcripts sharing each tss_id (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help14" style="" title="tss group_fpkm.tracking  ">
<font size="3"><center>Primary transcript FPKMs. Tracks the summed expression and counts of transcripts sharing each tss_id in each replicate. (See link to Manual at Bottom of Page) </center></font>
</div>
<div class="help" id="help15" style="" title="Download Summary">
<font size="3"><center>Here, you can download you tab delimited diffexpress results.  This includes a list of all genes, those deemed significant by at least 1 of the packages, 2 of the packages, 3 and finally, all four packages.  These contain p-values and other relevant info.  You can also download the various cuffdiff outputs.  See the Instructions page for more detail  </center></font>
</div>
<center>


<?php

#if the directory for the images to be viewed does not exist, then make it

if(empty($_POST['analysis'])){
	echo '<center>';
	echo '<img src="images/frnak_banner.png" alt="fRNAkenstein" width="600" ><br> <br>';
	echo '</center>';
	echo '<br>';
	echo '<form method="POST" action="data_level_results_new.php"';	
	echo '<span class="ui-widget">';
	echo'<input id="tags" name="analysis">';
	echo'</span><button id="adder" type="submit">Select Analysis</button><span><span class="helper" id="help0" </span></form>';	
	echo '<table>';
	echo '<tr></tr><td></td><td></td>'; 
	echo '<br><br><br>';
	echo '<div id="button-container">';
	echo '<form action="menu.php">';
	echo '<input type = "submit" id="button" value = "Menu" class="green" style="font-weight:bold>" ';
	//echo ' </form>';
	//<div id="button-container">
        //<form action="menu.php">
        //<input type = "submit" id="button" value = "Menu" class="green" style="font-weight:bold">
        //</form>
	//</div>

	echo '</div>';
	echo '</table>';

	exit('');
}
$analysis = htmlspecialchars($_POST['analysis']);
$diffExpressPathToScan = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis";

//$cuffDiffOutput = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/cuffdiff_output";
//API Call

//$mapCountPathToScan = "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/$analysis/cufflinks_out";

//API Call

//$rawCountFolder = "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/$analysis/htseq_output";

//Now, do  the  API Call



$pattern = "/((analysis).*)/"; //all of the diffexpress runs are going to begin with the prefix analysis
$isDiffExpress = 0;
$isMapCount = 0;
if(preg_match($pattern, $analysis) == 1)
{
	$isDiffExpress = 1;		
};

$libsCrunched = "";
$libsCrunched = "The libraries crunched in this analysis are ";
if($isDiffExpress == 1)
{
	$files = scandir("$diffExpressPathToScan");
	$userlogFile = "log.txt";
	array_push($files, $userlogFile);
	$cuffDiffFiles = scandir("$cuffDiffOutput");
	
	$whereToGrep =  $subdirectories . "/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/genes_sig_in_at_least_0.txt";
	$grepOutput = $subdirectories . "/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/libraries.txt";
	system("grep \"library\"" . " " . $whereToGrep . " > " . $grepOutput);
	
	$arrayOfLibsAndGenome = array();

	$lines = file($grepOutput);
	foreach ($lines as $line)
	{
    
	    $parts = preg_split('/\s+/', $line);
	    foreach($parts as $part)
	    {
	        $librarynum2 = "";
		
		$libpattern = "/\D*library_(.*)/";
		preg_match($libpattern, $part, $matches2);
		$librarynum2 = $matches2[1];
		
		if(strlen($librarynum2) != 0)
		{
			$libsCrunched = $libsCrunched . "library $librarynum2 ";
		}
	    }

	}
}

if($isDiffExpress != 1)
{
	$files = scandir("$mapCountPathToScan");
	$pattern1 = "/(\d+).*/"; //all of the mapcount runs are going to begin with the prefix analysis
	preg_match($pattern1, $analysis,$matches);	
	$library = $matches[1];
	$htseqFile = $library.".counts";
	array_push($files,$htseqFile);
	$userlogFile = "log.txt";
	array_push($files, $userlogFile);
}

echo "<div class=\"help\" id=\"help16\" style=\"\" title=\"Download Summary\">";
echo "<font size=\"3\"><center>$libsCrunched</center></font>";
echo "</div>";

?>
<form class="go-bottom" id='submitform' action='data_level_results_response.php' method='post' target='formresponse'>

<!--
Send the analysis path also!
-->

<input type="hidden" value="<?php echo $analysispath; ?>" name="analysispath" />
<input type="hidden" value="<?php echo $analysis; ?>" name="analysis" />

<center>
<?php
if($isDiffExpress != 1)
{
	
	echo "<form class=\"go-bottom\" id=\"submitform\" action=\"data_level_results_response.php\" method=\"post\" target=\"formresponse\">";
	//echo "$isDiffExpress is the isDiffExpress result !!!";
	$name = "hello this is a test";
	//echo "$name is name";
	echo "<input type=\"hidden\" value=\"<?php echo $name; ?>\" name=\"analysispath\" />";
	$name = "hello this is a test";
	//echo "$name is name";
	echo "<input type=\"hidden\" value=\"<?php echo $name; ?>\" name=\"analysis\" />";
}
?>

<!--
################################
# Beginning of alignment table #
################################
-->
<table>
<tr style="padding:0px; margin:0px;">
<td valign="top" style="padding-top:12px;padding-left:8px;width:300px">

<!--
################################################
# Search for output files			#
################################################
-->
<div class='container'>
<br><br><br>
<?php
#match all of the text files, which are going to be the output from
#the combining algorithm
$geneSigNumber = 0;
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo '<center>';
echo '<img src="images/frnak_banner.png" alt="fRNAkenstein" width="600" ><br> <br>';
echo "<table>";
if($isDiffExpress == 1)
{
	echo "<span class=\"helper\" id=\"help16\" style=\"color:blue;\"><b>Libraries Crunched</b></span><br><br>";
}

$pathToFiles = "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/$analysis/cufflinks_out/$file";

foreach ($files as $file)
{
	//echo "$file is the file !!";
	if($file != "." AND $file != "..")
	{	
		if($isDiffExpress != 1)
		{
			$length=count($files);
			
			#get the number of the library from the analysis name
			$pattern = "/(.*(\d))/";
			preg_match($pattern, $analysis, $matches);
			$number = $matches[1];
			
			$pathToFiles = "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/$analysis/cufflinks_out/$file";
			$pathToRawCount = "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/$analysis/htseq_output/$file";
			$pathToUserLog = "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/$analysis/log.txt";
			//echo $pathToUserLog . "is the path to the userlog";
			//data_level_results_response.php?analysis=$pathToFiles;			
			if($file == "genes.fpkm_tracking")
			{

				$nameOfFiles = $library . "_genes.fpkm_tracking";
				//echo $nameOfFiles . "is the nameOfFiles";
				echo "<tr>";
				echo "<td>";
				echo "<div id=\"button-container\" name=\"help0\">";
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \" genes.fpkm_tracking\" class=\"green\" style=\"font-weight:bold\" name=\"help0\">" . "<span><span class=\"helper\" id=\"help1\"><b>		</b></span><br><br>";;
				echo "</div>";
				echo "</td>";
			}
			else if($file == "isoforms.fpkm_tracking")
			{
				$nameOfFiles = $library . "_isoforms.fpkm_tracking";
				echo "<td>";
				echo "<div id=\"button-container\">";
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"isoforms.fpkm_tracking\" style=\"font-weight:bold\" class=\"green\">" . "<span><span class=\"helper\" id=\"help2\"><b>		</b></span><br><br>";
				echo "</div>";
				echo "</td>";
				echo "</tr>";
			}
			else if($file == "transcripts.gtf")
			{
				$nameOfFiles = $library . "_transcripts.gtf";
				echo "<td>";
				echo "<div id=\"button-container\">";
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"transcripts.gtf\" style=\"font-weight:bold\" class=\"green\">" . "<span><span class=\"helper\" id=\"help4\"><b>		</b></span><br><br>";
				echo "</div>";
				echo "</td>";
			}
			else if($file == "skipped.gtf")
			{
				$nameOfFiles = $library . "_transcripts.gtf";
				echo "<td>";
				echo "<div id=\"button-container\">";
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"skipped.gtf\" style=\"font-weight:bold\" class=\"green\">" . "<span><span class=\"helper\" id=\"help3\"><b>		</b></span><br><br>";;
				echo "</div>";
				echo "</td>";
			}
			
			
			else if($file == "$library.counts")
			{
				echo "<tr>";
				echo "<td>";
				$nameOfFiles = $library . ".counts";
				//echo "</table>";
				//echo "<center>";
				echo "<div id=\"button-container\">";
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToRawCount&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"HTseq Count\" style=\"font-weight:bold\" class=\"green\">" . "<span><span class=\"helper\" id=\"help3\"><b>		</b></span><br><br>";;
				echo "</div>";
				echo "</td>";
			} 
		
			else if($file == "log.txt")
			{
				//echo "the log is here";
				$nameOfFiles = $library . "user_log";
				echo "<td>";
				echo "<center>";
				echo "<div id=\"button-container\">";
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToUserLog&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"MapCount Log Commands\" style=\"font-weight:bold\" class=\"green\">" . "<span><span class=\"helper\" id=\"help3\"><b>		</b></span><br><br>";;
				echo "</div>";
				echo "</td>";
				echo "</table>";
			} 
		
		}	
		
		if($isDiffExpress == 1)
		{
				
			
			#there will be two types of files here, the gene lists and the cuffdiff output. the genelists will end in .txt.  the cuffdiff files, however, will not
			#these files will be in a further directory, which we can get to by composing the file path once we discriminate between the two types of files.
			$iscuffDiff = 0;
			$pattern = "/(.*(\d.txt))/";
			$resultTest = preg_match($pattern, $file, $matches);
			if(preg_match($pattern, $file, $matches) == 1)
			{
				
				$pathToFiles = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/$file";
				
				//make a new row if it is the third iteration
				if($geneSigNumber == 0)
				{
					echo "<tr>";
				}
				echo "<td>";
				echo "<div id=\"button-container\">";
				if($geneSigNumber == 0)
				{
					$nameOfFiles = $analysis . "all_genes";
					echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"All genes\"class=\"green\" style=\"font-weight:bold\" >" . "<span><span class=\"helper\" id=\"help15\" style=\"color:blue;\"><b>		</b></span><br><br>";	
				}
				else
				{
					$nameOfFiles = $analysis . "$geneSigNumber";
					echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"Genes sig in at least $geneSigNumber\"class=\"green\" style=\"font-weight:bold\" >";
				}
				echo "</div>";
				echo "</td>";
				if($geneSigNumber == 2)
				{
					echo "</tr>";
				}
				$geneSigNumber += 1;
			}
			
			$cuffDiffFiles = scandir("$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/cuffdiff_output");
			
			if($file == "$log.txt")
			{
				$nameOfFiles = $library . "user_log";
				echo "</table>";
				echo "<center>";
				echo "<div id=\"button-container\">";
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToUserLog&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"Log For Diffexpress\" style=\"font-weight:bold\" class=\"green\">" . "<span><span class=\"helper\" id=\"help3\"><b>		</b></span><br><br>";;
				echo "</div>";
			}
		
		}
	
	}
}
if($isDiffExpress != 1)
{
	echo "</table>";
	echo "<input type=\"hidden\" value=\"<?php echo $analysis; ?>\" name=\"this_is_a_test_of_passing_the_names\" />";
}
echo '</center>';
foreach($cuffDiffFiles as $file)
{
	if(($file != "..") & ($file != "."))
	{
		$pathToFiles = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/cuffdiff_output/$file";
		if($file == "cds.fpkm_tracking")
		{
			
			$nameOfFiles = $analysis . "cds.fpkm_tracking";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"cds.fpkm_tracking\" class=\"green\" style=\"font-weight:bold\">" . "<span><span class=\"helper\" id=\"help5\"><b>		</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
			echo "</tr>";
		}
		if($file == "cds_exp.diff")
		{
			$nameOfFiles = $analysis . "cds_exp.diff";
			echo "<tr>";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"cds_exp.diff\" class=\"green\" style=\"font-weight:bold\" >" . "<span><span class=\"helper\" id=\"help6\" style=\"color:blue;\"><b>		</b></span><br><br>";
			echo "</div>";
			echo "</td>";
		}
		if($file == "gene_exp.diff")
		{
			$nameOfFiles = $analysis . "gene_exp.diff";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"gene_exp.diff\" class=\"green\" style=\"font-weight:bold\">" . "<span><span class=\"helper\" id=\"help7\"><b>			</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
			
		}
		if($file == "genes.fpkm_tracking")
		{
			$nameOfFiles = $analysis . "genes.fpkm_tracking";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"genes.fpkm_tracking\" class=\"green\" style=\"font-weight:bold\">" . "<span><span class=\"helper\" id=\"help8\"><b>		</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
			echo "</tr>";
		}
		if($file == "isoform_exp.diff")
		{
			$nameOfFiles = $analysis . "isoform_exp.diff";
			echo "<tr>";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"isoform_exp.diff\" class=\"green\" style=\"font-weight:bold\">" . "<span><span class=\"helper\" id=\"help9\"><b>			</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
		}
		if($file == "isoforms.fpkm_tracking")
		{
			$nameOfFiles = $analysis . "isoforms.fpkm_tracking";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"isoforms.fpkm_tracking\" class=\"green\" style=\"font-weight:bold\" >" . "<span><span class=\"helper\" id=\"help10\"><b>		</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
		}
		if($file == "promoters.diff")
		{
			$nameOfFiles = $analysis . "promoters.diff";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"promoters.diff\" class=\"green\" style=\"font-weight:bold\" >" . "<span><span class=\"helper\" id=\"help11\"><b>		</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
			echo "</tr>";
		}
		if($file == "splicing.diff")
		{
			$nameOfFiles = $analysis . "splicing.diff";
			echo "<tr>";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"splicing.diff\" class=\"green\" style=\"font-weight:bold\">" . "<span><span class=\"helper\" id=\"help12\"><b>		</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
		}
		if($file == "tss_group_exp.diff")
		{
			$nameOfFiles = $analysis . "tss_group_exp.diff";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"tss_group_exp.diff\" class=\"green\" style=\"font-weight:bold\" >" . "<span><span class=\"helper\" id=\"help13\"><b>		</b></span><br><br>";
			echo "</div>";
			echo "</td>";
		}
		if($file == "tss_groups.fpkm_tracking")
		{
			$nameOfFiles = $analysis . "tss_groups.fpkm_tracking";
			echo "<td>";
			echo "<div id=\"button-container\">";
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles&analysis=$nameOfFiles\" download> <input type =\"button\" id=\"button\" value = \"tss_groups.fpkm_tracking\" class=\"green\" style=\"font-weight:bold\" >" . "<span><span class=\"helper\" id=\"help14\"><b>		</b></span><br><br>";;
			echo "</div>";
			echo "</td>";
			echo "</tr>";
		}	
	}
}
if($isDiffExpress == 1)
{
	echo "</table>";
	
}
echo '</div>';

?>
<br><br><br><br><br>

<!--
###########################
# Submit and Menu Buttons #
###########################
-->
<!--
<div class='container'>
<center>
<form action = 'data_level_results_response.php' target = 'formresponse'>
<button id='crunch' class='crunch' type="submit">"Show data level images"</button>
-->
</form>
<br> <br> <br>
<center>
<div id="button-container">
        <form action="menu.php">
        <input type = "submit" id="button" value = "Menu" class="green" style="font-weight:bold">
        </form>
</div>
<div id="button-container">
        <form action="http://cole-trapnell-lab.github.io/cufflinks/manual/">
        <input type = "submit" id="button" value = "Tuxedo Pipeline Manual" class="yellow" style="font-weight:bold">
        </form>
</div>

<!--
###################
# Response iFrame #
###################
-->
<td valign="top" style="padding-left:0px;align:left">
<br>
<!--
<iframe name='formresponse' src='placeholder_response.html' style="border: outset; background-color:#d0eace" width='500px' height='650' frameborder='0'>
</iframe>
-->
</td>
</tr>
</table>





