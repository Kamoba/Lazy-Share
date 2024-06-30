<?php



function getListFiles(){
  $files = [];
  if ($handle = opendir('uploads')) {
      while (false !== ($file = readdir($handle))) {
          if ($file != "." && $file != "..") {
              $files[] = [
                  'name' => $file,
                  'time' => filemtime('uploads/' . $file)
              ];
          }
      }
      closedir($handle);

      usort($files, function($a, $b) {
          return $b['time'] - $a['time'];
      });
      $list = "";
      foreach ($files as $file) {
        //$list .= '<li><a href="uploads/' . $file['name'] . '" download>' . $file['name'] . '</a>&nbsp;  <a href="?' . USER . '=' . PASS . '&delete=' . rawurlencode($file['name']) . '" style="text-decoration:none;color:red;">&#10008;</a></li>';  // GET
        $list .=  '<li><a href="uploads/' . $file['name'] . '" download>' . $file['name'] . '</a>&nbsp;  <a href="#" data-file="' . htmlspecialchars($file['name']) . '" class="delete-link" style="text-decoration:none;color:red;">&#10008;</a></li>'; // POST
      }
      return $list;
  }
}

$listFiles = "";
// update list files after upload new file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['updateList'])) {
    echo getListFiles();
    return;
}  elseif (isset($_POST['delete'])) {
    $fileToDelete = $_POST['delete'];
    $filePath = 'uploads/' . $fileToDelete;
    $listDeleted = [];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
} else {
    $list = getListFiles();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="style.css" rel="stylesheet" type="text/css" />
   
<script language="javascript" type="text/javascript">

function startUpload(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function stopUpload(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
         refreshList();
      }
      else {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><br><br><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
      document.getElementById('f1_upload_form').style.visibility = 'visible';      
      return true;   
}

function refreshList() {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'ajaxUpload.php?updateList=1', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) { 
          document.getElementById('file-list').innerHTML = xhr.responseText;
          attachDeleteHandlers(); // Attach delete handlers after refreshing the list
      }
  };
  xhr.send(); 
}


function attachDeleteHandlers() { 
    var deleteLinks = document.getElementsByClassName('delete-link');
    for (var i = 0; i < deleteLinks.length; i++) { 
        deleteLinks[i].addEventListener('click', function(event) {
            event.preventDefault();
            var fileName = this.getAttribute('data-file');
            deleteFile(fileName);
        });
    }
}

function deleteFile(fileName) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajaxUpload.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            refreshList(); // Refresh the list after deleting the file
        }
    };
    xhr.send('delete=' + encodeURIComponent(fileName));
}

document.addEventListener('DOMContentLoaded', function() {
  attachDeleteHandlers();
});
</script>   
</head>
<body>
  <div id="container">
      <div id="header">AJAX File Uploader</div>
      <div id="content">
          <form action="upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
                <p id="f1_upload_process" >Loading...<br/><img src="loader.gif" /><br/></p>
                <p id="f1_upload_form" align="center"><br/>
                    <label>File:  
                        <input name="myfile" type="file" size="30" /><br><br>
                    </label>
                    <label>
                        <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
                    </label>
                </p>                
                <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
            </form>
        </div>
    <h2>List of files:</h2>
    <ul id="file-list"><?php echo $list ?? ""; ?></ul>
  </div>

</body>   
