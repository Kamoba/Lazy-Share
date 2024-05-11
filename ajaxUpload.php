<!DOCTYPE html >
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="style.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript">
<!--
function startUpload(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function stopUpload(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
      }
      else {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" class="btn btn-default" size="30" /><\/label><br><br><label><input type="submit" name="submitBtn" class="btn btn-success sbtn" value="Upload" /><\/label>';
      document.getElementById('f1_upload_form').style.visibility = 'visible';
      return true;
}
//-->
</script>
</head>
<body>
       <div id="container">
            <div id="header"><div id="header_left"></div>
            <div id="header_main">AJAX File Uploader</div><div id="header_right"></div></div>
            <div id="content">
                <form action="upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
                     <p id="f1_upload_process" >Loading...<br/><img src="loader.gif" /><br/></p>
                     <p id="f1_upload_form" align="center"><br/>
                         <label>File:
                              <input name="myfile" type="file" class="btn btn-default" size="30" /><br><br>
                         </label>
                         <label>
                             <input type="submit" name="submitBtn" class="btn btn-success sbtn" value="Upload" />
                         </label>
                     </p>

                     <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                 </form>
             </div>

	<?php

    if ($handle = opendir('./uploads')) {
        if (isset($_GET['delete']))                  // effacer ficher aui doivent l'etre avant d'afficher la list
        {
          $fileToDelete = "uploads/" . rawurldecode($_GET['delete']);
          if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
          }
          //header('location: ?'.USER.'='.PASS); exit();             // rediriger vers home
        }
        $list = "";
        while (false !== ($file = readdir($handle))) {
          if ($file != "." && $file != "..") {
            $list .= '<li><a href="uploads/' . $file . '"download>' . $file . '</a>&nbsp;  <a href="?' . USER . '=' . PASS . '&delete=' . rawurlencode($file) . '" style="text-decoration:none;color:red;") >&#10008;</a></li>';
          }
        }
        closedir($handle);
      }

    ?>
    <h2>List of files:</h2>
    <ul><?php echo $list; ?></ul>
         </div>

</body>
