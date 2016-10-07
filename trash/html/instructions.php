<!--
##########
# Header #
##########
-->

<head>
<title>
fRNAkenstein:"Beware; for I am fearless, and therefore powerful."
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="STYLESHEET" type="text/css" href="css_dir/buttonStyle.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

</head>
<center>

<!-- for coordinates -->
<script language="JavaScript">
$( document ).ready(function() {

	$( "#dialog" ).dialog({
		autoOpen: false,
		buttons: [ { text: "Okay", click: function() { $( this ).dialog( "close" ); } } ],
		show: {
			effect: "puff",
			duration: 250
		},
		hide: {
			effect: "puff",
			duration: 250
		},
		width: 450,
		height: 450,
	});

});

function point_it(event){
	allow = 1;
	pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("pointer_div").offsetLeft;
	pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("pointer_div").offsetTop;
	
	if (allow == 1){
		/* For the record, I think this is ugly */
		if(pos_y >= 3 && pos_y <= 102 && pos_x >= 172 && pos_x <= 252)
		{
			//FASTQ FILE
			// # = id ; $(<x>) = reference to element
			// #dialog = ref to dialog element
			$( "#dialog" ).dialog( "open" );
			$("#dialog p").text("FASTQ format is a text-based format for storing both a biological sequence and its corresponding quality scores. The FASTQ file is the input library for Tophat.");
			//make dialog box an option box, set the title to 'Fastq file'
			$('#dialog').dialog('option', 'title', 'Fastq File');
			return;
		}
		if(pos_x >= 139 && pos_x <= 285)
		{
			if(pos_y >= 142 && pos_y <= 204)
			{
				//Tophat
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Using a reference FASTA file, Tophat maps reads to the genome. More formally, TopHat is a fast splice junction mapper for RNA-Seq reads. It aligns RNA-Seq reads to various genomes using the ultra high-throughput short read aligner Bowtie, and then analyzes the mapping results to identify splice junctions between exons. ");
				$('#dialog').dialog('option', 'title', 'Tophat');
				return;
			}
			else if (pos_y >= 342 && pos_y <= 403)
			{
				//CUFFLINKS
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Cufflinks assembles mapped reads into transcripts using an annotation. Each transcript contains relative expression values for each isoform and gene.");
				$('#dialog').dialog('option', 'title', 'Cufflinks');
				return;
			}
		}
		if(pos_x >= 151 && pos_x <= 273)
		{
			if(pos_y >= 242 && pos_y <= 303)
			{
				//Mapped Reads
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Reads that have been mapped to the genome that are used for input to Cufflinks. (Format .BAM)");
				$('#dialog').dialog('option', 'title', 'Mapped Reads');
				return;
			}
			else if (pos_y >= 442 && pos_y <= 504)
			{
				//Assembled Transcripts
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Transcripts with relative expression values used for differential expression. (Format .gtf)");
				$('#dialog').dialog('option', 'title', 'Assembled Transcripts');
				return;
			}
		}
		if(pos_x >= 528 && pos_x <= 675)
		{
			if(pos_y >= 142 && pos_y <= 204)
			{
				// Cuffmerge
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Cuffmerge takes multiple libraries as input (in the form of their assembled transcripts) in order to create a consensus annotation and conducts normalization to prepare for differential expression analysis.");
				$('#dialog').dialog('option', 'title', 'Cuffmerge');
				return;
			}
			else if (pos_y >= 342 && pos_y <= 403)
			{
				// Cuffdiff
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Four differential expression packages are applied to the given data set: Cuffdiff, BaySeq, DeSeq2 and EdgeR.  Each software package applies different statistical approaches, though underlying assumptions are similar.  Sets of enriched genes are generally overlapping but not identical from each algorithm.  Cuffdiff is a non parametric tool generally operated via command line while BaySeq, DESeq2 and edgeR are R based packages.  BaySeq relies primarily on Bayesian statistics while edgeR and Deseq2 generate more traditional p values.");
				$('#dialog').dialog('option', 'title', 'Differential Expression');
				return;
			}
		}
		if(pos_x >= 541 && pos_x <= 663)
		{
			if(pos_y >= 242 && pos_y <= 303)
			{
				//Transcriptome Assembly
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Merged annotation file for use in differential expression analysis. (Format .gtf)");
				$('#dialog').dialog('option', 'title', 'Transcriptome Assembly');
				return;
			}
			else if (pos_y >= 442 && pos_y <= 504)
			{
				//DiffExp Output
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("Final differential expression output. (Each package with different formats)");
				$('#dialog').dialog('option', 'title', 'DiffExpress Output');
				return;
			}
		}
		if(pos_y >= 120 && pos_y <= 526)
		{
			if(pos_x >= 67 && pos_x <= 361)
			{
				//THCL
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("The first stage of the fRNAkenstein pipeline. Performs gene mapping and transcript assembly for input libraries. First, select the libraries you want to generate transcripts for. Then, select the number of processors (recommended 24), the annotation file, and the fasta file for this stage. A confirmation of your scheduled task will be displayed in the window to the right, along with the unique run ID. Save this for your records.");
				$('#dialog').dialog('option', 'title', 'THCL');
				return;
			}
			else if (pos_x >= 452 && pos_x <= 746)
			{
				//DiffExpress
				$( "#dialog" ).dialog( "open" );
				$("#dialog p").text("The second stage of the fRNAkenstein pipeline. Assembles consensus annotation and generates differential expression results. First, name the control and experimental conditions, and select the control and experimental libraries. If you know the library numbers of an any old library you want to add, you can use the \"add archived library\" feature to add them. Then, select the number of processors (recommended 24), the annotation file, and the fasta file for this stage. Finally, name the analysis, choose the annotation format (NCBI or Ensembl), and begin the analysis. A confirmation of your scheduled task will be displayed in the window to the right, along with the unique run ID. Save this for your records.");
				$('#dialog').dialog('option', 'title', 'DiffExpress');
				return;
			}
		}
	}
}
</script>



</head>
<body>
<center>
<!--
###########################
# Formatting Box & Legend #
###########################
-->
<style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
    }
</style>
<div>
<fieldset class="fieldset-auto-width">
<legend>
<h3>
The fRNAkenstein Pipeline
</h3>
</legend>
<h4>Click on an element for more information.</h4><br>
<div id="pointer_div" onclick="point_it(event)" style = "background-image:url('images/flowchart.png');height:528;width:861">
</div>
<br><div class='container'>
<font size="2">Click inside "Tophat and Cufflinks" or "DiffExpress" for stage instructions.</font></div>
<br> <br> <br> <br>
<form action="menu.php">
    <input align = "bottom" type="submit" value="Return to Menu">
</form>


</fieldset>

<div id="dialog" style="display:none;" title="">
  <p></p>
</div>


</link>

</fieldset>
</body>
