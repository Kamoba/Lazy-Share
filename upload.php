<?php
   // Edit upload location here
   $destination_path = getcwd() . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR;

   $result = 0;
   $nrFiles = count($_FILES['myfile']['name']);
   for ($n = 0; $n < $nrFiles; $n++) {
     $target_path = $destination_path . basename($_FILES['myfile']['name'][$n]);
     if(@move_uploaded_file($_FILES['myfile']['tmp_name'][$n], $target_path)) $result = 1;
   }
?>

<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?>);</script>   
