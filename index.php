<?php
/*
Server-side PHP file to share texts and files
Featured on github.com
Developed by Kamoba https://github.com/Kamoba
*/

define('USER', 'key');                  // choose your Get variables (USER and PASS)
define('PASS', 'lazy');

if (!isset($_GET[USER]) || $_GET[USER] != PASS) {           // accessible only from YOURSERVER/lazy-share?key=lazy
	  exit();
}

    // write to file from ajax
if (isset($_POST["myText"])) {
		$f = fopen("Text.txt", "w");
		$content = $_POST["myText"];
		fwrite($f, $content);
		fclose($f);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Lazy Share</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<meta name="viewport" content= "width=device-width, initial-scale=1.0">
</head>
<body>
<h2>Text Share</h2>
<form action="index.php?<?php echo($_SERVER['QUERY_STRING']); ?>" method="post">
<textarea id="myText" name="myText"><?php if (file_exists('Text.txt')) readfile('Text.txt'); ?></textarea>

<div class="buttons">
<input type="submit" id="save" value="Save"></input>
<span id="txtMsg"><?php if ($_POST['myText']) echo('Saved successfully') ?></span>
</div>
</form>

<?php
include "ajaxUpload.php";
?>

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
    document.getElementById('txtMsg').innerHTML = '';
}

function selectAll() {
    document.getElementById("myText").select();
    document.getElementById('txtMsg').innerHTML = '';
    return false;
}

function copy(_ev, rm) {    // on copy button pressed
   if (!navigator.clipboard) {
       document.getElementById('txtMsg').innerHTML = 'Copying not supported';
       return;
   }

   const txtArea = document.getElementById('myText');
   let start = txtArea.selectionStart;
   const finish = txtArea.selectionEnd;
   let text = txtArea.value.substring(start, finish);
   if (!text) {
       text = document.getElementById('myText').value;
       txtArea.select();
       start = 0;
   }

   navigator.clipboard.writeText(text).then(function() {
       document.getElementById('txtMsg').innerHTML = `Copied ${text.length} characers`;
       if (rm) {
           txtArea.value = txtArea.value.substr(0, start) + txtArea.value.substr(txtArea.selectionEnd);
           txtArea.setSelectionRange(start, start);
       }
       txtArea.focus();
   }, function() {
       document.getElementById('txtMsg').innerHTML = 'Copy failed';
   });
}

function cut(ev) {
    copy(ev, true);
}

const btnSubmit = document.getElementById('save');

const btns = ['Select Line', 'Select All', 'Cut', 'Copy'];
const cb = [selectLine, selectAll, cut, copy];
for (let n = 0; n < btns.length; n++) {
  const btnNew = document.createElement('button');
  btnNew.type = 'button';
  btnNew.innerHTML = btns[n];
  btnNew.addEventListener('click', cb[n]);
  btnSubmit.parentElement.insertBefore(btnNew, btnSubmit);
}
</script>


</body>
</html>
