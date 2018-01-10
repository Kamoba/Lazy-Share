<?php
    // write to file from ajax
if (isset($_POST["textblock"])){   
		$f = fopen("Text.txt", "w");   // Open the text file
		$content = $_POST["textblock"];
		fwrite($f, $content);  
		fclose($f);  
}

if (isset($_GET['Key'])){ 
    if ($_GET['Key'] != "lazy"){           // accessible only on YOURSITEWEB/Lazy Share/?Key=lazy
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
					#copy{
					background:#cccccc;
					padding: 5px 15px 5px 15px;
					margin: 0 15px 0 15px;
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
				<button id="copy">Copy</button> 	<button id="cut">Cut</button>  	<button id="save" onclick="Save()">Save</button>

				<p id="demo"></p>

				<br><br><br>
				<?php
				include "ajaxUpload.php";
				?>

				
				<script>
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
					var textBox = document.getElementById("myText");
					textBox.onfocus = function() {
						textBox.select();

						// Work around Chrome's little problem
						textBox.onmouseup = function() {
							// Prevent further mouseup intervention
							textBox.onmouseup = null;
							return false;
						};
					};
				document.getElementById('copy').onmousedown = function() {    // on copy button pressed
                console.log(document.execCommand('copy'))
                }
				document.getElementById('cut').onmousedown = function() {   // on cut button pressed
                console.log(document.execCommand('cut'))
                }
				
				
               </script>


				</body>
				</html>



