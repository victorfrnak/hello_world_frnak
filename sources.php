<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">

<script language="JavaScript">
$( document ).ready(function() {

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

		$( "#help"+k ).mouseover(function() { // k = key from the each loop
                	help.dialog( "open" );
                }).mouseout(function() {
                    	help.dialog( "close" );
                });
	});
});
</script>
<body>





<div class="help" id="help0" style="" title="TopHat">
<font size="3"><center>Dachwan K, Pertea G.,Trapnell, C., Pimental, H., Kelly, R., Salzberg S.L.  TopHat2: accurate alignment of transcriptomes in the presence of insertions, deletions and gene fusions.   Genome Biology.  2013.</center></font>
</div>
<div class="help" id="help1" style="" title="Cufflinks And Cuffmerge">
<font size="3"><center>Trapnell, C., Hendrickson, D.G., Sauvageau, M., Goff, L., Rinn, J.L., Pachter, L.  Differential Analysis of Gene Regulation at Transcript Resolution with RNA-seq.  Nature Biotechnology 31:46-53 (2013)</center></font>
</div>
<div class="help" id="help2" style="" title="HTSeq-Count">
<font size="3"><center>Anders, S. Theodor, P Huber, W. HTSeq a Python framework to work with high-throughput sequencing data. Bioinformatics Vol. 31 no. 2 2015, pages 166 169</center></font>
</div>
<div class="help" id="help3" style="" title="SamTools">
<font size="3"><center>Li, H. Handsaker, B., Wysoker, A., Fennell, T., Ruan, J., Marth, G., Abecasis, Durbin, R. 1000 Genome Project Data Processing Subgroup.  The Sequence Alignment/Map format and SAMtools.  Bioinformatics.  2009 Aug 15:25(16):2078-2079</center></font>
</div>
<div class="help" id="help4" style="" title="Cuffdiff">
<font size="3"><center>Trapnell, C., Hendrickson, D.G., Sauvageau, M., Goff, L., Rinn, J.L., Pachter, L.  Differential Analysis of Gene Regulation at Transcript Resolution with RNA-seq.  Nature Biotechnology 31:46-53 (2013)</center></font>
</div>
<div class="help" id="help5" style="" title="cummeRbund">
<font size="3"><center>Goff L, Trapnell C and Kelley D (2013). cummeRbund: Analysis, exploration, manipulation, and visualization of Cufflinks high-throughput sequencing data.. R package version 2.10.0.</center></font>
</div>
<div class="help" id="help6" style="" title="BaySeq">
<font size="3"><center>Hardcastle, T.J. Kelly, K.A. baySeq: Empirical Bayesian methods for identifying differential expression in sequence count data. BMC Bioinformatics 2010.  11:422</center></font>
</div>
<div class="help" id="help7" style="" title="EdgeR">
<font size="3"><center>Robinson, M.D., McCarthy, D.J., Smyth, G.K.  edgeR: a Bioconductor package for differential expression analysis of digital gene expression data.  Bioinformatics.  2010. Jan 1:28(1):139-140.</center></font>
</div>
<div class="help" id="help8" style="" title="DeSeq2">
<font size="3"><center>Love, M. Huber, W., Anders, S. Moderated Estimation of Fold Change and Dispersion for RNA-Seq with DESeq2. Genome Biology. 2014, 15:550.</center></font>
</div>
<div class="help" id="help9" style="" title="Dr. Wheeler RNA Seq Blog">
<font size="3"><center>RNA Seq Blog from Dr. Dave Wheeler, instrumental in setting up DESeq2 pipeline</center></font>
</div>
<div class="help" id="help10" style="" title="Negative Binomial">
<font size="3"><center>Intro to Negative Binomial for Count Data</center></font>
</div>
<div class="help" id="help11" style="" title="Negative Binomial">
<font size="3"><center>In most differential expression packages today, RNA-seq reads under the null are modeled by the Negative Binomial Distribution.  The original choice to model the data, the Poisson did not capture sufficient variability - hence researchers developed an Overdispersed Poisson approach. The Overdispersed Poisson is mathematically equivalent to the Negative Binomial.  I've attached papers explaining some of the math behind the evolution of these statistics. Researchers often wonder about the rationale of using specific statistics when they want to evaluate p-values to choose validation candidates. </center>
</div>




<center>



<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<table>

<tr>
<td>
<div id="button-container">
        <form action="http://www.genomebiology.com/2013/14/4/R36/">
        <input type = "submit" id="button" value = "TopHat" class="testbutton">
        </form>
</div><span class="helper" id="help0"><b>      </b></span><br><br>
</td>

<td>
<div id="button-container">
        <form action="http://www.ncbi.nlm.nih.gov/pubmed/22383036">
        <input type = "submit" id="button" value = "CuffLinks and Cuffmerge" class="testbutton">
        </form>
</div><span class="helper" id="help1"><b>      </b></span><br><br>
</td> 
  
<td>
<div id="button-container">
        <form action="http://www.ncbi.nlm.nih.gov/pubmed/25260700">
        <input type = "submit" id="button" value = "HTSeq-Count" class="testbutton">
        </form>
</div><span class="helper" id="help2"><b>      </b></span><br><br>
</td> 
  


</tr>

<tr>

<td>

<div id="button-container">
        <form action="http://www.ncbi.nlm.nih.gov/pmc/articles/PMC2723002/">
        <input type = "submit" id="button" value = "SAMTools Paper" class="testbutton">
        </form>
</div><span class="helper" id="help3"><b>      </b></span><br><br>
</td>


<td>
<div id="button-container">
        <form action="http://www.nature.com/nbt/journal/v31/n1/abs/nbt.2450.html">
        <input type = "submit" id="button" value = "CuffDiff Paper" class="testbutton">
        </form>
</div><span class="helper" id="help4"><b>      </b></span><br><br>
</td>


<td>
<div id="button-container">
        <form action="http://140.107.3.20/packages/release/bioc/vignettes/cummeRbund/inst/doc/cummeRbund-manual.pdf">
        <input type = "submit" id="button" value = "CummeRbund Paper" class="testbutton">
        </form>
</div><span class="helper" id="help5"><b>     </b></span><br><br>
</td>

</tr>


<tr>

<td>
<div id="button-container">
        <form action="http://www.biomedcentral.com/1471-2105/11/422">
        <input type = "submit" id="button" value = "BaySeq Paper" class="testbutton">
        </form>
</div><span class="helper" id="help6"><b>     </b></span><br><br>
</td>

<td>
<div id="button-container">
        <form action="http://www.ncbi.nlm.nih.gov/pmc/articles/PMC2796818/">
        <input type = "submit" id="button" value = "EdgeR Paper" class="testbutton">
        </form>
</div><span class="helper" id="help7"><b>    </b></span><br><br>
</td>

<td>
<div id="button-container">
        <form action="http://www.genomebiology.com/2014/15/12/550">
        <input type = "submit" id="button" value = "DESeq2 Paper" class="testbutton">
        </form>
</div><span class="helper" id="help8"><b>   </b></span><br><br>
</td>

</tr>

<tr>

<td>
<div id="button-container">
        <form action="http://dwheelerau.com/2014/02/17/how-to-use-deseq2-to-analyse-rnaseq-data/">
        <input type = "submit" id="button" value = "Dr. Wheeler Blog" class="testbutton">
        </form>
</div><span class="helper" id="help9"><b>    </b></span><br><br>
</td>



<td>
<div id="button-container">
        <form action="http://data.princeton.edu/wws509/notes/c4a.pdf/">
        <input type = "submit" id="button" value = "Negative Binomial" class="testbutton">
        </form>
</div><span class="helper" id="help10"><b>    </b></span><br><br>
</td>

<td>
<div id="button-container">
        <form action="http://www.johndcook.com/negative_binomial.pdf">
        <input type = "submit" id="button" value = "N.B. Derivation" class="testbutton">
        </form>
</div><span class="helper" id="help11"><b>     </b></span><br><br>
</td>
</tr>

</table>

<center>

<div id="button-container">
        <form action="instructions.php">
        <input type = "submit" id="button" value = "Back" class="yellow">
        </form>
</div>
 
<div id="button-container">
        <a href="citations.php">
        <!--<form action="sources_response.php?fetchResults=/opt/apache2/frankdec/frnakenstein/citations.txt\"> -->
        <input type = "submit" id="button" value = "References Page" class="green">
        </form>
</div><span class="helper" id="help8"><b>   </b></span><br><br>

