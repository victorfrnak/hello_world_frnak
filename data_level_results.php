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

###make the diffexpress and mapcount directories for the users if they do
##not already exist
$userDiffDirectory = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'];
if (file_exists($userDiffDirectory) == FALSE) 
{
        exec("mkdir $userDiffDirectory");
}

//echo "$userDiffDirectory is the user diff directory !!";

$userMapCountDirectory = "$subdirectories/mapcount_output/" . $_SESSION['user_name'];

//echo "$userMapCountDirectory is the user map directory !!";

if (file_exists($userMapCountDirectory) == FALSE) 
{
        exec("mkdir $userMapCountDirectory");
}




?>
<script>
$(function() {
var availableTags1 = <?php echo json_encode(scandir("$subdirectories/diffexpress_output/" . $_SESSION['user_name'])) ?>;
var availableTags2 = <?php echo json_encode(scandir("$subdirectories/mapcount_output/" . $_SESSION['user_name'])) ?>;
var totalTags = availableTags1.concat(availableTags2);
 $( "#tags" ).autocomplete({
source: totalTags, minLength:2, delay:0
});
});

$(function(){
	var analyses=[];
	<?php $diffexpressFiles = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'];?>
	<?php $mapcountFiles = "$subdirectories/mapcount_output/" . $_SESSION['user_name'];?>
	
	var diffExpressAnalyses = <?php echo json_encode(scandir($diffexpressFiles)) ?>;
	var mapCountAnalyses = <?php echo json_encode(scandir($mapcountFiles)) ?>;
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
		$( "#help"+(k)).mouseover(function() { // k = key from the each loop
                	help.dialog( "open" );
                }).mouseout(function() {
                    	help.dialog( "close" );
                });
	});
});

</script>
<body>
<div class="help" id="help0" style="" title="Select Analysis">
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
<center>


<?php

#if the directory for the images to be viewed does not exist, then make it

if(empty($_POST['analysis'])){
	echo '<center>';
	echo '<img src="images/frnak_banner.png" alt="fRNAkenstein" width="600" ><br> <br>';
	echo '</center>';
	echo '<br>';
	echo '<form method="POST" action="data_level_results.php"';	
	echo '<span class="ui-widget">';
	echo'<input id="tags" name="analysis">';
	echo'</span><button id="adder" type="submit">Select Analysis</button><span><span class="helper" id="help0" </span></form>';	
	echo '<table>';
	echo '<tr></tr><td></td><td></td>'; 
	echo '<br><br><br>';
	echo '<div id="button-container">';
	echo '<form action="menu.php">';
	echo '<input type = "submit" id="button" value = "Menu" class="green">';
	echo ' </form>';
	echo '</div>';
	echo '</table>';

	exit('');
}
$analysis = htmlspecialchars($_POST['analysis']);
$diffExpressPathToScan = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis";
$cuffDiffOutput = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/cuffdiff_output";
$mapCountPathToScan = "$subdirectories/mapcount_output/" . $_SESSION['user_name'] . "/$analysis/cufflinks_out";


$pattern = "/((analysis).*)/"; //all of the diffexpress runs are going to begin with the prefix analysis
$isDiffExpress = 0;
$isMapCount = 0;
if(preg_match($pattern, $analysis) == 1)
{
	$isDiffExpress = 1;		
};


if($isDiffExpress == 1)
{
	$files = scandir("$diffExpressPathToScan");	
	$cuffDiffFiles = scandir("$cuffDiffOutput");
	
}

if($isDiffExpress != 1)
{
	$files = scandir("$mapCountPathToScan");

}

?>
<form class="go-bottom" id='submitform' action='data_level_results_response.php' method='post' target='formresponse'>

<!--
Send the analysis path also!
-->

<input type="hidden" value="<?php echo $analysispath; ?>" name="analysispath" />
<input type="hidden" value="<?php echo $analysis; ?>" name="analysis" />

<center>

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

echo "<div id=\"\" style=\"overflow:auto; min-height: 40px; max-height:200px; width:250px;  display: inline-block; position: relative;\"><br>";
foreach ($files as $file)
{
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
			
			if($file == "genes.fpkm_tracking")
			{

				echo "\n<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>genes.fpkm_tracking</a>" . "<span><span class=\"helper\" id=\"help1\" style=\"color:blue;\"><b>?</b></span><br><br>";
			}
			else if($file == "isoforms.fpkm_tracking")
			{
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>isoforms.fpkm_tracking</a>" . "<span><span class=\"helper\" id=\"help2\" style=\"color:blue;\"><b>?</b></span><br><br>";
				//echo "hello2<br><br><br>";
				
			}
			else if($file == "transcripts.gtf")
			{
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>transcripts.gtf</a>" . "<span><span class=\"helper\" id=\"help4\" style=\"color:blue;\"><b>?</b></span><br><br>";
			}
			else if($file == "skipped.gtf")
			{
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>skipped.gtf</a>" . "<span><span class=\"helper\" id=\"help3\" style=\"color:blue;\"><b>?</b></span><br><br>";
			//	echo "hello4<br><br><br>";
			//	echo $pathToFiles . "is the path to the files for the skipped.gtf";
				
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
				echo "<br><br>";
				echo '<br><br>';
				echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>Genes sig in at least" . $geneSigNumber . "</a>";
				$geneSigNumber += 1;
			}
			
			$cuffDiffFiles = scandir("$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/cuffdiff_output");
		}
	
	}
}
echo "hello!!";
foreach($cuffDiffFiles as $file)
{
	echo "well, we are in the first loop that we should be in";
	if(($file != "..") & ($file != "."))
	{
		$pathToFiles = "$subdirectories/diffexpress_output/" . $_SESSION['user_name'] . "/$analysis/cuffdiff_output/$file";
		if($file == "cds.fpkm_tracking")
		{
			echo "well, we are in the loop that we should be in";
			echo "<br><a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>cds.fpkm_tracking</a>" . "<span><span class=\"helper\" id=\"help5\" style=\"color:blue;\"><b>?</b></span><br><br>";
			
		}
		if($file == "cds_exp.diff")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>cds_exp.diff</a>" . "<span><span class=\"helper\" id=\"help6\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "gene_exp.diff")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>gene_exp.diff</a>" . "<span><span class=\"helper\" id=\"help7\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "genes.fpkm_tracking")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>genes.fpkm_tracking</a>" . "<span><span class=\"helper\" id=\"help8\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "isoform_exp.diff")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>isoform_exp.diff</a>" . "<span><span class=\"helper\" id=\"help9\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "isoforms.fpkm_tracking")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>isoforms.fpkm_tracking</a>" . "<span><span class=\"helper\" id=\"help10\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "promoters.diff")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>promoters.diff</a>" . "<span><span class=\"helper\" id=\"help11\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "splicing.diff")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>splicing.diff</a>" . "<span><span class=\"helper\" id=\"help12\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "tss_group_exp.diff")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>tss_group_exp.diff</a>" . "<span><span class=\"helper\" id=\"help13\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}
		if($file == "tss_groups.fpkm_tracking")
		{
			echo "<a href=\"data_level_results_response.php?fetchResults=$pathToFiles\" download>tss_groups.fpkm_tracking</a>" . "<span><span class=\"helper\" id=\"help14\" style=\"color:blue;\"><b>?</b></span><br><br>";
		}	
	}
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
<div id="button-container">
        <form action="menu.php">
        <input type = "submit" id="button" value = "Menu" class="green">
        </form>
</div>
<div id="button-container">
        <form action="http://cole-trapnell-lab.github.io/cufflinks/manual/">
        <input type = "submit" id="button" value = "Tuxedo Pipeline Manual" class="yellow">
        </form>
</div>

<!--
###################
# Response iFrame #
###################
-->
<td valign="top" style="padding-left:0px;align:left">
<br>
<iframe name='formresponse' src='placeholder_response.html' style="border: outset; background-color:#d0eace" width='500px' height='650' frameborder='0'>
</iframe>
</td>
</tr>
</table>





