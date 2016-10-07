<!----!>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>

<script>
$(document).ready(function(){

	$('#loading-image', window.parent.document).hide();
	$('#page-cover', window.parent.document).hide();
	alert("This graph shows expression level from only one of the expression packages, cuffdiff");
});

</script>


<?php
session_start();
$toPutInFigures = $_POST['genes'];
//$figureType = strip_tags (htmlspecialchars( escapeshellcmd($_POST['figureType'])));
$figureType = "all";
$geneOrIsoform = strip_tags (htmlspecialchars( escapeshellcmd($_POST['geneOrIsoform'])));
$barOrLine = strip_tags (htmlspecialchars( escapeshellcmd($_POST['barOrLine'])));
$errorBars = strip_tags (htmlspecialchars( escapeshellcmd($_POST['errorBars'])));
$analysispath = strip_tags (htmlspecialchars( escapeshellcmd($_POST['analysispath'])));
$analysis = strip_tags (htmlspecialchars( escapeshellcmd($_POST['analysis'])));
$analysispath = $analysispath . "/cuffdiff_output";
$userName = $_SESSION['user_name'];
//echo $_SESSION['user_name'] . "is the username";
$file = "/opt/apache2/frankdec/subdirectories/diffexpress_output/" . $userName . "/$analysis/cuffdiff_output/cummerbund_images/all.jpg";

#if no genes are selected, echo and then exit the page without running

//echo $toPutInFigures ." is the toPutinFigures";
if(count($toPutInFigures) == 0)
{
	echo "<h4>Error 1: please select a gene.  You must add genes to the list using adder and then select them </h4>";
	exit("");
}


foreach($toPutInFigures as &$gene)
{
	$gene = strip_tags (htmlspecialchars( escapeshellcmd($gene)));
}

#plot command is going to be the string which we will attach all of the commands to
$plotcommand = "";

#determine if the user wants to plot with a line or a bar graph plot
if ($geneOrIsoform == "gene")
{ 
	$plotcommand = "".$barOrLine."(gene, showErrorbars='$errorBars') \n";
}
else if ($geneOrIsoform == "isoform")
{
	#echo "Isoform Level<br>";
	$plotcommand = "".$barOrLine."(isoforms(gene), showErrorbars='$errorBars') \n";
}

# load cummerbund and initialize graph command
$graphCommand = "library(cummeRbund)\n";

$graphCommand = "library(cummeRbund)\n";

//# Set Working Directory
$graphCommand .= "setwd(\"$analysispath/\")\ncuff <- readCufflinks()\n";

//# Set Image Path
$imagepath = "$analysispath/cummerbund_images";

//echo $imagepath . "is the image path";

//make the directory for the images
//NOTE COMMENTED THIS OUT ON THE 28TH

//mkdir($imagepath, 0777, true);
chmod($imagepath, 0777);

if (!file_exists($imagepath)) {
    mkdir($imagepath);
    chmod($imagepath, 0777);
}



if($figureType == "all")
{
	$count = 0;
	$geneVector = "";	

	$graphCommand .= "jpeg(\"$imagepath/all.jpg\")\n";

	foreach($toPutInFigures as &$gene)
	{
		$count += 1;
		
		if($count < count($toPutInFigures))
		{		
			$geneVector = $geneVector . "\"$gene\",";
		}
		else
		{
			$geneVector = $geneVector . "\"$gene\"";
		}
	}


	$graphCommand .= "gene <- getGenes(cuff,c($geneVector));\n".$plotcommand;  
	$graphCommand .= "dev.off()\n";

}

//combine genes into a string
//which will be passed as the session variable

else if($figureType == "separate")
{
	$count = 0;	
	#echo "Figure Type: Separate<br>";
	$listOfGenes = "";
	foreach($toPutInFigures as &$gene)
	{
         
		$graphCommand .= "png(\"$imagepath/$gene.png\")\n";

		$graphCommand .= "gene <- getGenes(cuff,\"$gene\");\n".$plotcommand; 	
		$graphCommand .= "dev.off()\n";
		if($count == 0)
		{
			$listOfGenes = $gene;
		}
		if($count != 0)
		{
			$listOfGenes .= "," . $gene;	
		}
		$count += 1; 
	}
}

//ALL OF THE STUFF DOWN HERE IS IMPORTANT
$rfile = "$analysispath/images.txt";
exec ("touch $rfile");
//echo "touch $rfile is the command to touch the rfile"; 
//echo $rfile . "is the r file";
//$myfile = fopen("$rfile", "w");
//fwrite($myfile, $graphCommand);
//fclose($myfile);

file_put_contents($rfile, $graphCommand, LOCK_EX);
//echo "well, we have added to the file";
//just briefly commented on the 20th
exec("R --vanilla < $rfile");

session_start();

if($figureType == "all")
{
	$_SESSION['varname'] = $file;
	$_SESSION['analysis'] = $analysis;
}

if($figureType == "separate")
{
	$_SESSION['varname'] = $listOfGenes;
	//echo $listOfGenes . "is the list of genes!!";
	$_SESSION['analysis'] = $analysis;
	//echo $analysis . "is the analysis";
}


//echo $file . "is file!!";
//echo $_SESSION['varname'] . "is $_SESSION['varname']";
?>


</td>
<td style="padding-left:24px">

<iframe id='frame' name='formresponse' src='placeholder_response.php' style="border: outset; background-color:#d0eace" width='500px' height='700px' frameborder='0'></iframe>

</td>
</tr>
 
