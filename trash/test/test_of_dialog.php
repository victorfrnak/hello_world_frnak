<head>

	<title>jQuery Dialog Form Example</title>
	 
	 <!-- including css file here-->
    <link rel="stylesheet" href="css/dialog.css"/>
	
	 <!-- including css & jQuery Dialog UI here-->
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/dialog.js"></script>
		
</head>

<title>
fRNAkenstein - MapCount Cruncher
</title>
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<link rel="icon" type="image/ico" href="images/favicon.ico"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
</head>


<body>
	<div class="container">
	   <div class="main">
	      <div id="dialog" title="Dialog Form">
		<form id="submitform" action="just_advanced_option_box_response.php" method="post" target="formresponse">
			<div class="help" id="help1" style="" title="readGapLength">
				<font size="3"><center>Final read alignments having more than these many total length of gaps are discarded. The default is 2..</center></font>
				</div>
				
				<div class="help" id="help2" style="" title="readEditDistance">
				<font size="3"><center>Final read alignments having more than these many edit distance are discarded. The default is 2.</center></font>
				</div>
				
				<div class="help" id="help3" style="" title="readAlignEdit">
				<font size="3"><center>Some of the reads spanning multiple exons may be mapped incorrectly as a contiguous alignment to the genome even though the correct alignment should be a spliced one - this can happen in the presence of processed pseudogenes that are rarely (if at all) transcribed or expressed. This option can direct TopHat to re-align reads for which the edit distance of an alignment obtained in a previous mapping step is above or equal to this option value. If you set this option to 0, TopHat will map every read in all the mapping steps (transcriptome if you provided gene annotations, genome, and finally splice variants detected by TopHat), reporting the best possible alignment found in any of these mapping steps. This may greatly increase the mapping accuracy at the expense of an increase in running time. The default value for this option is set such that TopHat will not try to realign reads already mapped in earlier steps.</center></font>
				</div>
				
				<div class="help" id="help4" style="" title="readAlignEdit">
				<font size="3"><center>This is the expected (mean) inner distance between mate pairs. For, example, for paired end runs with fragments selected at 300bp, where each end is 50bp, you should set -r to be 200. The default is 50bp.</center></font>
				</div>
				
				<div class="help" id="help6" style="" title="readAlignEdit">
				<font size="3"><center>The standard deviation for the distribution on inner distances between mate pairs. The default is 20bp.</center></font>
				</div>
				
				<div class="help" id="help7" style="" title="readAlignEdit">
				<font size="3"><center>The "anchor length". TopHat will report junctions spanned by reads with at least this many bases on each side of the junction. Note that individual spliced alignments may span a junction with fewer than this many bases on one side. However, every junction involved in spliced alignments is supported by at least one read with this many bases on each side.	This must be at least 3 and the default is 8./center></font>
				</div>
				
				<div class="help" id="help8" style="" title="readAlignEdit">
				<font size="3"><center>The maximum number of mismatches that may appear in the "anchor" region of a spliced alignment. The default is 0.</center></font>
				</div>
				
				<div class="help" id="help9" style="" title="readAlignEdit">
				<font size="3"><center>The minimum intron length. TopHat will ignore donor/acceptor pairs closer than this many bases apart. The default is 70.</center></font>
				</div>
				
				<div class="help" id="help10" style="" title="readAlignEdit">
				<font size="3"><center>The maximum intron length. When searching for junctions ab initio, TopHat will ignore donor/acceptor pairs farther than this many bases apart, except when such a pair is supported by a split segment alignment of a long read. The default is 500000.</center></font>
				</div>
				
				<div class="help" id="help11" style="" title="readAlignEdit">
				<font size="3"><center>The maximum insertion length. The default is 3./center></font>
				</div>
				
				<div class="help" id="help12" style="" title="readAlignEdit">
				<font size="3"><center>The maximum deletion length. The default is 3.</center></font>
				</div>
				
				<div class="help" id="help13" style="" title="Citations">
				<font size="3"><center>This will link to the Tuxedo pipeline manual for the tophat algorithm</center></font>
				</div>
				
				<!-- the entry spaces !-->
				
				<label>read gap length 1</label><span class="helper" id="help1" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="readGapLength" name="readGapLength"><br/>
				
				<label>read-edit-distance</label><span class="helper" id="help2" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="readEditDistance" name="readEditDistance"><br/>
				
				<label>read-align-edit</label><span class="helper" id="help3" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="readAlignEdit" name="readAlignEdit"><br/>
				
				<label>expected distance between pairs</label><span class="helper" id="help4" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="expectedDistancePairs" name="expectedDistancePairs"><br/>
				
				<label>mate standard deviation</label><span class="helper" id="help5" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="mateStdDev" name="mateStdDev"><br/>
		    
				<label>minimum number length</label><span class="helper" id="help6" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="minNumLen" name="minNumLen"><br/>
				
				<label>minimum anchor length</label><span class="helper" id="help7" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="minAnchorLength" name="minAnchorLength"><br/>
				
				<label>splice-mismatched</label><span class="helper" id="help8" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="spliceMismatch" name="spliceMismatch"><br/>
	    
				<label>min-intron length</label><span class="helper" id="help9" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="minIntronLen" name="minIntronLen"><br/>
				
				<label>max-intron length</label><span class="helper" id="help10" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="maxIntronLen" name="maxIntronLen"><br/>
				
				<label>max insertion length!!</label><span class="helper" id="help11" style="color:blue;"><b>?</b></span><br><br>
				<input type="text" id="maxInsertLen" name="maxInsertLen"><br/>
					
				<input type="submit" id="submit" value="Submit this" /></br>
				<div class='container'>
				<button id='crunch' class='crunch' type="submit">fRNAkenstein, Crunch!</button>
				</div>
			  </form>
				
		  </div>
		  <h2>jQuery Dialog Form Example</h2><hr/>
		  <p>Click below button to see jQuery dialog form.</p>
		  <input type="button" id="button" value="Open Dialog Form" />
	  </div>
	
	<!-- Div Fugo is advertisement div-->
	  <div class="fugo">
		<a href="http://www.formget.com/app/"><img src="images/formGetadv-1.jpg" /></a>
	  </div>		
	</div>		
</body>
<!--
#######################
# iFrame for Response #
#######################
-->

<td valign="top" style="padding-left:0px;">
<iframe name='formresponse' src='placeholder_response.html' style="border: none; background-color:#d0eace" width='400px' height='550' frameborder='0'>
</iframe>



</html>






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

<script>
$(document).ready(function(){
	
	$(function() {
		$("#dialog").dialog({
			autoOpen: false
		});
		$("#button").on("click", function() {
			$("#dialog").dialog("open");
		});
	});

	//validating Form Fields.....
	$("#submit").click(function(e){

	var readGapLength = $("#readGapLength").val();
	var readEditDistance = $("#readEditDistance").val();
	var readAlignEdit = $("#readAlignEdit").val();
	var expectedDistancePairs = $("#expectedDistancePairs").val();
	var mateStdDev = $("#mateStdDev").val();
	var minNumLen = $("#minNumLen").val();
	var minAnchorLength = $("#minAnchorLength").val();
	var spliceMismatch = $("#spliceMismatch").val();
	var minIntronLen = $("#minIntronLen").val();
	var maxIntronLen = $("#maxIntronLen").val();
	var maxInsertLen = $("#maxInsertLen").val();
	//var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if( email ==='' || name ==='')
       {
		 alert("Please fill all fields...!!!!!!");
		 e.preventDefault();
       }
    else if(!(email).match(emailReg))
       {
         alert("Invalid Email...!!!!!!");
		 e.preventDefault();
       }    
	else 
	   {
         alert("Form Submitted Successfully......");
	   }
	
	});
		
});



$(document).ready(function(){	
	//opens up the document
	$(function() {
		$("#dialog").dialog({
			autoOpen: false
		});
		
		$("#button").on("click", function() {
			$("#dialog").dialog("open");
		});
		
		$("#citation").on("click", function() {
			$("#help1").dialog("open");
		});
		
	});
});


</script>

<?php
$taskid = "";
echo "<input name='opendialog1' type='text' class='opendialog' onclick='countChecked()' value=".$taskid." ?>";
echo "<input type=\"submit\" id=\"submit\" value=\"Submit this\" /></br>";
echo "<div class=\"container\">";
echo "<button id=\"crunch\" class=\"crunch\" type=\"submit\">fRNAkenstein, Crunch!</button>";
echo "</div>";

?>
<div class='container'>
<button id='crunch' class='crunch' type="submit">fRNAkenstein, Crunch!</button>
</div>
<script language="JavaScript">

function countChecked() {
  var n = $("input:checked").length;

  var allVals = [];
   $('input:checkbox:checked').each(function() {
   allVals.push($(this).val());

   });
   $('.sel').text(allVals+' ');
   $('.select1').val(allVals);
   alert(allVals);

    <?php $taskidj=$rowtask['taskID'];
   // echo "aaa...".$rowtask['taskID']; ?>     

}

$(":checkbox").click(countChecked);


// my jquery code


        $('.mydialog').dialog({

            bgiframe: true,
            autoOpen: false,
            modal: true,
            width: 700,
            height:500,
            resizable: false,
            open: function(){closedialog = 1;$(document).bind('click', overlayclickclose);},
            focus: function(){closedialog = 0;},
            close: function(){$(document).unbind('click');},
            buttons: {
                Submit: function(){
                var bValid = true;
            //  allFields.removeClass( "ui-state-error" );

            //  bValid = bValid && checkLength( name, "username", 3, 16 );



            //  bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );





                if ( bValid ) {

                        processDetails();

                        return false;

                }


                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                    $('input[name=opendialog]').attr('checked', false);
                }
            }
        });

    $('.opendialog').click(function() {
            $('.mydialog').dialog('open');
            closedialog = 0;
        });


</script>

<script>
//the added jquery code to get the response!!!
$("input[type=text]").click(function () {
   $.post('just_advanced_option_box_response.php', 'val=' + $(this).val(), function (response) {
      //alert(response);
   });
});
</script>
