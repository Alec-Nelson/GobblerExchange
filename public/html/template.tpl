<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sign Up</title>
<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/WhatToWeather.css">
<!-- <script src="<?= BASE_URL ?>/public/js/jquery-2.2.0.min.js"></script> -->
</head>
<body>

<ul id="breadCrumbs">
	<li><a href="<?= BASE_URL ?>/about/">About	</a></li>
	<li><a href="<?= BASE_URL ?>/settings/">Settings</a></li>
	<li><a href="<?= BASE_URL ?>/addclothes/">Add Clothes</a></li>
	<li><a href="<?= BASE_URL ?>/">Home	</a></li>
	<li hidden id="weather" ><img id="wicon"><a id="wtext"></a></li>
</ul>

<h1>Sign Up</h1>


<?php
	if( !isset($_SESSION['username']) || $_SESSION['username'] == '') {
?>
<p id="userStatus" class="userStatus"></p>
<form id="register" method="POST" action="<?= BASE_URL ?>/signup/user">
<div class="textBox">
	<input id="email" type="text" placeholder=" Email" name="email"/>
	<input id="uname" type="text" placeholder=" Username" name="uname" />
	<input id="pass" type="password" placeholder=" Password" name="pass"/>
	<input id="pass2" type="password" placeholder=" Confirm Password" name="pass2"/>
</div>
<div class="but">
	<input id="signUpButton" class="buttons" type="submit" value="Sign Up" />
</div>
</form>

		<?php
			} else {
		?>

			<p>Logged in as <strong><?= $_SESSION['username'] ?></strong>. <a href="<?= BASE_URL ?>/logout">Log out?</a></p>

		<?php
			}
		?>

<p class="description">
Making an account will allow you to recieve messages and texts, and will allow you personalize your clothing for the weather you like it at.
</p>

</body>
</html>
