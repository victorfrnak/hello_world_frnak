<?php

######################################
# fRNAkenstein                       #
#   by Allen Hubbard & Wayne Treible #
#                                    #
# Version 0.10 Updated 6/17/2014     #
######################################

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
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];
#echo $subdirectories . "is subdirectories !!!";
#echo "hello this message is working !!";
$key = $ini['login']['key'];
$secret = $ini['login']['secret'];

session_start();

$token = $_SESSION['access_token'];
//echo $token . "is token";
$time_out = $_SESSION['SESSION_TIMEOUT'];
$user = $_SESSION['user_name'];

#path for the file to see if the user has logged into CoGE
$checkForLogin = $subdirectories . "/mapcount_output/$user/have_logged_in.txt";


//Command for the listing of the data files from the data store
// ./files-list /schmidtc/Transcriptome_Delaware

if ( (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) || 
   (isset($_SESSION['SESSION_TIMEOUT']) && (time() - $_SESSION['SESSION_TIMEOUT'] > 5400)) ) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time      
    session_destroy();   // destroy session data in storage                 
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


if(empty($_SESSION['user_name']) && !($_SESSION['user_logged_in']))
{
  header('Location: '.$def_path);
}

$directoryUserGenome = "$subdirectories/genome_directory/" . $user; 
#echo "$directoryUserGenome ";
if (file_exists($directoryUserGenome) == FALSE) 
{
	#echo "we should be making their directory !!!";
	mkdir($directoryUserGenome);
	chmod($directoryUserGenome, 0777);
}

$directoryUserBash = "$subdirectories/bash_scripts/" . $user; 
#echo "$directoryUserBash ";
if (file_exists($directoryUserBash) == FALSE) 
{
        #echo "we should be making their directory !!!";
        mkdir($directoryUserBash);
        chmod($directoryUserBash, 0777);
}

$directoryUserOldBash = "$subdirectories/old_bash_scripts/" . $user; 
#echo "$directoryUserOldBash ";
if (file_exists($directoryUserOldBash) == FALSE) 
{
        //echo "we should be making their directory !!!";
        mkdir($directoryUserOldBash);
        chmod($directoryUserOldBash, 0777);
}

$dataStoreFiles = "/opt/apache2/frankdec_dev/users_libs/schmidtc/libsAvailable.txt";
exec("touch $dataStoreFiles");
exec("chmod 777 $dataStoreFiles");


###Command for the data store ###
//exec("bash /opt/apache2/frankdec_dev/iplantc-agave-cli-0cc5274b53c4/bin/tenants-init -t iplantc.org");

exec("bash /opt/apache2/frankdec_dev/iplantc-agave-cli-0cc5274b53c4/bin/tenants-init -d https://geco.iplantc.org/frnakenseq_dev/ -t 3");
//exec("bash /opt/apache2/frankdec_dev/iplantc-agave-cli-0cc5274b53c4/bin/files-list -z $token /schmidtc/Transcriptome_Delaware > $dataStoreFiles");
exec("bash /opt/apache2/frankdec_dev/iplantc-agave-cli-0cc5274b53c4/bin/files-list -z $token /schmidtc/Transcriptome_Delaware > $dataStoreFiles");

exec("curl -sk -H \"Authorization: Bearer $token\" https://agave.iplantc.org:443/files/v2/listings/schmidtc/Transcriptome_Delaware > $dataStoreFiles");

echo "hello";
echo "$token is the token \n \n";

echo "curl -sk -H \" Authorization: Bearer $token \" https://agave.iplantc.org:443/files/v2/listings/schmidtc/Transcriptome_Delaware > $dataStoreFiles";

//curl -sk -H "Authorization: Bearer ffb96af193e68b1066e21f5613bacf1" https://agave.iplantc.org:443/files/v2/listings/schmidtc/Transcriptome_Delaware



//bash /home/allenhub/iplantc-agave-cli-0cc5274b53c4/bin/files-list -z cf742c31750d84875b86dd86ccbd085 /schmidtc/Transcriptome_Delaware

echo "bash /opt/apache2/frankdec_dev/iplantc-agave-cli-0cc5274b53c4/bin/files-list -z $token /schmidtc/Transcriptome_Delaware > $dataStoreFiles is the command to be run \n \n";

echo $dataStoreFiles . " is the data store file !! \n \n";

$arrayOfLibsDataStore = array();


$dataFiles = file($dataStoreFiles);

var_dump(json_decode($dataFiles), true);
//echo $toEcho . " is to echo !! \n \n"; 
$arrayOfDataStoreLibs = array();

foreach ($dataFiles as $line_num => $line)
{
    //echo $line . " is the data file with preg all!! \n \n";
   var_dump(json_decode($line), true);
  echo " is after var dump \n \n";
    $libPattern = "*.gz";
    preg_match_all($libPattern, $line, $matches); 
    $arrayToAdd = array();
    $file = $matches[1];
    echo $matches[1] . " is the file !! \n \n";
    echo " hello !! \n \n";
    //$arrayToAdd = ("$library" => "$genome");
    //echo "hello";
    $libNumber = strval($file);
    
    $arrayOfLibsAndGenome =  array_merge($arrayOfLibsAndGenome,$libNumber);
}

foreach($arrayOfLibsAndGenome as $fromDataStore)
{
	echo $arrayOfLibsAndGenome . "is from the data store \n \n";
}
//test for the big ten and copy over if it doesn't exit
//$directoryBigTen = '/opt/apache2/frankdec/subdirectories/genome_directory/' . $user . '/big_ten/'; 
//if (file_exists($directoryBigTen) == FALSE) 
//{
  //      exec("cp -r /opt/apache2/frankdec/subdirectories/genome_directory/big_ten/ /opt/apache2/frankdec/subdirectories/genome_directory/$user/big_ten/");
    //    exec("chmod 777 /opt/apache2/frankdec/subdirectories/genome_directory/$user/big_ten/*/*");
//}

?>
<head>
<title>
fRNAkenstein - MapCount Cruncher
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">
</head>

<!--#jquery section-->
<!--
#########################
# Help Dialog Box Stuff #
#########################
-->
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
	$arr = $("#genomes");

	//have an alert that says if this is the first time logging in, they should go to CoGE
	
	$lengthArray = $arr.length;
	
	
	//alert($arr + "is the array from which we autocomplete");
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
	
		  
  var files =<?php
  echo json_encode(scandir("$subdirectories/uploads/$user"))
  ?>; 
      
  for (var index = 0; index < files.length; index++)
  {
  
    var file = files[index];
    if ((file != ".") & (file != ".."))
    {     
      //var numberOfFile = file.match(/[0-9]+/);
      var numberOfFile = file.match(/(^[0-9]+)/);
    
      //alert(file + "is the file");
      //alert(numberOfFile + "is the number of the file");
      
      if(numberOfFile == null)
      {
	//alert("okay first !!");
	alert("One of your libraries is not numbered.  This is going to be problematic because fRNAkenstein uses these numbers to keep track of individual mapcount runs.  Please re name and then reload the file with the file manager or risk having the analysis not show up in downstream steps");
      }
      
    }
    
  }

  var alreadyTemp =<?php
      echo json_encode(scandir("$subdirectories/temp_output/$user"))
      ?>; 
      
      var doneMapCount =<?php
      echo json_encode(scandir("$subdirectories/mapcount_output/$user"));
      ?>; 
     
      var numberAlreadyMapCount = []
      var number = 0
      
      //get the numbers of the libraries already crunched!!
      for (var index = 0; index < doneMapCount.length; index++)
      {
	if ((doneMapCount[index] != ".") & (doneMapCount[index] != ".."))
	{ 
	  number = doneMapCount[index].match(/([0-9]+)/);
	  //alert(number + "is the number");
	  //alert(doneMapCount[index] + "is the library");
	  numberAlreadyMapCount.push(number);
	}
      }
      
      //alert("hello 1");
      
      var AlreadyTempNumber = []
      //get the numbers of the libraries already in temp, but not crunched
      for (var index = 0; index < alreadyTemp.length; index++)
      {
  
	var file = alreadyTemp[index];
	if ((file != ".") & (file != ".."))
	{
	  //alert("testing the loop");
	  var numberOfFile = file.match(/(^[0-9]+)/);
	  //alert(numberOfFile + "is the number of the file");
	  AlreadyTempNumber.push(numberOfFile);
	}
    
      }
      
      //alert("hello 2");
      
      var DupCrunched = 0;
      var alreadyCrunched = numberAlreadyMapCount.concat(numberAlreadyMapCount);
      //alert("hello 2");
      //alert(alreadyCrunched + "is the libraries already crunched");
      
     // alert("hello 3");
      
      //see if one of the libraries are already loaded
      for (var index = 0; index < files.length; index++)
      {
      
	//alert("in the first loop.  hello + " + index);
	var file = files[index];
	//alert(file + "is the file");
	if ((file != ".") & (file != ".."))
	{     
	  //var numberOfFile = file.match(/[0-9]+/);
	  var numberOfFile = file.match(/(^[0-9]+)/);
	  //alert(numberOfFile + "is the number of the file");
	  for (var indexII = 0; indexII < alreadyCrunched.length; indexII++)
	  {
	    var crunched = alreadyCrunched[indexII];
	   // alert(crunched + "is a crunched library");
	    if ((crunched != ".") & (crunched != ".."))
	    {
	    
	      //alert(crunched + "is the crunched library!");
	      if ((numberOfFile == crunched)&((numberOfFile  != null) & (crunched != null)))
	      {
		alert(file + "an analysis with this name has already been run please use the file manager to remove this file and rename it");
	      }
	    
	    }
	  }
	}
      }

});

  //determine if this is their first time logging into the system.
  //We will test for if they have already tried to query CoGE
  var HaveSearched = 0;
  var HaveGoneIntoLoop  = 0;
  
function search_genomes(search_term) {
  
   var token = <?php echo(json_encode($token)); ?>;
   //alert(token + "is the token");
   var user = <?php echo(json_encode($user)); ?>; 
   //var url = "https://geco.iplantc.org/coge/api/v1/genomes/search/" + search_term + "?username=" + user + "&token=" + token;
   var url = "https://genomevolution.org/coge/api/v1/genomes/search/" + search_term + "?username=" + user + "&token=" + token;
   
   var BigTen =<?php
   echo json_encode(scandir("/storage2/allenhub/subdirectories/genome_directory/$user/big_ten/"));
   ?>;
 
   for (var i = 0; i < BigTen.length; ++i){
   	BigTen[i] = BigTen[i] + " fRNAk approved";
    }
	$.getJSON(url, function(data) {
		HaveGoneIntoLoop = 1;
		var totGenomes = [];	
		
		
		if (data.genomes.length) {
			
		      
		      
			HaveSearched = 1;
		      
			var genomes = [];
			for (var i = 0; i < data.genomes.length; ++i){
				genomes.push(data.genomes[i].organism.name + " [ID: " + data.genomes[i].id+ "]" + "[chr count in annotation: " + data.genomes[i].chromosome_count + "]");
			}
                       
			if(BigTen.length > 0){
			    totGenomes = genomes.concat(BigTen);
			    //alert(totGenomes + "is tot genomes");
                        
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
			}
                        else {
                            var totGenomes = genomes;
                        }
			$("#genomes").autocomplete({source: totGenomes});
			$("#genomes").autocomplete("search");
		}
      
    });
    
    //var logInFile = <?php echo file_exists("$checkForLogin"); ?>;
    //alert(logInFile);
    //alert(logInFile + "is the logInFile");
    
    //if ((search_term.length > 4) & (HaveSearched == 0) & (HaveGoneIntoLoop == 1))
    //{
      //alert("This is probably one of your first times using fRNAkenseq.  In order to be authenticated into the system to access iPlant genomes, please log out of fRNAkenseq and visit https://genomevolution.org/coge/.  Log in to this website with your iPlant account, then log out.  Then log back in to fRNAkenseq, in order to have access to iPlant genomes.  This is a one-time activation process.");
      //alert("Will be the length of totaGenomes !!");
      //alert(totGenomes.length + "is the length of totaGenomes");
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
        var checkboxs=document.getElementsByName("fqfilename[]");
        var okay=false;
        var zippped=true;
	var genome=false;

	//we will have separate arrays for the big checkboxes, representing selection of a library in general
	//and the small check boxes representing selection for strandedness
	var bigCheckboxs=document.getElementsByName("fqfilename[]");
        var littleCheckboxs = document.getElementsByName("stranded[]");
	//alert(bigCheckboxs.contents() + "is the array of checkboxes");        
        var arrayOfSmallCheckboxesSelected = [];
        var arrayOfBigCheckboxesSelected = [];
        

	for (var index = 0; index < bigCheckboxs.length; index++)
        {
          if(bigCheckboxs[index].checked)
          {
            arrayOfBigCheckboxesSelected.push(bigCheckboxs[index].value);
          }
        }

	//create array of the small checkboxes, signifying stranded libraries
	for (var index = 0; index < littleCheckboxs.length; index++)
        {
          if(littleCheckboxs[index].checked)
          {
          	arrayOfSmallCheckboxesSelected.push(littleCheckboxs[index].value); 
          }
          
        }

        for(var i=0,l=checkboxs.length;i<l;i++)
        {
	  alert(checkbox[i]);
          if(checkboxs[i].checked)
          {
	    okay=true;
	    //alert("hello");
          }
	  str = checkboxs[i];	
	  if(document.getElementById('genomes').value != "")
	  {
	    genome=true;
	  }
        //if(okay&&genome){
	  if(okay){
            document.getElementById('crunch').className = "disabled";
			document.getElementById('crunch').disabled = 1;
			alert("Running MapCount on Data!");
        }

      else if (!okay)
      {
	alert("Please select a library!");
      }
      else{
	alert("Please select a genome!");
	}
        return okay;
    }
}

</script>

<body>

<div class="help" id="help" style="" title="Library Numbers">
<font size="3"><center>Choose library numbers for mapping and assembly with FPKM and raw count quantification.</center></font>
</div>
<div class="help" id="help2" style="" title="Number of Processors">
<font size="3"><center>Choose number of proccessors for parallelization of tophat and cufflinks. (Recommended: 24)</center></font>
</div>
<div class="help" id="help3" style="" title="Genome">
<font size="3"><center>Select your organism from your available CoGe genomes. (Search will auto-complete)</center></font>
</div>

<center>




<!--
############################
# Beginning of submit form #
############################
-->
<?php
if($_SESSION['user_name'] != "fRNAktest")
{
	echo '<div>';
	echo '<form id="submitform" onsubmit="return valthisform(this);" action="mapcount_response.php" method="post" target="formresponse">';
	echo '<input type="hidden" name="submitted" id="submitted" value="1"/>';
}
else
{
        echo "<form method=\"post\">"; 
}
?>
<!--
################################
# Beginning of alignment table #
################################
-->

<table>
<th colspan="3" >
<a href="menu.php"><img src="images/frnak_banner.png" style="border:0;padding-right:25px;" alt="fRNAkenstein" width="600" ></a> <br><br>
</th>

<!--
#############################
# Row for form and response #
#############################
-->

<tr style="padding:0px; margin:0px;">
<td valign="top" style="padding-top:12px;padding-left:8px;padding-right:12px;width:270px">

<!--
################################################
# Create Checkboxes for fastq files (lib nums) #
################################################
-->

<div class='container'>

<?php
//echo "<script> checkFormat(\"/opt/apache2/frankdec/subdirectories/genome_directory/\"); </script>\n";


$fqfiles = scandir("$subdirectories/uploads" . "/" . $_SESSION['user_name']);
//$fqfiles = scandir("$subdirectories/fastq_directory");

# Sorts files by "natural human sorting" such that:
# 1.ext                       1.ext
# 10.ext     ==becomes==>     2.ext
# 2.ext                       10.ext 
if(!empty($fqfiles))
{
  natsort($fqfiles);
}

echo "<span><b>Choose library number(s): </b></span><span class=\"helper\" id=\"help0\" style=\"color:blue;\"><b>?</b></span><br><br>";
echo "<div id=\"\" style=\"overflow:auto; min-height: 40px; max-height:200px; width:250px;  display: inline-block; position: relative;\"><br>";
if(count($fqfiles)<3){ #because of . and .. directories existing
	echo "<b>Note:</b> No libraries ready to crunch!<br><br>";
} else {
	echo "";
	# else, list the files
	foreach($fqfiles as $fqfile)
	{
		# *** TO FIX ***
		# Modifying arrays while 'foreach' iterating is broken in php (reference)
		# -> It buffers the array at the foreach call and iterates over 
		# -> potentially old or modified data (bad, php!)
		# This double checks that the element is in the new fqfiles array 
		# to fix this minor annoying problem...
		# Edit: I was an idiot (didn't know &$var like I should have), but I guess this still works
		if (($key = array_search($fqfile, $fqfiles)) !== false) 
		{
			$doublestranded = 0;
			if ($fqfile !== "." and $fqfile !== "..")
			{ 
				$librarynum = "";
				$libpattern = "/^s*(\d*).*/";
				preg_match($libpattern, $fqfile, $matches);
				$librarynum = $matches[1];

				foreach ($fqfiles as $fqfile2)
				{
					if ($fqfile != "." and $fqfile != ".." and $fqfile2 != "." and $fqfile2 != "..")
					{ 
						$librarynum2 = "";
						$libpattern = "/^s*(\d*).*/";
						preg_match($libpattern, $fqfile2, $matches2);
						$librarynum2 = $matches2[1];
					
						if(($librarynum2 == $librarynum) and ($fqfile !== $fqfile2))
						{
							// Remove double stranded results from list
							if (($key = array_search($fqfile, $fqfiles)) !== false) 
							{
								$key2 = array_search($fqfile2, $fqfiles);
								unset($fqfiles[$key]);
								unset($fqfiles[$key2]);
							} 
							$doublestranded = 1;
							
							echo '<div class="frnakcheckbox">';
							echo "<input type=\"checkbox\" id=\"frnakcheckbox".$librarynum."\"  name=\"fqfilename[]\" value=\"$fqfile&$fqfile2\">";
							echo '<label for="frnakcheckbox'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.' (paired end)</div>';
							echo '<br><input type="checkbox"name="stranded[]" value = ' . "$fqfile&$fqfile2" .'> Check Box if ' . $librarynum2 . ' stranded<br><br>';
						} 
					}
				}

				if ($doublestranded == 0)
				{
					echo '<div class="frnakcheckbox">';
					echo "<input type=\"checkbox\" id=\"frnakcheckbox".$librarynum."\"  name=\"fqfilename[]\" value=\"$fqfile\">";
				        echo '<label for="frnakcheckbox'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.'</div><br>';
				}
			}
		}
	}
} 
echo "</select></div>";

?>

<!--
######################################
# Proc Selector Slider (JS onchange) #
######################################
-->
<br><br>
<span><b>Number of processors: </b></span><span class="helper" id="help1" style="color:blue;"><b>?</b></span><br><br>
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

<!--
################################
# Create DDBox for fasta files #
################################
-->

<?php

/*
$fafiles = scandir("$subdirectories/fasta_directory"); 

echo "<br><span><b>Choose a fasta: </b></span><span class=\"helper\" id=\"help2\" style=\"color:blue;\"><b>?</b></span><br><br>";
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

<div id="ctrlholder" style="padding-bottom:10px">

</div><br>

<span><b>Choose a Genome: </b></span><span class="helper" id="help2" style="color:blue;"><b>?</b></span><br><br>
<span class="ui-widget">
<input name='genome' id="genomes" type="search" placeholder="Search Genomes..." spellcheck="false" onclick='$(this).autocomplete("search");' onkeypress="wait_to_search(search_genomes, this);" style="width: 200px;">
</span>
<br>
<span><b>*First time users, log out of fRNAkenseq and log in to https://genomevolution.org/coge/ To Activate Autocomplete </b></span><br><br>
<br><br><br>
<!--
#####################################
# Create DDBox for annotation files #
#####################################
-->

<?php
/*
$afiles = scandir("$subdirectories/annotation_directory");
echo "<br><br><span><b>Choose an Annotation File: </b></span><span class=\"helper\" id=\"help3\" style=\"color:blue;\"><b>?</b></span><br><br>";

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
}
*/
?>

<!--
#######################
# Annotation Selector #
#######################
-->

<!--br><br>
<span><b>Annotation Type: </b></span><span class="helper" id="help4" style="color:blue;"><b>?</b></span><br>

<div class="frnakRadio">
<div class="checkname">NCBI</div>
<input type="radio" id="frnakRadioInput" name="annotationtype" value="ncbi" checked>
<label for="frnakRadioInput"></label></div>

<div class="frnakRadio">
<div class="checkname">Ensembl</div>
<input type="radio" id="frnakRadioInput2" name="annotationtype" value="ensembl" >
<label for="frnakRadioInput2"></label></div>

<br-->

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
#######################
# iFrame for Response #
#######################
-->

<td valign="top" style="padding-left:0px;">
<iframe name='formresponse' src='placeholder_response.html' style="border: none; background-color:#d0eace" width='400px' height='550' frameborder='0'>
</iframe>

<!--
#######################
# Footer and clean-up #
#######################
-->
<br><br>
<p align="right"><font size="1">- Created by Allen Hubbard and Wayne Treible at the University of Delaware - </font></p>

</td>
</tr>
</table>
</form>
</center>
</fieldset>
</body>

