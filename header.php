<div class="topnav">
<ul>
  <li><a class="<?php if ($pageId == 0) echo 'active'; ?>" href="index.php">Strona główna</a></li>
<?php
	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
	{
?>
		<li><a class="<?php if ($pageId == 3) echo 'active'; ?>" href="profile.php">Twój profil</a></li>
		
		<?php
		if ($_SESSION['access'] != 2){ 
		?>
		<li><a class="<?php if ($pageId == 7) echo 'active'; ?>" href="usercourses.php">Twoje kursy</a></li>
		
		<?php
		}?>
		
		<li><a class="<?php if ($pageId == 6) echo 'active'; ?>" href="courses.php">Wyświetl kursy</a></li>
		
		<?php if ($_SESSION['access'] == 1) {
		?>
		<li><a class="<?php if ($pageId == 5) echo 'active'; ?>" href="addcourse.php">Dodaj kurs</a></li>
		
		
		<?php
		}
		if ($_SESSION['access'] == 2) {
		?>
		<li><a class="<?php if ($pageId == 4) echo 'active'; ?>" href="adminpanel.php">Panel administratora</a></li>
		<?php
		}
		?>
		<li style="float:right"><a href="logout.php">Witaj <?php echo $_SESSION['name'].'! [ Wyloguj się! ]'; ?> </a></li>
<?php
	}
	else
	{
?>
		<li><a class="<?php if ($pageId == 1) echo 'active'; ?>" href="register.php">Rejestracja</a></li>
		<li><a class="<?php if ($pageId == 2) echo 'active'; ?>" href="login.php">Logowanie</a></li>
<?php
	}
?>

  <li><a class="<?php if ($pageId == 8) echo 'active'; ?>" href="shout.php">Shoutbox</a></li>
</ul> 
</div>