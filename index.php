<?php
/*
Server-side PHP file to share texts and files
Featured on github.com
Developed by Kamoba https://github.com/Kamoba
*/

define('USER', 'key');                  // choose your Get variables (USER and PASS)
define('PASS', 'lazy');
    // write to file from ajax
if (isset($_POST["textblock"])){   
		$f = fopen("Text.txt", "w");   
		$content = $_POST["textblock"];
		fwrite($f, $content);  
		fclose($f); 
}

if (isset($_GET[USER])){ 
    if ($_GET[USER] != PASS){           // accessible only from YOURSERVER/lazy-share?key=lazy    
	  exit();
	}
}
else{
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Lazy Share</title>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<style>
	body {
	height: 100%;
	}
	textarea {
	  width: 100%;
	  height: 20vw;
	}
	#copy, #select{
	background:#cccccc;
	padding: 5px 15px 5px 15px;
	margin: 0 15px 0 0;
	}
	#cut{
	background:#ff3300;
	padding: 5px 15px 5px 15px;
	margin: 0 15px 0 0px;
	}
	#save{
	background:#00b33c;
	padding: 5px 15px 5px 15px;
	}
</style>
</head>
<body>
<center>
<textarea id="myText" ></textarea> <br>  
</center>
<button id="copy">Copy</button>  <button id="select" onclick="selectAll()">Select All</button> 	<button id="cut">Cut</button>  	<button id="save" onclick="Save()">Save</button>

<p id="demo"></p>

<br><br><br>

<?php
include "ajaxUpload.php";
?>

<script>
function writeToFile(d1, d2){
var fso = new ActiveXObject("Scripting.FileSystemObject");
var fh = fso.OpenTextFile("test.txt", 8, false, 0);
fh.WriteLine(d1 + ',' + d2);
fh.Close();
}
var submit = document.getElementById("submit");
submit.onclick = function () {
	var id      = document.getElementById("id").value;
	var content = document.getElementById("content").value;
	writeToFile(id, content);
}
$(document).ready(function(){
	$.ajaxSetup ({           // Disable caching of AJAX responses
	 cache: false
	});
	
	$('#myText').load('Text.txt', function() {    // load file Text.txt
	});	

});
</script>
<script>

function Save() {
	const $Text = $('#myText').val();        
	 $.ajax({                            // Send texarea content with ajax
			method: 'POST',                                       
			url: 'index.php',                     
			data: { textblock: $Text },
			timeout: 3000,                                       
			success: function(data) { Success(); },                        
			error: function() { alert('The request was unsuccessful'); }       

		  });
}
function Success() {
	document.getElementById("demo").innerHTML = "saved successfully!";
}
</script>
<script type="text/javascript">   
// select ALL
function selectAll(){
		var textBox = document.getElementById("myText");
			textBox.select();
			// Work around Chrome's little problem
				// Prevent further mouseup intervention
				textBox.onmouseup = null;
				return false;
	}
				
document.getElementById('copy').onmousedown = function() {    // on copy button pressed
console.log(document.execCommand('copy'))
}
document.getElementById('cut').onmousedown = function() {   // on cut button pressed
console.log(document.execCommand('cut'))
}


</script>


</body>
</html>



