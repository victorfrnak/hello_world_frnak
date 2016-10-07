
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<!--
this is the stylesheet link which was originally used
-->
<link rel="STYLESHEET" type="text/css" href="css_dir/style.css">
<script>
//val this form
function valthisform()
{
        /*
        function fnOpenNormalDialog() {
        $("#submit").html("These libraries will have their mapcount information deleted if you click yes.  You will be able to reanalyze the library with another fasta and annotation.  But, keep in mind that this will take up to 2 to 4 hours for each library");

        // Define the Dialog and its properties.
        $("#dialog-confirm").dialog({
            resizable: false,
            modal: true,
            title: "Modal",
            height: 250,
            width: 400,
            buttons: {
            "Yes": function ()
                {
                    $(this).dialog('close');
                    callback(true);
                },
                "No": function ()
                {
                    $(this).dialog('close');
                    callback(false);
                }
            }
        });
    }

    $('#btnOpenDialog').click(fnOpenNormalDialog);

    function callback(value) {
        if (value) {
            alert("Confirmed");
            return okay;
        } else
        {
            alert("Rejected");
            location.reload();
        }
    }	
*/
//return okay;
}
    
    
</script>

<?php
$ini = parse_ini_file("../config.ini.php", true);
$def_path = $ini['login']['default'];
$subdirectories = $ini['filepaths']['subdirectories'];
session_start();
?>

<?php
echo '<table>';
echo '<input type="button" id="button" value="Libraries to Recrunch" />';
echo '<div id="dialog-confirm">';
echo "<form id ='form' onsubmit=\"return valthisform(this);\" action='recrunch_mapcount_response.php' >";
    //echo "<form id ='form' action='recrunch_mapcount_response.php' >";
    //$libs = scandir("$subdirectories/mapcount_output/". $_SESSION['user_name']);
$libs = scandir("$subdirectories/mapcount_output/");
$colCount = 0;
echo "<input type='submit' id='submit' value = 'submit'/>";
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
        echo "<input type=\"checkbox\" id=\"control".$librarynum."\" name=\"libfilename[]\" class=\"blockedctrl\" value=\"$library\">";
        echo '<label for="control'.$librarynum.'"></label></div><div class="checklabel">'.$librarynum.'</div><br>';
        
        if($colCount == 10)
        {
            //echo '</td>';
            $colCount = 0;
        }
    
    }
    
    $colCount += 1;
}
    echo "<input type='submit' id='submit' value = 'submit'/>";
    echo '</form>';
    echo '<p><tt id="results"></tt></p>';
    echo '<div id="dialog-confirm"></div>';
    echo '</table>';
?>
 
    
    
   




<script>






$(document).ready(function(){	
    function showValues() {
        var str = $( "#form" ).serialize();
        $( "#results" ).text( str );
        alert( str );
      }
      
});


//jquery script for the dialog box button
$(document).ready(function(){	
	//opens up the document
	$(function() {
		$("#dialog-confirm").dialog({
			autoOpen: false
		});
		
		$("#button").on("click", function() {
			$("#dialog-confirm").dialog("open");
		});
	});
        
       /* 
        $("#form").submit(function(e)
        {
	    showValues();
			  
	});
        */
        


});

$("#form").submit(function() {
    if ($("input[type='submit']").val() == "submit") {
        alert("This will delete your mapcount and let you re-analyze with another genome. This could be time consuming.  Make sure you have downloaded results from previous mapcount if you need record of them. Please click confirm or unselect the libraries");
        $("input[type='submit']").val("confirm");
        return false;
    }
});

</script>





<style>
input[type=submit]{
width:30%;
border: 1px solid #59b4d4;
background: #0078a3;
color: #eeeeee;
padding: 3px 0px;
border-radius: 5px;
margin-left: 33%;
cursor:pointer;
}
    
</style>

