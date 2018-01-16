<html>
<head>
  <title>Elektroniczny dziennik</title>
<?php 
  
  if(isset($_COOKIE['style'])) {
	echo'<link rel="stylesheet" href="css/'.$_COOKIE['style'].'.css?v=1.0">';
  }
  else {
	  echo '<link rel="stylesheet" href="css/styles.css?v=1.0">';
  }
  ?>
  
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
</head>
<body>
<div class="main">
<?php
    if(!isset($_SESSION)) 
    { 
		session_start();
	}
	$pageId = 8;
	include 'header.php';
?>
<div class="content">
<h1>Komunikator</h1>

        <div class="shoutbox">
            
            <h1>Shoutbox <img src='./images/refresh.png'/></h1>
            
            <ul class="shoutbox-content"></ul>
			<?php
			if (isset($_SESSION['logged']))
				{
					echo '<div class="shoutbox-form">
						<h2>Napisz wiadomość <span>×</span></h2>
						
						<form action="./publish.php" method="post">
							<label class="shoutbox-comment-label" for="shoutbox-comment">Wiadomość</label>
							<input type="text" id="shoutbox-comment" name="comment" size="20">
							<input type="submit" value="Wyślij"/>
						</form>
					</div>';
				}
				else {
					echo '<div class="shoutbox-notlogged">Zaloguj się aby móc wysłać wiadomości</div>';
				}
				?>
            
        </div>

</div>
<?php
include 'footer.php';
?>
</div>

        <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="./js/shoutbox.js"></script>

</body>
</html> 