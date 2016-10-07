<button id="run_system">Start</button>

<script>
$('#run_system').on('click', function() {
    //$.ajax({
     //   url : 'run.php'
   // }).done(function(data) {
  //      console.log(data);
//    });
	<?php echo system('mv /opt/apache2/frankdec/subdirectories/uploads/allenhub/914021_ACTTGA_L004_R1_001.fastq.gz /opt/apache2/frankdec/subdirectories/uploads/');?>;

});


</script>



<?php
echo "hello";
?>
