<?php
# include the function here
include 'function.resize.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<link rel="shortcut icon" href="images/hayley.ico">
        <title>Hayley Gripp</title>
        <link rel="stylesheet" href="CSS/stylesheet.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="CSS/GalleryCSS.css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
        <script type="text/javascript" src="JS/Galleryjs.js"></script>
        <script type="text/javascript" src="JS/gallerypageJS.js"></script>
    </head>
    <body id="pictures">
    	<div id="wrapper">
	    	<?php include "menu.php"; ?>
	    	
	    	<div class="container">
	    		<div id="galleryList">
					<ul>
					<?php
					$dirs = array('cache', 'cache/remote');
					foreach($dirs as $dirr) {
					    $end_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dirr;
					    if(!is_dir($end_dir)) {
					        echo "<p><em>Hint: If this page looks broken, you probably need to 'mkdir -m 777 -p $end_dir</em></p>";
					    }
					}
					
					$dir = new DirectoryIterator("images/gallery/");
					foreach ($dir as $fileInfo)
					{
						if($fileInfo->isDot()) continue;
						$pic = $fileInfo->getFilename();
						$settings = array('w'=>267,'h'=>200,'crop'=>true,'canvas-color' => 'white');
					?>
						<li class='galleryList'>
								<a href='images/gallery/<? print $pic ?>' onclick='return hs.expand(this)'>
									<img class="galleryImage" src='<?=resize('images/gallery/'.$pic,$settings)?>' border='0' />
								</a>
						</li>
					<?php } ?>
					</ul>
				</div>
				<?php if(isset($_SESSION['adminusername']))
				{ ?>
		    	<div class='uploadForm'>
    				<form enctype='multipart/form-data' id="picUploadForm" action="PHPscripts/uploader.php" method='post'>
    					<span class="title">Upload Pictures</span><br>
					  	Choose file to upload: <input name='uploadedFile' type='file' />
					  	<br><br>
					  	<input type='submit' value='Upload File' />
        			</form>
        		</div>
        		<?php } ?>
		    </div>
		</div>
	    <?php include "footer.html"; ?>
    </body>
</html>