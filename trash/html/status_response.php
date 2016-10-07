<head>
<style type="text/css">
.centercontainer {
	/* Internet Explorer 10 */
	display:-ms-flexbox;
	-ms-flex-pack:center;
	-ms-flex-align:center;

	/* Firefox */
	display:-moz-box;
	-moz-box-pack:center;
	-moz-box-align:center;

	/* Safari, Opera, and Chrome */
	/*display:-webkit-box;
	-webkit-box-pack:center;
	-webkit-box-align:center;*/

	/* W3C */
	display:box;
	box-pack:center;
	box-align:center;

	display: inline-block;


}
.progress-label {
    float: left;
    margin-left:45%;
    margin-top: 5px;
    font-weight: bold;
    text-shadow: 1px 1px 0 #fff;
}

table
{
//border: 1px solid #000;
}
th
{
border-bottom: 1px solid #000;
}
th, td
{
/*border:1px solid black;*/
padding:5px;
}

.status
{
	border-radius: 100%;
	margin:0px 10px;
	border: 1px solid black;
	width: 15px;
	height: 15px;
	float:center;
}

.queued
{
	background: yellow;
}

.running
{
	background:green;
}

.failed
{
	background:red;
}

.key
{
	float: left;
	width: 15px;
	text-align: left;
	margin: 2px 2px;
	margin-left:10px;
	display: inline-block;
}

.keytext
{
	width: 80px;
	float: left;
	text-align: left;
	margin: 2px 2px;
	display: inline-block;
	font-size:14px;
	font:Times;
}

</style>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src='//code.jquery.com/jquery-1.10.2.js'></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<script language="javascript">
$(document).ready(function() {
  $(function () {
      var progressbar = $("#progressbar"),
          progressLabel = $(".progress-label");

      progressbar.progressbar({
          value: false,
          change: function () {
              progressLabel.text(progressbar.progressbar("value") + "%");
          },
          complete: function () {
              progressLabel.text("100%");
          }
      });

      function progress() {
          var val = progressbar.progressbar("value") || 0;

          progressbar.progressbar("value", val + 1);

          if (val < 99) {
              setTimeout(progress, 100);
          }
      }

      setTimeout(progress, 3000);
  });
});
</script>

</head>

<body>

<center>
<br><br>
<?php
session_start();
if(empty($_SESSION['user_name']) && !($_SESSION['user_is_logged_in']))
{
  header('Location: index.php');
}


$subdirectories = "/var/www/subdirectories_for_interface";

$scripts = scandir("$subdirectories/bash_scripts");


$fRNAkrunning = exec("ps aux | grep -E '[r]un_(.*)\.(mapcount|diffexp)\.sh' ", $outputs);

if ( empty($fRNAkrunning) && count($outputs) == 0 && empty($scripts))
{
	echo "No processes currently queued or running!<br>";
} 
else {

	echo "<table>";
	echo "<tr>";
	echo "<th>ID</th><th>Type</th><th>User</th><th>Started</th><th>Status</th>";
	echo "</tr>";
	$files = array();
	if (!empty($fRNAkrunning) && count($outputs) > 0)
	{
		foreach($outputs as $proc)
		{ 
			preg_match("/(run_(.*)\.(.*)\.sh)/",$proc,$match);
			$file = $match[1];
			if($match[3] != '*')
			{
				echo "<tr>";
				array_push($files, $file);
				$id = $match[2];
				$type = $match[3];
				echo "<td>$id</td><td>$type</td><td>USER</td><td>TIME</td><td><div class='status running'></div></td></tr>";
			}
		}
	}
	if( !empty($scripts) )
	{

		foreach ($scripts as $script)
		{
			if(($script != ".") and ($script != ".."))
			{
				if(!empty($files))
				{
					foreach($files as &$file)
					{
						if($file != $script)
						{
							preg_match("/(run_(.*)\.(.*)\.sh)/",$script,$match);
							$id = $match[2];
							$type = $match[3];
							if($id != '')
							{
								echo "<tr>";
								echo "<td>$id</td><td>$type</td><td>USER</td><td>TIME</td><td><div class='status queued'></div></td></tr>";
								unset($files[$file]);
							}
						}
					}
				}
				else
				{
					preg_match("/(run_(.*)\.(.*)\.sh)/",$script,$match);
					if (count($match) > 0)
					{
						$id = $match[2];
						$type = $match[3];
						if($id != '')
						{
							echo "<tr>";
							echo "<td>$id</td><td>$type</td><td>USER</td><td>TIME</td><td><div class='status queued'></div></td></tr>";
						}
					}
				}
			}
		}
	}

}

echo "</table>";

?>

<!--
#########################
# fRNAkenstein Reloader #
#########################
-->

<script language="javascript">
function reloader()
{
        /* Reload window */
        parent.location.reload();
        /* Set iFrame to empty */
        window.location.assign("about:blank");

}
</script>


<br>
<a href="" onclick="return reloader(event)"><h3 style="opacity:0.3;">Refresh to check status again</h2></a><br>
</div>


</center>
</div>
</body>
