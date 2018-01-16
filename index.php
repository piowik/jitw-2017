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
	<!--
	<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet"> 
	-->

  </head>
<body>
<div class="main">
<?php

    if(!isset($_SESSION)) 
    { 
		session_start();
	}
	$pageId = 0;
	include 'header.php';
?>
<div class="content">
<h1>Elektroniczny dziennik</h1>
<table><tr><th>Wymaganie</th><th>Status</th></tr>
<tr><td>posiadać co najmniej dwa style css (mile widziany css3) oraz jeden do druku</td><td>OK</td></tr>
<tr><td>logowanie</td><td>OK</td></tr>
<tr><td>projekty nie wykorzystują baz danych wszelkie informacje zawarte są w plikach tekstowych (osoby chcące stosować bazy danych SQL lub NoSQL mogą realizować projekt z ich wykorzystaniem w przypadku wykorzystania baz danych można otrzymać do 10 punktów dodatkowych)</td><td>SQL</td></tr>
<tr><td>komunikator</td><td>OK</td></tr>
<tr><td>wykorzystanie JavaScript</td><td>Siła hasła, komunikator, pokazywanie/ukrywanie elementów, zmiana stylu</td></tr>
<tr><td>Logowanie (3 poziomy użytkowników: student, nauczyciel, admin)</td><td>OK</td></tr>
<tr><td>Dodawanie użytkowników przed admina</td><td>OK</td></tr>
<tr><td>rejestracja zwykła</td><td>OK</td></tr>
<tr><td>Administratorzy i nauczyciele mogą dodawać projekty lub ćwiczenia i oceniać je</td><td>OK</td></tr>
<tr><td>studenci mogą dodawać rozwiązania projektów lub ćwiczeń do oceny</td><td>OK</td></tr>
<tr><td>Studenci mogą przeglądać swoje oceny (punkty).</td><td>OK</td></tr>
</table>
</div>
<?php
include 'footer.php';
?>
</div>

</body>
</html> 