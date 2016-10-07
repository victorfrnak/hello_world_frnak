 <!-- including css & jQuery Dialog UI here-->
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<script>
//the added jquery code to get the response
$(function() {



$('#testform').submit(function(e){
        var temp = "123";
        e.preventDefault();
	var form_data = $('#testform');
	var serial_advanced = "&val=" + temp;
	var serial_data = form_data.serialize() + serial_advanced;
	alert(serial_data);
	$.post('test_response.php', serial_data, function(data){
                $("#formresponse").contents().find('html').html(data);         
        });
    });

});



</script>


<form id="testform" action="test_response.php" target="formresponse">
<input type="hidden" value="test" name="test">
<input type="text" value="hello" name="hello">
<input type="submit" id="submit">

</form>
<br><br>



<!--
#######################
# iFrame for Response #
#######################
-->

<td valign="top" style="padding-left:0px;">
<iframe id="formresponse" name='formresponse' src='../placeholder_response.html' style="border: none; background-color:#d0eace" width='400px' height='550' frameborder='0'>
</iframe>
