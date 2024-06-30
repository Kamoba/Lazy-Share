<?php
/*
Server-side PHP file to share texts and files
Featured on github.com
Developed by Kamoba https://github.com/Kamoba
*/

define('USER', 'key');                  // choose your Get variables (USER and PASS)
define('PASS', 'lazy');
    // write to file from ajax
if (isset($_POST["textblock"])) {
		$f = fopen("Text.txt", "w");
		$content = $_POST["textblock"];
		fwrite($f, $content);
		fclose($f);
}

if (!isset($_GET[USER]) || $_GET[USER] != PASS) {           // accessible only from YOURSERVER/lazy-share?key=lazy
	  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Lazy Share</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<meta name="viewport" content= "width=device-width, initial-scale=1.0">
</head>
<body>
<textarea id="myText" name="myText"></textarea>
<div class="buttons">
<button id="copy">Copy</button>  
<button id="selectLine" onclick="selectLine()">Select Line</button> 	
<button id="select" onclick="selectAll()">Select All</button> 	
<button id="cut">Cut</button>  	
<button id="save" onclick="Save()">Save</button>
</div>

<p id="demo"></p>

<?php
include "ajaxUpload.php";
?>

<script>
/*
function writeToFile(d1, d2) {
var fso = new ActiveXObject("Scripting.FileSystemObject");
var fh = fso.OpenTextFile("test.txt", 8, false, 0);
fh.WriteLine(d1 + ',' + d2);
fh.Close();
}
var submit = document.getElementById("submit");
submit.onclick = function() {
	var id      = document.getElementById("id").value;
	var content = document.getElementById("content").value;
	writeToFile(id, content);
}*/
$(document).ready(function() {
	$.ajaxSetup({           // Disable caching of AJAX responses
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
		function selectLine() {
		var textarea = document.getElementById('myText');
		var cursorPos = textarea.selectionStart;
		var selectionEnd = textarea.selectionEnd;

		// Store current scroll position
		var scrollTop = textarea.scrollTop;

		// Find start position of the current line
		var startPos = cursorPos;
		while (startPos > 0 && textarea.value[startPos - 1] !== '\n') {
			startPos--;
		}

		// Find end position of the current line
		var endPos = cursorPos;
		while (endPos < textarea.value.length && textarea.value[endPos] !== '\n') {
			endPos++;
		}

		// Set selection range to select the current line
		textarea.setSelectionRange(startPos, endPos);

		// Restore focus and scroll position
		textarea.focus();
		textarea.scrollTop = scrollTop;

		// Restore selection if it was not at cursor position
		if (selectionEnd !== cursorPos) {
			textarea.setSelectionRange(cursorPos, selectionEnd);
		}
	}
// select ALL
function selectAll() {
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
