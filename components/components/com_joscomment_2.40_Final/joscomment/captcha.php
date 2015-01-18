<?php define('_VALID_MOS', 1);

require_once('../../../configuration.php');

function query($query)
{
    return mysql_query(str_replace('#__', $GLOBALS['mosConfig_dbprefix'], $query));
}

if (isset($HTTP_GET_VARS["refid"]) && $HTTP_GET_VARS["refid"] != "") {
    $referenceid = stripslashes($HTTP_GET_VARS["refid"]);
} else {
    $referenceid = md5(mktime() * rand());
}

$font = "./captcha/century.ttf";
$bgurl = rand(1, 3);
$im = ImageCreateFromPNG("captcha/bg" . $bgurl . ".png");
$chars = array("a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g",
    "G", "h", "H", "i", "I", "j", "J", "k",
    "K", "L", "m", "M", "n", "N", "o", "p", "P", "q", "Q",
    "r", "R", "s", "S", "t", "T", "u", "U", "v",
    "V", "w", "W", "x", "X", "y", "Y", "z", "Z", "2", "3", "4",
    "5", "6", "7", "8", "9");

$length = 5;
$textstr = "";

for ($i = 0; $i < $length; $i++) {
    $textstr .= $chars[rand(0, count($chars)-1)];
}

$size = rand(12, 14);
$angle = rand(-4, 4);

$color = ImageColorAllocate($im, rand(0,64), rand(0,64), rand(0,64));
$rk_color = ImageColorAllocate($im, 128, 128, 128);

$textsize = imagettfbbox($size, $angle, $font, $textstr);
$twidth = abs($textsize[2] - $textsize[0]);
$theight = abs($textsize[5] - $textsize[3]);

$x = (imagesx($im) / 2) - ($twidth / 2) + (rand(-25, 25));
$y = (imagesy($im)) - ($theight / 2) + 3;

ImageTTFText($im, $size, $angle, $x + 2, $y + 2, $rk_color, $font, $textstr);
ImageTTFText($im, $size, $angle, $x, $y, $color, $font, $textstr);

header("Content-Type: image/png");
ImagePNG($im);
imagedestroy($im);

mysql_connect($mosConfig_host, $mosConfig_user, $mosConfig_password) or die(mysql_error());
mysql_select_db($mosConfig_db);
query("INSERT INTO #__comment_captcha (insertdate, referenceid, hiddentext) VALUES (now(), '" . $referenceid . "', '" . $textstr . "')");
query("DELETE FROM #__comment_captcha WHERE insertdate < date_sub(now(), interval 1 day)");

?>
