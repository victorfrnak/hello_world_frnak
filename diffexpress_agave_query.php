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
$token = $_SESSION['access_token'];
$time_out = $_SESSION['SESSION_TIMEOUT'];
$user = $_SESSION['user_name'];

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

//test for the big ten and copy over if it doesn't exit
$directoryBigTen = "/opt/apache2/frankdec/subdirectories/genome_directory/" . $user . "/big_ten/";
$diffdir = "$subdirectories/diffexpress_output/".$_SESSION['user_name'];

if (file_exists($directoryBigTen) == FALSE)
{
	mkdir($diffdir);
	chmod(0777,$diffdir);
}



$authorization = "Authorization: Bearer $token";

$ch = curl_init("https://agave.iplantc.org:443/files/v2/listings/schmidtc/coge_data/frank_mapcount_output");

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
        $file = $obj["result"][$i]["name"];
        //echo $file . " is the file about to added \n \n";
//        echo $file . " is the file !!! \n \n";
        array_push($filesFromDataStore, $file);

// . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";
}



$fqfiles = $filesFromDataStore;


//if (file_exists($directoryBigTen) == FALSE) 
//{
    //    exec("cp -r /opt/apache2/frankdec/subdirectories/genome_directory/big_ten/ /opt/apache2/frankdec/subdirectories/genome_directory/$user/big_ten/");
  //      exec("chmod 777 /opt/apache2/frankdec/subdirectories/genome_directory/$user/big_ten/*/*");
//}

?>


<head>
<title>
fRNAkenstein - DiffExpress Cruncher
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="STYLESHEET" type="text/css" href="css_dir/buttonStyle.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">

<style>
.frnakcheckboxHelper {
    visibility: visible
}
</style>
<!--
##############################################
# Initilaize PhP variables for jQuery script #
##############################################
-->

<?php
# Can modify later with real values
$archivedlibs = scandir("$subdirectories/mapcount_output");
$archivedlibs = preg_replace(array("/(.*)library_/","/\./","/\.\./"), "", $archivedlibs);

$toRecrunch = $_GET['libfilename'];
$toRecrunchII = $_GET['submitRecrunch'];

//echo json_encode($toRecrunch) . " is the value to recrunch";
//echo json_encode($toRecrunchII) . " is the value to recrunch";


//echo "return reloader(recrunchForm)";

//echo json_encode($toRecrunch) . " is the value to recrunch";

$pathToAnalysis = $mapcountpath = "$subdirectories/mapcount_output/".$_SESSION['user_name'];
$fastqPath = "$subdirectories/uploads/".$_SESSION['user_name'];
$fastqTempPath = "$subdirectories/temp_output/".$_SESSION['user_name'];

foreach($toRecrunch as $library)
{
  
  $libPath = $pathToAnalysis . "/" . $library;
  $libPattern = "/\D*(.*)/";
  preg_match($libpattern, $library, $matches);
  $librarynum = $matches[1];
  print $librarynum . " is library number";
  $moveFastqCommand = "$fastqTempPath/$librarynum*.gz $fastqPath";
  $removeAnalysisCommand = "rm -r  $libPath";
  
  echo "$moveFastqCommand is the command to move fastq file";
  echo "$removeAnalysisCommand is the command to remove the analysis";
  
}
unset($toRecrunch);

?>

<!--
##########################
# Archive Checkbox Adder #
##########################
-->

<script>

//reloader
function reloader()
{
        /* Reload window */
        parent.location.reload();
        /* Set iFrame to empty */
        window.location.assign("about:blank");

}


$(document).ready(function(){
	var libs = <?php echo json_encode($archivedlibs); ?>;


	$("#ctrlchk").click(function()
	{
		var inp = $("#ctrltxt");
		if(inp.val().length > 0) {
			var library = inp.val();
			if(jQuery.inArray(library, libs) != -1)
			{
				var $ctrl = $('<input/>').attr({ type: 'checkbox', name:'controlfilename[]', value: 'library_'+library}).addClass("ctrlchk");
				$("#ctrlholder").append($ctrl);
				$("#ctrlholder").append(library);
				$("#ctrlholder").append("<br>");
				libs = jQuery.grep(libs, function(value)
				{
  					return value != library;
				});
			}
			else {
				alert("Library \'" + library + "\' is not in the archive or already added as a separate condition.");
			}
		}
		else
		{
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
				libs = jQuery.grep(libs, function(value)
				{
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
  var token = <?php echo(json_encode($token)); ?>;
   //alert(token + "is the token");
   var user = <?php echo(json_encode($user)); ?>; 
   var url = "https://geco.iplantc.org/coge/api/v1/genomes/search/" + search_term + "?username=" + user + "&token=" + token;
   var BigTen =<?php
   //echo json_encode(scandir("/opt/apache2/frankdec/subdirectories/genome_directory/$user/big_ten/"))
   echo json_encode(scandir("/storage2/allenhub/subdirectories/genome_directory/$user/big_ten/"))
   ///opt/apache2/frankdec/subdirectories/genome_directory/big_ten/
   ?>; 

   //alert(BigTen + "is big ten");
  var token = <?php echo(json_encode($token)); ?>;
  var user = <?php echo(json_encode($user)); ?>; 
  var url =  "https://genomevolution.org/coge/api/v1/genomes/search/" + search_term + "?username=" + user + "&token=" + token;
  
  
  //determine if this is their first time logging into the system.
  //We will test for if they have already tried to query CoGE
  var HaveSearched = 0;
  
  for (var i = 0; i < BigTen.length; ++i)
  {
   	BigTen[i] = BigTen[i] + " fRNAk approved";
  }
           $.getJSON(url, function(data) {
                if (data.genomes) {
			HaveSearched = 1;
                        var genomes = [];
                        for (var i = 0; i < data.genomes.length; ++i){
				genomes.push(data.genomes[i].organism.name + " [ID: " + data.genomes[i].id+ "]" + "[chr count in annotation: " + data.genomes[i].chromosome_count + "]");
                        }
                        //add the genomes in the big ten to the list of genomes for the user!!
                        var totGenomes = genomes.concat(BigTen);
			var listApprovedGenomes = [];
			 for (var i = 0; i < totGenomes.length; ++i)
                         {
				var isThere = totGenomes[i].search("fRNAk approved"); 
                                
				if(isThere != -1)
                               	{
					approvedLocation = i;
					listApprovedGenomes.unshift(totGenomes[i]);
                        	}
                         }

			for (var i = 0; i < listApprovedGenomes.length; i++)
                        {
				totGenomes.unshift(listApprovedGenomes[i]);
			}
                        
                        $("#genomes").autocomplete({source: totGenomes});
			$("#genomes").autocomplete("search");
                }
    });

    
  //alert(search_term.length + "is the length of search_term");
  //alert(HaveSearched + "is HaveSearched");
   //if ((search_term.length > 4) & (HaveSearched == 0) )
    //{
      //alert("This is probably one of your first times using fRNAkenseq.  In order to be authenticated into the system to access iPlant genomes, please log out of fRNAkenseq and visit https://genomevolution.org/coge/.  Log in to this website with your iPlant account, then log out.  Then log back in to fRNAkenseq, in order to have access to iPlant genomes.  This is a one-time activation process.");
    //} 
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
	document.getElementById("submitform").submit();

}

$(document).ready(function(){	
  //opens up the document
  $(function() {
    $("#dialog-confirm").dialog({
      autoOpen: false
    });	
  $('[name = dialogbutton]').on("click", function() {
    $("#dialog-confirm").dialog("open");
      });
  });


  $('[name = submitRecrunch]').on("click", function() {
      alert("well, this is working!!!");
      
      if ($("[name = submitRecrunch]").val() == "submit") {
          alert("This will undo your mapcount and let you re-analyze with another genome. This could be time consuming. Please click confirm or unselect the libraries");
          $("[name = submitRecrunch]").val("confirm");
          return false;
      }
  
  });

  
});


$('[name = submitRecrunch]').on("click", function() {
    alert("well, this is working!!!");
    alert("input[type='submit']");
    
    if ($("input[type='submit']").val() == "submit") {
        alert("This will undo your mapcount and let you re-analyze with another genome. This could be time consuming. Please click confirm or unselect the libraries");
        $("input[type='submit']").val("confirm");
        return false;
    }
        
});


$('[name = submitRecrunch]').on('click', function() {
    $.ajax({
        url : 'move_to_recrunch.php'
    }).done(function(data) {
        console.log(data);
    });
});

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
                //alert(k + "is the key");
                //alert(v + "is the value !!");
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
<div class="help" id="help8" style="" title="Re-crunch Mapcount">
<font size="3"><center>Choose Libraries that you would like to re-crunch with mapcount.  This will remove the current files associated with a given run of mapcount and allow you to re-analyze the library.  This can be useful if you have a newer annotation or made a mistake with the mapcount selection.  It can be time consuming, though, to re-crunch libraries. </center></font>
</div>




<script language="JavaScript">

//jquery to show the user what the annotation was that their library was crunched with
$( document ).ready(
function() {

        $(document).mousemove(function (e) {
                $( ".frnakcheckboxAnno" ).dialog("option", "position", {
                        my: "left+30 top+30-$(document).scrollTop()",
                        at: "left top",
                        of: e
                });
        });
        $('.frnakcheckboxAnno').each(function(k,v){ // Go through all Divs with .box class
              //alert(k + "is key for the frnakcheckbox Anno");
              //alert(v + "is value for the frnakcheckbox Anno");
              
                
                var help = $(this).dialog({ autoOpen: false });
                $(this).parent().find('.ui-dialog-titlebar-close').hide();

                $( "#frnakcheckboxAnno"+k ).mouseover(function() { // k = key from the each loop
                        help.dialog( "open" );
                }).mouseout(function() {
                        help.dialog( "close" );
                });
            
          //alert("this will give the user the annotation that was used to crunch their library...for now it is just a placeholder");
        });
        
        

        

});


//do the same for the experimental checkboxes
$( document ).ready(
function() {

        $(document).mousemove(function (e) {
                $( ".frnakcheckboxAnnoExp" ).dialog("option", "position", {
                        my: "left+30 top+30-$(document).scrollTop()",
                        at: "left top",
                        of: e
                });
        });
        $('.frnakcheckboxAnnoExp').each(function(k,v){ // Go through all Divs with .box class
              //alert(k + "is key for the frnakcheckbox Anno");
              //alert(v + "is value for the frnakcheckbox Anno");
              
                
                var help = $(this).dialog({ autoOpen: false });
                $(this).parent().find('.ui-dialog-titlebar-close').hide();

                $( "#frnakcheckboxAnnoExp"+k ).mouseover(function() { // k = key from the each loop
                        help.dialog( "open" );
                }).mouseout(function() {
                        help.dialog( "close" );
                });
            
          //alert("this will give the user the annotation that was used to crunch their library...for now it is just a placeholder");
        });
});

</script>

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

if( $_SESSION['user_name'] != "fRNAktest")
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
//$controllibs = scandir("$subdirectories/mapcount_output/" . $_SESSION['user_name']);
$controllibs = $filesFromDataStore;

echo $dispersion;
# Sorts files by "natural human sorting" such that:
# 1.ext                       1.ext
# 10.ext     ==becomes==>     2.ext
# 2.ext                       10.ext 
if(!empty($controllibs))
{
  natsort($controllibs);
}


//grep to find the annotations that the user used
$whereToGrep =  $subdirectories . "/old_bash_scripts/" . $_SESSION['user_name'];
$whereToPutGrep = $subdirectories . "/old_bash_scripts/" . $_SESSION['user_name'] ."/output_of_grep.txt";
$listOfLibs = exec("grep \"cufflinks * -\"" . " " . $whereToGrep . "/* >" . $whereToPutGrep);
//echo "$listOfLibs";
//echo "$whereToGrep";
//echo "grep \"cufflinks * -\"" . " " . $whereToGrep . "/* >" . $whereToPutGrep;

//grep also from the directory holding bash scripts still running
//to get the information of libraries even if their run
//hasn't complete
$whereToGrepII =  $subdirectories . "/bash_scripts/" . $_SESSION['user_name'];
$whereToPutGrepII = $subdirectories . "/old_bash_scripts/" . $_SESSION['user_name'] ."/output_of_grepII.txt";

$listOfLibsII = exec("grep \"cufflinks * -\"" . " " . $whereToGrepII . "/* >" . $whereToPutGrepII);

//combine the output of the two greps files
$catFiles = $subdirectories . "/old_bash_scripts/" . $_SESSION['user_name'] ."/cat_grep_files.txt";
exec("cat $whereToPutGrep $whereToPutGrepII > $catFiles");
#echo "$whereToPutGrep $whereToPutGrepII > $catFiles";
$lines = file($catFiles);
$arrayOfLibsAndGenome = array();
foreach ($lines as $line_num => $line) {
//	echo $line;
    ##get the library and the 
    //echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
    //echo $line . "is the line";
    //$libPattern = "/.*(cufflinks.*24).*\-g.*\/allenhub\/(.*)\/\*.gff.*library_([A-Z0-9]+).*/";
    //$libPattern = "/.*(cufflinks.*24).*\-g.*\/" . $_SESSION['user_name'] . "\/(.*)\/\*.gff.*library_([A-Z0-9]+)\/cufflinks_out.*/";
    $libPattern = "/.*\/genome_directory\/(.*)\*.gff.*\/" . $_SESSION['user_name'] . "\/(library_.*).*\/cufflinks_out.*/";
    preg_match($libPattern, $line, $matches);
   // echo $matches[2] . "is the genome \n";
  //  echo $matches[3] . "is the lib number\n";
 //   echo $line . "is thel line !!";
    
    $arrayToAdd = array();
    $genome = $matches[1];
    $library = $matches[2];
    
    //echo $genome . "is the genome !!";
    //echo $library . "is the library !!";
    
    //echo $genome . "is the genome !!";
    //echo $library . "is the library !!";
    
    if(is_numeric($library) == TRUE)
    {
      $library = $library;
    
    }else{
      //echo $library . " is the library and is apparently not a number !!";
      
    }
    //$arrayToAdd = ("$library" => "$genome");
    //echo "hello";
    $libNumber = strval($library);
    $arrayOfLibsAndGenome =  array_merge($arrayOfLibsAndGenome,array($libNumber => "$genome"));
}

//print_r($arrayOfLibsAndGenome);

$outputwindowcount = 0;

echo "<b>Choose control library number(s):</b><span class=\"helper\" id=\"help1\" style=\"color:blue;\"><b>?</b></span><br><br>";
//echo '<span class=\"frnakcheckboxHelper\"id=\"frnakcheckboxAnno0\" style=\"color:blue;\"><b>?</b></span><br><br>';
//echo "<b>Choose control library number(s):</b><span class=\"frnakcheckboxHelper\" id=\"frnakcheckboxAnno0\" style=\"color:blue;\"><b>?</b></span><br><br>";

echo "<div id=\"\" style=\"overflow:auto; min-height:40; max-height:200px; width:200px;  display: inline-block; position: relative;\">";
if(count($controllibs)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No libraries ready to crunch!<br>";
} else {
  echo "<br>";

  $countControlLibs = 0;
  foreach($controllibs as $library)
  {
    if ($library !== "." and $library !== "..")
    {
      $librarynum = "";
      $librarynum = $library;
      //$libpattern = "/\D*(.*)/";
      //preg_match($libpattern, $library, $matches);
      //$librarynum = $matches[1];
      //if(strlen($librarynum) == 0)
      //{
       // $librarynum = $library;
        
      //}
      $libpattern = "/\D*(.*)/";
      preg_match($libpattern, $library, $matches);
      $librarynum = $matches[1];
      //echo $matches[1] . "is the match that will be the";
      $librarynumToDisplay = $library;      
      
      //if(strlen($librarynum == 0))
      //{
        //$librarynumToDisplay = $library; 
      //}
      //else
      //{
        //$librarynumToDisplay = "library_" . $library;    
      //}

      //echo $library . "is the library!!";
      //echo $librarynum . "is the library num";
      echo '<div class="frnakcheckbox">';
      echo "<input type=\"checkbox\" id=\"control".$librarynum."\" name=\"controlfilename[]\" class=\"blockedctrl\" value=\"$library\">";
      echo '<label for="control'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.'</div>';
      echo "<span class=\"frnakcheckboxHelper\" id=\"frnakcheckboxAnno" . $countControlLibs . "\" style=\"color:white;\"><b>???????????????</b></span><br>";
      //echo  $countControlLibs . "is the countControlLibs !!\n";
      //$libraryname = "library_" . "$librarynum";
      //echo "$libraryname is the key used to find the genome that analyzed this library!!"; 
      echo "<div class=\"frnakcheckboxAnno\" id=\"frnakcheckboxAnno". $countControlLibs ."\" style=\"\" title=\"Annotation Used\">";
      //echo "<font size=\"3\"><center>" . $countControlLibs . "this is a test to see if we can make dialog boxes showing the annotation associated with a given library </center></font>";
      //echo $librarynum . "is the library number !!";
      //if(is_numeric($librarynum) == TRUE)
      //{
        //$libraryname = "library_" . "$librarynum";
        //echo "well, this is a number !!";
      //}else
      //{
        //echo "well, this is not a number !!" . $libraryname;
        //$libraryname = $librarynum;
        
      //}
      //echo $libraryname . "is the library name!!";
      echo "This library was analyzed with genome $arrayOfLibsAndGenome[$librarynumToDisplay]"; 
      echo "</div>";
      
      
      $countControlLibs += 1;
    }
    $outputwindowcount += 1;
    
  } 
  echo "</select></div>";
}  


?>

</div>
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
//$explibs = scandir("$subdirectories/mapcount_output/". $_SESSION['user_name']);
$explibs = $filesFromDataStore;

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
        $countExpLibs = 0;
	foreach($explibs as $explibrary)
	{
          //echo json_encode($explibs) . "is the experimental libraries\n";
          //echo json_encode($explibrary) . "is the experimental library, single unit \n";
          $librarynumToDisplay = "";
	  if ($explibrary !== "." and $explibrary !== "..")
	  { 
	    $librarynum = "";
            //echo "we are in the for loop and $library is the library";
            //echo "we are in the for loop and $explibrary is the explibs";
	    $libpattern = "/\D*(.*)/";
            preg_match($libpattern, $explibrary, $matches);
	    $librarynum = $matches[1];
            //echo $matches[1] . "is the match that will be the";
            $librarynumToDisplay = $explibrary; 
            
           // if(strlen($librarynum == 0))
            //{
              //$librarynumToDisplay = $explibrary; 
            //}
            
            $librarynumToDisplay . " is the library number to display !!";
	    
	    echo '<div class="frnakcheckbox">';
	    echo "<input type=\"checkbox\" id=\"exp".$librarynum."\" name=\"expfilename[]\" class=\"blockedexp\" value=\"$explibrary\">";
	    echo '<label for="exp'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.'</div>';
            echo "<span class=\"frnakcheckboxHelperExp\" id=\"frnakcheckboxAnnoExp" . $countExpLibs . "\" style=\"color:white;\"><b>????????????????</b></span>";
            //$libraryname = "library_" . "$librarynum";
            echo "<div class=\"frnakcheckboxAnnoExp\" id=\"frnakcheckboxAnnoExp". $countExpLibs ."\" style=\"\" title=\"Annotation\">";
            //echo "<font size=\"3\"><center>" . $countExpLibs . "this is a test to see if we can make dialog boxes showing the annotation associated with a given library </center></font>";
            //$libraryname = "$librarynum" . "_library" ;
            // if(is_numeric($librarynum) == TRUE)
            //{
              //$libraryname = "library_" . "$librarynum";
            //}
          
            $libraryname = $librarynum;
            echo "This library was analyzed with genome $arrayOfLibsAndGenome[$librarynumToDisplay]"; 
            echo "</div>";
            $countExpLibs += 1;
          }
	}

	echo "</select></div>";
}
?>

</div>

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


<span><b>Choose a Genome: </b></span><span class="helper" id="help2" style="color:blue;"><b>?</b></span><br><br>
<span class="ui-widget">
<input name='genome' id="genomes" type="search" placeholder="Search Genomes..." spellcheck="false" onclick='$(this).autocomplete("search");' onkeypress="wait_to_search(search_genomes, this);" style="width: 200px;">
</span>
<br>
<span><b>*First time users, log out of fRNAkenseq and log in to https://genomevolution.org/coge/ To Activate Autocomplete </b></span><br><br>
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

<?php
require_once('recaptchalib.php');
$publickey = "6LfK0PUSAAAAANftfso7uj8OdyarzxH0zvst0Tmf"; 
#echo "Finally... Prove you're not a robot!";
#echo recaptcha_get_html($publickey);
?>

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
<div id= "button-container">
	<input type = "submit" id="button" value = "fRNAk Crunch!" style="font-weight:bold" class= "yellow">
	</form>
</div>


<div id="button-container">
	<form action="menu.php">
	<input type = "submit" id="button" value = "Menu" style="font-weight:bold" class="green">
	</form>
</div>
<!--
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span class="helper" id="help8" style="color:blue;"><b>?</b></span>
<div id="button-container">
	<input type = "button" id="button" name = "dialogbutton" value="Libraries to Recrunch" class="blue">
</div>
-->

<?php

echo '<table>';
//echo '<input type="button" id="button" value="Libraries to Recrunch" />';
echo '<div id="dialog-confirm" value = "text 1">';
echo "<form id ='recrunchForm' action='diffexpress.php' >";

    //echo "<form id ='form' action='recrunch_mapcount_response.php' >";
    //$libs = scandir("$subdirectories/mapcount_output/". $_SESSION['user_name']);
$libs = scandir("$subdirectories/mapcount_output/");
$colCount = 0;
echo "<input type='submit' id='submitRecrunch' name = 'submitRecrunch' value = 'submit'  action = diffexpress.php class = dialogSubmitButton  > ";
foreach($libs as $library)
{   
    if ($library !== "." and $library !== "..")
    {
        $librarynum = "";
        $libpattern = "/\D*(.*)/";
        preg_match($libpattern, $library, $matches);
        $librarynum = $matches[1];
        if($colCount == 10)
        {
            //echo '<td>';    
        }
        echo '<div class="frnakcheckbox">';
        echo "<input type=\"checkbox\" id=\"recrunch".$librarynum."\" name=\"libfilename[]\" class=\"blockedctrl\" value=\"$library\">";
        echo '<label for="recrunch'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.'</div><br>';
    }
}
echo '</form>';
echo '<p><tt id="results"></tt></p>';
echo '<div id="dialog-confirm"></div>';
echo '</table>';
?>

</td>

<!--
#######################
# Footer and clean-up #
#######################
-->

</tr>
</table>
</body>
<!--
<style>
.dialogSubmitButton{
width:30%;
border: 1px solid #59b4d4;
background: #0078a3;
color: #eeeeee;
padding: 3px 0px;
border-radius: 5px;
margin-left: 33%;
cursor:pointer;
}
-->
</style>
