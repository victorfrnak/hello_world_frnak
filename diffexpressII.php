<!--
######################################
# fRNAkenstein                       #
#   by Allen Hubbard & Wayne Treible #
#                                    #
# Version 0.10 Updated 6/17/2014     #
######################################
-->

<?php 
 
#####################################################
# Parse config file, load values, and start session #
#####################################################

$ini = parse_ini_file("../config.ini.php", true);
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];

session_start();

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


<head>
<title>
fRNAkenstein - DiffExpress Cruncher
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<!--
##############################################
# Initilaize PhP variables for jQuery script #
##############################################
-->

<?php
# Can modify later with real values
$archivedlibs = scandir("$subdirectories/mapcount_output");
$archivedlibs = preg_replace(array("/(.*)library_/","/\./","/\.\./"), "", $archivedlibs);

?>

<!--
##########################
# Archive Checkbox Adder #
##########################
-->

<script>

$(document).ready(function(){
	var libs = <?php echo json_encode($archivedlibs); ?>;


	$("#ctrlchk").click(function(){
		var inp = $("#ctrltxt");
		if(inp.val().length > 0) {
			var library = inp.val();
			if(jQuery.inArray(library, libs) != -1)
			{
				var $ctrl = $('<input/>').attr({ type: 'checkbox', name:'controlfilename[]', value: 'library_'+library}).addClass("ctrlchk");
				$("#ctrlholder").append($ctrl);
				$("#ctrlholder").append(library);
				$("#ctrlholder").append("<br>");
				libs = jQuery.grep(libs, function(value) {
  					return value != library;
				});
			}
			else {
				alert("Library \'" + library + "\' is not in the archive or already added as a separate condition.");
			}
		}
		else {
			alert("Please enter a library number");
		}

	});

	$("#expchk").click(function(){
		var inp = $("#exptxt");
		if(inp.val().length > 0) {
			var library = inp.val();
			if(jQuery.inArray(library, libs) != -1)
			{
				var $exp = $('<input/>').attr({ type: 'checkbox', name:'expfilename[]', value: 'library_'+library}).addClass("expchk");
				$("#expholder").append($exp);
				$("#expholder").append(library);
				$("#expholder").append("<br>");
				libs = jQuery.grep(libs, function(value) {
  					return value != library;
				});
			}
			else {
				alert("Library \'" + library + "\' is not in the archive or already added as a separate condition.");
			}
		}
		else {
			alert("Please enter a library number");
		}

	});


	/*$('.Blocked').change( function() {
    		var isChecked = this.checked;
    		if(isChecked) {
        		$(this).parents("tr:eq(0)").find(".textbox").prop("disabled",true); 
    		} else {
        		$(this).parents("tr:eq(0)").find(".textbox").prop("disabled",false);
    		}
	});*/

	$(".Blockedctrl").click(function(event) {
		var val = event.target.value;
		var myname = "controlfilename[]";
		var othername = "expfilename[]";
		if(this.checked){
			$('input:checkbox[name="' + othername + '"][value="' + val + '"]').attr("disabled", true);

		} else {
			$('input:checkbox[name="' + othername + '"][value="' + val + '"]').removeAttr("disabled");
		};

	});
	$(".Blockedexp").click(function(event) {
		var val = event.target.value;
		var myname = "expfilename[]";
		var othername = "controlfilename[]";
		if(this.checked){
			$('input:checkbox[name="' + othername + '"][value="' + val + '"]').attr("disabled", true);

		} else {
			$('input:checkbox[name="' + othername + '"][value="' + val + '"]').removeAttr("disabled");
		};


	});

	        
        $("#genomes").autocomplete({
        	source:[],
        	select: function(event, ui) {
            		$('#genomes').val(ui.item.label.replace(/<(?:.|\n)*?>/gm, ''));
            		return false; // Prevent the widget from inserting the value.
        	},
	        focus: function(event, ui) {
            		return false; // Prevent the widget from inserting the value.
        	}
	});

	$.ui.autocomplete.prototype._renderItem = function (ul, item) {
        	item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong><mark>$1</mark></strong>");
        	return $("<li></li>")
        		.data("item.autocomplete", item)
                	.append("<a>" + item.label + "</a>")
                	.appendTo(ul);
	};
});


</script>

<!--
######################
# Form Validator #
######################
-->

<script language="javascript">
function search_genomes(search_term) {
    var url = "https://geco.iplantc.org/coge/api/v1/genomes/search/" + search_term;
        $.getJSON(url, function(data) {
                if (data.genomes) {
                        var genomes = [];
                        for (var i = 0; i < data.genomes.length; ++i){
                                //genomes.push(data.genomes[i].organism.name + " [ID: " + data.genomes[i].id+"]")
				genomes.push(data.genomes[i].name + " [ID: " + data.genomes[i].id+"]");
                        }
                        $("#genomes").autocomplete({source: genomes});
                        $("#genomes").autocomplete("search");
                }
    });
}


var timer;

function wait_to_search (search_func, search_obj) {
    var search_term = search_obj.value;

    if (!search_term || search_term.length >= 2) {

        if (timer) {
            clearTimeout(timer);
        }

        timer = setTimeout(
            function() {
                search_func(search_obj.value);
            },
            100
                );
        }
}

function valthisform()
{
	var controlcheckboxs=document.getElementsByName("controlfilename[]");
	var expcheckboxs=document.getElementsByName("expfilename[]");
	var cbokay=false;
	var inputokay=false;
	var analysisokay=false;
	var dispersion = 0;
	var controllength = controlcheckboxs.length;
	var explength = expcheckboxs.length;
	for(var i=0,l=controlcheckboxs.length;i<l;i++)
	{
		for(var ii=0,ll=expcheckboxs.length;ii<ll;ii++)
		{
			if(controlcheckboxs[i].checked && expcheckboxs[ii].checked)
			{
	   			cbokay=true;
			}
		}
	}
	
	var controlcount = 0;
	for(var i=0,l=controlcheckboxs.length;i<l;i++)
	{
	    if(controlcheckboxs[i].checked)
	    {
	      controlcount += 1;	
	  
	    }
	}
	
	var expcount = 0;
	for(var i=0,l=expcheckboxs.length;i<l;i++)
	{
	    if(expcheckboxs[i].checked)
	    {
	      expcount += 1;	
	    }
	}
	
	/*
	if ((controlcheckboxs.length  == 1))
	{
	  
	  alert("you are going to need to enter a value for the dispersion, as you do not have any replicates");
	
	}
	*/
	if(document.getElementById('controlcondition').value != "" && document.getElementById('expcondition').value != "")
	{
		inputokay=true;
	}
	if(document.getElementById('analysisname').value != "")
	{
		analysisokay=true;
	}
	
	if(cbokay && inputokay && analysisokay)
	{
		if((expcount == 1) && (controlcount == 1))
		{
		  alert("When doing RNAseq, differential expression algorithms calculate variation in gene expression among samples of the same condtion; this is called dispersion.  If you don't have replicates, however, you need to provide and estimate of dispersion");
		  var dispersion = prompt("please enter the dispersion value");
    
		  $.post("diffexpress_response.php", {"dispersion" : dispersion});
		}
		alert("Running DiffExpress on Data!");
		document.getElementById('crunch').className = "disabled";
                document.getElementById('crunch').disabled = 1
	}
	else if(inputokay && analysisokay)
	{
		alert("Please select both libraries.");
	}
	else if(cbokay && analysisokay)
	{
		alert("Please name both conditions.");
	}
	else if(inputokay && cbokay)
	{
		alert("Please enter an analysis name.");
	}
	else
	{
		alert("Please complete all input fields.");
		alert(controlcount + "is the control length" + expcount + "is the expcount");
	}
	return cbokay && inputokay && analysisokay;
}

</script>

<!--
#########################
# Help Dialog Box Stuff #
#########################
-->

<script language="JavaScript">
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

                $( "#help"+k ).mouseover(function() { // k = key from the each loop
                        help.dialog( "open" );
                }).mouseout(function() {
                        help.dialog( "close" );
                });
        });
});

</script>

</head>
<body>
<div class="help" id="help" style="" title="Control Condition">
<font size="3"><center>Enter a name for the control condition of your run.</center></font>
</div>
<div class="help" id="help1" style="" title="Control Libraries">
<font size="3"><center>Choose library numbers in the control group for differential expression analysis following  mapping and assembly with FPKM and raw count quantification.</center></font>
</div>
<div class="help" id="help2" style="" title="Experimental Condition">
<font size="3"><center>Enter a name for the treatment condition of your run</center></font>
</div>
<div class="help" id="help3" style="" title="Experimental Libraries">
<font size="3"><center>Choose library numbers in the treatment group for differential expression analysis following  mapping and assembly with FPKM and raw count quantification.</center></font>
</div>
<div class="help" id="help4" style="" title="Number of Processors">
<font size="3"><center>Choose number of processors across which to thread the cuffdiff component of diffExpress</center></font>
</div>
<div class="help" id="help5" style="" title="Fasta File">
<font size="3"><center>Select the reference fasta file for diffExpress, it should be the same as that which the libraries were aligned against for mapping in MapCount </center></font>
</div>
<div class="help" id="help6" style="" title="Annotation File">
<font size="3"><center>Select the reference annotation file for diffExpress, it should be the same as that which was used for transcript assembly in MapCount</center></font>
</div>
<div class="help" id="help7" style="" title="Analysis Name">
<font size="3"><center>Please enter an informative name for this run of diffExpress.</center></font>
</div>



<center>
<!--
############################
# Beginning of submit form #
############################
-->
<style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
    }
</style>
<div>
<!fieldset class="fieldset-auto-width">

<!legend>
<h3>
<!--fRNAkenstein - DiffExpress Cruncher-->
</h3>
</legend>

<?php

if( $_SESSION['user_name'] != "guest")
{
        echo " <form id=\"submitform\" onsubmit=\"return valthisform(this);\" action=\"diffexpress_response.php\" method=\"post\" target=\"formresponse\"> ";
}
else
{
        echo "<form method=\"post\">"; 
} ?>


<!--
################################
# Beginning of alignment table #
################################
-->

<table style="margin: 0px;">

<th colspan="3" >
<a href="menu.php"><img src="images/frnak_banner_diffexpress.png" style="border:0;padding-right:25px;" alt="fRNAkenstein" width="600" ></a> </td> <br>
</th>


<!--
##########################################################
# Create Checkboxes for control library files (lib nums) #
##########################################################
-->


<tr style="padding:0px; margin:0px;">
<td valign="top" align="left" style="padding-top:12px;padding-left:8px;width:225px">

<div class='container'>
<span><b>Control Condition:</b></span><span class="helper" id="help0" style="color:blue;"><b>?</b></span><br><br>
<input type="text" id="controlcondition" name="controlcondition"><br><br>

<?php
$dispersion = 0;
$dispersion = $_POST['dispersion'];
$controllibs = scandir("$subdirectories/mapcount_output/" . $_SESSION['user_name']);
echo $dispersion;
# Sorts files by "natural human sorting" such that:
# 1.ext                       1.ext
# 10.ext     ==becomes==>     2.ext
# 2.ext                       10.ext 
if(!empty($controllibs))
{
  natsort($controllibs);
}

$outputwindowcount = 0;

echo "<b>Choose control library number(s):</b><span class=\"helper\" id=\"help1\" style=\"color:blue;\"><b>?</b></span><br><br>";
echo "<div id=\"\" style=\"overflow:auto; min-height:40; max-height:200px; width:200px;  display: inline-block; position: relative;\">";
if(count($controllibs)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No libraries ready to crunch!<br>";
} else {
  echo "<br>";
  foreach($controllibs as $library)
  {
    if ($library !== "." and $library !== "..")
    {
      $librarynum = "";
      $libpattern = "/\D*(.*)/";
      preg_match($libpattern, $library, $matches);
      $librarynum = $matches[1];

      echo '<div class="frnakcheckbox">';
      echo "<input type=\"checkbox\" id=\"control".$librarynum."\" name=\"controlfilename[]\" class=\"blockedctrl\" value=\"$library\">";
      echo '<label for="control'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.'</div><br>';

    }
    $outputwindowcount += 1;
  } 
  echo "</select></div>";
}  


?>

</div>
</div>
<!--
##############################
# Archived Control Libraries #
##############################
-->
<!--
<h4>Add archived control library:</h4> 

<div id='ctrlholder' style='padding-bottom:10px'>

</div>

<input type="text" id="ctrltxt"> <button id="ctrlchk" type="button">+</button>
-->

</td>

<!--
###############################################################
# Create Checkboxes for experimental library files (lib nums) #
###############################################################
-->

<td valign="top" align="left" style="padding-top:12px;padding-left:8px;width:225px">
<div class='container'>
<span><b>Experimental Condition:</b></span><span class="helper" id="help2" style="color:blue;"><b>?</b></span><br><br>
<input type="text" id="expcondition" name="expcondition"><br><br>

<?php
$explibs = scandir("$subdirectories/mapcount_output/". $_SESSION['user_name']);

# Sorts files by "natural human sorting" such that:
# 1.ext                       1.ext
# 10.ext     ==becomes==>     2.ext
# 2.ext                       10.ext 
if(!empty($explibs))
{
  natsort($explibs);
}

echo "<b>Choose experimental library number(s):</b><span class=\"helper\" id=\"help3\" style=\"color:blue;\"><b>?</b></span><br><br>";
echo "<div id=\"\" style=\"overflow:auto; min-height:40px; max-height:200;  width:200px;  display: inline-block; position: relative;\">";
if(count($explibs)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No libraries ready to crunch!<br>";
} else {
	echo "<br>";
	foreach($explibs as $explibrary)
	{
	  if ($explibrary !== "." and $explibrary !== "..")
	  { 
	    $librarynum = "";
	    $libpattern = "/\D*(.*)/";
	    preg_match($libpattern, $explibrary, $matches);
	    $librarynum = $matches[1];
	    echo '<div class="frnakcheckbox">';
	    echo "<input type=\"checkbox\" id=\"exp".$librarynum."\" name=\"expfilename[]\" class=\"blockedexp\" value=\"$explibrary\">";
	    echo '<label for="exp'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.'</div><br>';
	  }
	}

	echo "</select>";
}
?>

</div>

<!--
##############################
# Archived Control Libraries #
##############################
-->
<!--
<h4>Add archived experimental library:</h4> 

<div id='expholder' style='padding-bottom:10px'>


</div>
<input type="text" id="exptxt"> <button id="expchk" type="button">+</button>
-->

</td>

<!--
#######################
# iFrame for Response #
#######################
-->

<td rowspan="2" valign="top" style="padding-left:0px;align:left;">
<br>
<iframe id='frame' name='formresponse' src='placeholder_response.html' style="border: none; background-color:#d0eace; position:relative;" width='400px' height="550px"; ?>' frameborder='0'>

</iframe>
</td>
</tr>

<!--
#############################
# Row for form and response #
#############################
-->

<tr style="padding:0px; margin:0px;">
<td colspan="2" valign="top" style="padding-top:12px;padding-left:8px;width:500px">
<div class='container'>

<!--
######################################
# Proc Selector Slider (JS onchange) #
######################################
-->

<span><b>Number of processors:</b></span><span class="helper" id="help4" style="color:blue;"><b>?</b></span><br><br>
<script>
function showVal(newVal){ 
    document.getElementById("slideVal").innerHTML = newVal;
}
</script>

<div style="float:left;">Run on&nbsp;</div>
<div id="slideVal" style="float:left;">24</div>
<div style="float:left;">&nbsp;processor(s)</div><br>

<div style="height:30px;width:250px;float:left;">
1<input name="procs" type="range" min="1" max="31" step="1" value="24" oninput="showVal(this.value)"> 31</div>
<br>
<br>
<!--
################################
# Create DDBox for fasta files #
################################
-->

<?php/*
$fafiles = scandir("$subdirectories/fasta_directory"); 

echo "<b>Choose a fasta:</b><span class=\"helper\" id=\"help5\" style=\"color:blue;\"><b>?</b></span><br><br>";
if(count($fafiles)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No fasta files available! (email wtreible@udel.edu)<br>";
} else {
	echo "<select name=\"fafilename\">";
	foreach ($fafiles as $fafile)
	{
	  if (($fafile != ".") and ($fafile != ".."))
	  { 
	    echo "<option value=\"$fafile\">$fafile</option>";
	  }
	} 
	echo "</select>";
}
*/
?>

<span><b>Choose a Genome: </b></span><span class="helper" id="help2" style="color:blue;"><b>?</b></span><br><br>
<span class="ui-widget">
<input name='genome' id="genomes" type="search" placeholder="Search Genomes..." spellcheck="false" onclick='$(this).autocomplete("search");' onkeypress="wait_to_search(search_genomes, this);" style="width: 200px;">
</span>
<!--
#####################################
# Create DDBox for annotation files #
#####################################
-->

<?php
/*
$afiles = scandir("$subdirectories/annotation_directory");

echo "<b>Choose an annotation file:</b><span class=\"helper\" id=\"help6\" style=\"color:blue;\"><b>?</b></span><br><br>";
if(count($afiles)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No annotation files available! (email wtreible@udel.edu)<br>";
} else {
	echo "<select name=\"afilename\">";
	foreach ($afiles as $afile) 
	{
	  if(($afile != ".") and ($afile != ".."))
	  { 
	    echo "<option value=\"$afile\">$afile</option>";
	  }
	} 
	echo "</select>";
}*/
?>

<br><br>

<!--
#################
# Analysis Name #
#################
-->

<span><b>Analysis Name:</b></span><span class="helper" id="help7" style="color:blue;"><b>?</b></span><br><br>
<input type="text" id="analysisname" name="analysisname">
<br><br>
<!--
#################
# Captcha Stuff #
#################
-->

<!--<?php
require_once('recaptchalib.php');
$publickey = "6LfK0PUSAAAAANftfso7uj8OdyarzxH0zvst0Tmf"; 
#echo "Finally... Prove you're not a robot!";
#echo recaptcha_get_html($publickey);
?>-->

<br>

<input type='hidden' name='submitted' id='submitted' value='1'/>
<!--
##############
# Dialog Box #
##############
-->
<div id="dialog" style="display:none;" title="">
  <p></p>
</div>

<!--
###########################
# Submit and Menu Buttons #
###########################
-->

<button class="crunch" id="crunch" type="submit">fRNAkenstein, Crunch!</button>
<br> <br> <br>

</form>
<form action="menu.php">
    <input align="bottom" type="submit" value="Return to fRNAkenstein Menu">
</form>
</td>
</div>
</td>


<!--
#######################
# Footer and clean-up #
#######################
-->

</tr>
</table>
</link></form>
</fieldset>
</body>
