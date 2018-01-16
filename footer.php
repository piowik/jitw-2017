
<?php

$cookie_name = "style";
	if(!isset($_COOKIE[$cookie_name])) {
		$cookie_value = "styles";
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	
?>
<div class="footer">
Elektroniczny dziennik 2017.<br>
Zmień styl
<select name="selectedStype" onchange="report(this.value)">
	<option value="styles" <?php if ($_COOKIE[$cookie_name] == 'styles') echo ' selected="selected"'; ?>>Styl 1</option>
	<option value="styles2" <?php if ($_COOKIE[$cookie_name] == 'styles2') echo ' selected="selected"'; ?>>Styl 2</option>
</select>
</div>
<script type="text/javascript" src="js/stylechange.js"></script>