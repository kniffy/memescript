<?php
$debug = false; // Set this to true to display error messages
$admin_password = 'asdf'; // Access the admin area with index.php?admin=<password here>
# ░░░░░░░░░░░░▄▐
# ░░░░░░▄▄▄░░▄██▄
# ░░░░░▐▀▀▀▌░░░░▀█▄
# ░░░░░▐███▌░░░░░░▀█▄
# ░░░░░░▀█▀░░░▄▄▄▄▄▀▀
# ░░░░▄▄▄██▀▀▀▀
# ░░░█▀▄▄▄█░▀▀
# ░░░▌░▄▄▄▐▌▀▀▀
# ▄░▐░░░▄▄░█░▀▀  U HAVE BEEN SPOOKED BY THE
# ▀█▌░░░▄░▀█▀░▀
# ░░░░░░░▄▄▐▌▄▄
# ░░░░░░░▀███▀█░▄
# ░░░░░░▐▌▀▄▀▄▀▐▄  CYBER SKILENTON FROM THE YEAR 3030
# ░░░░░░▐▀░░░░░░▐▌
# ░░░░░░█░░░░░░░░█
# ░░░░░▐▌░░░░░░░░░█  SEND THIS TO 7 LIFE FORMS OR TIME TRAVELING 
# ░░░░░█░░░░░░░░░░▐▌ SKELINTONS WILL BLAST YOU WITH THEIR PHASERS
// Display an error message, or a static meme
function whoops($message='') {
global $debug;
if ($debug) {
// Display the error message
echo $message;
} else {
// Display a static meme until the error is fixed
echo <<<EOF
<a href="."><img class="meme" src="src/placeholder.jpg" height="100%"></a>
</body>
</html>
EOF;
}
die();
}
$refresh = (isset($_GET['admin']) && $_GET['admin'] == $admin_password) ? '' : '<meta http-equiv="refresh" content="10;.">';

// Write the page title and styles
echo <<<EOF
<!DOCTYPE html>
<html>
<head>
<title>The King of Memes</title>
$refresh
<style>
body {
margin: 0;
color: white;
background-color: #FFFFEE;
}
.meme {
text-align: center;
position: absolute;
margin: auto;
top: 0;
right: 0;
bottom: 0;
left: 0;
image-orientation: from-image;
}
</style>
</head>
<body>
EOF;
// Connect to the MySQL database
$link = @mysqli_connect('127.0.0.1', 'root', 'password');
if (!$link) {
whoops("Could not connect to database: " . ((is_object($link)) ? mysqli_error($link) : (($link_error = mysqli_connect_error()) ? $link_error : '(unknown error)')));
}
$db_selected = @mysqli_query($link, "USE " . 'webzone');
if (!$db_selected) {
whoops("Could not select database: " . ((is_object($link)) ? mysqli_error($link) : (($link_error = mysqli_connect_error()) ? $link_error : '(unknown error')));
}
if (isset($_GET['admin']) && $_GET['admin'] == $admin_password) {
echo <<<EOF
<form action="?admin={$_GET['admin']}" method="post" enctype="multipart/form-data">
<p><input type="file" name="meme" size="35" accesskey="f"> <input type="submit" value="Upload meme"></p>
</form>
<p><a href="?admin={$_GET['admin']}&list">List all memes</a></p>
<br>
EOF;
if (isset($_FILES['meme'])) {
if (!is_file($_FILES['meme']['tmp_name']) || !is_readable($_FILES['meme']['tmp_name'])) {
die('Meme transfer failure.');
}
$file_hex = md5_file($_FILES['meme']['tmp_name']);
$result = mysqli_query($link, "SELECT * FROM `memes` WHERE `hex` = '" . $file_hex . "' LIMIT 1");
if (!$result) {
die('Could not check for duplicate meme!');
}
if (mysqli_num_rows($result) > 0) {
die('That meme has already been uploaded.');
}
$file_type = strtolower(preg_replace('/.*(\..+)/', '\1', $_FILES['meme']['name']));
if ($file_type == '.jpeg') {
$file_type = '.jpg';
}
if ($file_type != '.jpg' && $file_type != '.gif' && $file_type != '.png') {
die('Invalid meme format');
}
$file_name = time() . $file_type;
if (!move_uploaded_file($_FILES['meme']['tmp_name'], 'src/' . $file_name)) {
die('Could not copy meme.');
}
mysqli_query($link, "INSERT INTO `memes` (`filename`, `hex`) VALUES ('" . $file_name . "', '" . $file_hex . "')");
echo 'Meme successfully uploaded:<br>';
echo '<a href="src/' .$file_name . '" target="_blank"><img src="src/' . $file_name . '" width="150" height="150" border="0"></a>';
} else if (isset($_GET['list'])) {
echo '<p>Memes:</p>';
$result = mysqli_query($link, "SELECT * FROM `memes` ORDER BY `id` DESC");
while ($meme = mysqli_fetch_assoc($result)) {
echo '<a href="src/' . $meme['filename'] . '" target="_blank"><img src="src/' . $meme['filename'] . '" width="150" height="150" border="0"></a> ' . "\n";
}
}
die();
}
// Fetch a dank meme
$result = mysqli_query($link, "SELECT MIN(`id`) AS id_min, MAX(`id`) AS id_max FROM `memes`");

if (!$result) {
whoops("Could not fetch a dank meme!");
}
while ($row = mysqli_fetch_assoc($result)) {
 $found_dank_meme = false;
 while (!$found_dank_meme) {
 $result2 = mysqli_query($link, "SELECT `filename` FROM `memes` WHERE `id` = " . mt_rand($row['id_min'], $row['id_max']) . " LIMIT 1");
 if (!$result2) {
 whoops("Could not fetch a dank meme!");
 }
 if (mysqli_num_rows($result2) > 0) {
 while ($meme = mysqli_fetch_assoc($result2)) {
 $found_dank_meme = true;
 $image = $meme['filename'];
 }
 }
 }
}
// Display the dank meme
echo <<<EOF
<a href="."><img class="meme" src="src/$image" height="100%"></a>
<audio src="nicememe.mp3" type="audio/mpeg" preload="auto" autoplay>
</body>
</html>
EOF;
# ░▄▄▄▄▄▄
# ▐▀▀████▌ U HAVE BEEN VISITED BY THE
# ▀░▐██████▄  EMBARRASSED BANANA OF HILARITY
# ░░▌▄▄▄░▄▄▐
# ░░▌░▄▄░▄▄▄▌
# ░▐░░▀▀▀▀▀▀░▌
# ░▌░░░░░░░░░░▌
# ░▐░█▀▀▀▀▀▀▀█▐
# ░▐░░▀▄▄▄▄▄▀░▌░░░▄▄█▄
# ░▐░░░░░░░░░▐▄▄▄▄▀▀▀▄
# ░▐░░░░░░░░░▐░░░░░▐░░▌▄▄▄
# ░░█░░░░░░░░▐░░░░░▐░░▐▐░░▌░░░░░░░░▄██▀▀▄▄
# ░░▐░▌░░░░░░▐░░░░░░▌░░▐▌░▌▄░░░░░▄▄█░
# ░░░█░▌░░░░░▐░░░░░░▐░░▐▐░░▀▄▄▀▀▀▀░
# ░░░░█░▌░░░░▐░░░░░░░▐░░▌▐▄░░░▀▄░
# ░░░░░█▀▄▄▄░▌░░░░░░░▐░░░▐▄▀▄░░░▀▄░
# ░░░░░░▀████▌░░░░░░░▐░▌░░░▐▄▀▄▄░░▀▀▄▄▄▀▀▀▀
# ░░░░░▐█▌▀▀▄▄░░░░░░░░▌░▌░░░░▌▄▄▀▀▀▀▄▄
# ░░░░▐█▀░░░░▀▌░░░░░░░▀▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄
# ░░░▐█▌░░░░▄▄█▌░░░░░░░░░░░░░░░░░░░░░░▀
# ░░░░▀▀▀░    Good Luck & Prosperity will come to you,
# ░░░░░░░░     but only if you say:
# ░░░░░░░░       i really really like this meme
