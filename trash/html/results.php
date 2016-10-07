<?php 
####################################
# Required File Structure:         #
#                                  #
# subdirectories/                  #
#   --fastq_to_be_crunched/        #
#   --fasta_directory/             #
#   --annotation_directory/        #
#   --temp_output/                 #
#   --bash_scripts/                #
#   --thcl_output/                 #
#   --logs/                        #
#                                  #
# Modify $subdirectories to change #
#   the root of the file system    #
####################################

$subdirectories = "/var/www/subdirectories_for_interface/";
$analysisoutput = "$subdirectories/diffexpress_output";

if(empty($_GET['analysis'])){
	exit("<h4>Error: Invalid link.</h4>");
}

$analysis = htmlspecialchars($_GET['analysis']);

$analysispath = "$analysisoutput/$analysis/out.txt";


$fh = fopen($analysispath, 'r');
$read = file_get_contents($analysispath);

if(empty($read)){
	exit("<h4>Error: Invalid link.</h4>");
}

$lines = explode("\n", $read);

ini_set('memory_limit', '512M');

$i = 0;
foreach($lines as $key => $value)
{
	$temp = preg_replace('/\s+/', "\t", $value);
	$rows[$i] = explode("\t", $temp);
	$i++;
}

echo "<table border='3' style='width:300px;'>";

foreach ($rows as $row)
{
	echo "<tr>";
	foreach($row as $col)
	{
		if ($col != "\t")
		{
			echo "<td>$col</td>";
		}
	}
	echo "</tr>";
}
echo "</table>";

?>
