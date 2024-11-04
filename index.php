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

        button{
            cursor: pointer;
        }
        #reload, #copy,
        #select {
            background: #cccccc;
            padding: 5px 15px 5px 15px;
            margin: 0 15px 0 0;
        }

        #paste,
        #selectLine {
            background: #cccccc;
            padding: 5px 15px 5px 15px;
            margin: 0 15px 0 0;
        }
        #cut {
            background: #ff3300;
            padding: 5px 15px 5px 15px;
            margin: 0 15px 0 0px;
        }

        #save {
            background: #00b33c;
            padding: 5px 15px 5px 15px;
        }
        .success{
            color: #00b33c;
        }
    </style>
</head>

<body>
    <center>
        <textarea id="myText" name="myText"></textarea> <br> <!-- il faut ajouter "name" pour que serialise() marche!  -->
    </center>
    <button id="reload">&#x1F504;</button>  
    <button id="copy">Copy</button>  
<button id="selectLine" onclick="selectLine()">Select Line</button>     
<button id="select" onclick="selectAll()">Select All</button>   
<button id="cut">Cut</button>   
<button id="save" onclick="Save()">Save</button>

<p id="demo"></p>

<br><br><br>
<?php
include "ajaxUpload.php";
?>


<script>
    $(document).ready(function() {
        $.ajaxSetup({ // Disable caching of AJAX responses
            cache: false
        });

        $('#myText').load('Text.txt', function() { // lire, inclure le fichier Text.txt
        });

    });
</script>
<script>
    function Save() {
        const $Text = $('#myText').val(); //.serialize();   // pour garder les caracteres speciaux!
        //alert($Text);
        $.ajax({
            method: 'POST', // GET ou POST (GET par défaut). On peut utiliser "type" aussi à la place de "method"
            url: 'index.php', //adresse à laquelle la requête doit être envoyée.
            data: {
                textblock: $Text
            },
            timeout: 3000, // délai maximum (en millisecondes) ==> ERROR
            success: function(data) {
                Reussi();
            }, // fct exécutée en cas de succès
            error: function() {
                alert('The request was unsuccessful');
            } // fct en cas d'echéc. (La requête n\'a pas abouti)

        });
    }

    function Reussi() {
        document.getElementById("demo").innerHTML = '<span class="success">Saved successfully!</span>';
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

    document.getElementById('reload').onmousedown = function() { // on copy button released
        window.location.href = window.location.href;
    }
    document.getElementById('copy').onmousedown = function() { // on copy button released
        console.log(document.execCommand('copy'))
    }
    document.getElementById('cut').onmousedown = function() { // on cut button released
        console.log(document.execCommand('cut'))
    }



</script>


</body>

</html>
