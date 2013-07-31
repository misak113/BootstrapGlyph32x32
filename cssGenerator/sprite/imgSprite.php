<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Michael
 * Date: 8.2.13
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */

header('content-type: text/css');

$dir = scandir(__DIR__.'/imgs');

$spriteImageName = 'android-iconography-medium';
$spriteCssName = 'android-iconography-medium';
/* icon-large */
$cols = 10;
$height = $width = 32;
$dx = 48.0;
$dy = 48.0;
$x = $y = 0;
$prefix = '.icon-large';
$xStart = 0;
/**/
/* icon-medium * /
$cols = 10;
$height = $width = 24;
$dx = 36.0;
$dy = 36.0;
$x = $y = 0;
$prefix = '.icon-medium';
$xStart = 0;
/**/
/* icon-standard * /
$cols = 10;
$height = $width = 16;
$dx = 24.0;
$dy = 24.0;
$x = $y = 0;
$prefix = '.icon-standard';
$xStart = 463+347;
/**/
/* icon-mini * /
$cols = 10;
$height = $width = 12;
$dx = 18.0;
$dy = 18.0;
$x = $y = 0;
$prefix = '.icon-mini';
$xStart = 463+347+232;
/**/
/* icon-new * /
$cols = 10;
$height = $width = 14;
$dx = 21.0;
$dy = 21.0;
$x = $y = 0;
$prefix = '.icon-new';
$xStart = 0;//463+347+232+174;
/**/

function imagetransparent($width, $height) {
	$trans = @imagecreatefrompng(__DIR__.'/transparent.png');
	list($old_width, $old_height) = getimagesize(__DIR__.'/transparent.png');
	$trans2 = imagecreatetruecolor($width, $height);
	// Resize
	$resized = imagecopyresized($trans2, $trans, 0, 0, 0, 0, $width, $height, $old_width, $old_height);
	imagecolortransparent($trans2, imagecolorallocate($trans2, 0,0,0));
	return $trans2;
}
//header('content-type: image/png');
//imagepng(imagetransparent(100, 100));die();

$ob = ob_start();

echo $prefix.' { background-image: url("../img/'.$spriteImageName.'.png"); width: '.$width.'px; height: '.$height.'px; }';
echo "\n";

$img = imagetransparent($cols*$dx+$xStart, (round(count($dir)/$cols)+1)*$dy);

$i = 0;
foreach ($dir as $file) {
	$filename = __DIR__.'/imgs/'.$file;
	$newX = round(($x-2+$xStart));
	$newY = round(($y-3));
	// image
	$icon = @imagecreatefrompng($filename);
	if (!$icon) continue;

	// Get new sizes
	list($old_width, $old_height) = getimagesize($filename);
	$newwidth = $width;
	$newheight = $height;
	// Load
	if (true || $newwidth != $old_width) {
		$thumbIcon = imagetransparent($newwidth, $newheight);
		// Resize
		$resized = imagecopyresized($thumbIcon, $icon, 0, 0, 0, 0, $newwidth, $newheight, $old_width, $old_height);
		$icon = $thumbIcon;
	}
	// append
	$merged = imagecopymerge($img, $icon, $newX, $newY, 0, 0, $newwidth, $newheight, 100);

	preg_match('~^\d+_(.+).png$~', $file, $m);
	if (!isset($m[1])) { continue; }
	$name = $m[1];
	$name = str_replace('_', '-', $name);

	echo $prefix.'.icon-'.$name.' { background-position: '.(-$newX).'px '.(-$newY).'px; }';
	echo "\n";

	$x = $x + $dx;
	$i++;
	if ($i >= $cols) {
		$i = 0;
		$x = 0;
		$y = $y + $dy;
	}
}
$css = ob_get_clean();

file_put_contents('./'.$spriteCssName.'.css', $css);

//header('content-type: image/png');
//imagepng($img);die();
$saved = imagepng($img, './'.$spriteImageName.'.png');

if ($saved) echo 'sprite vytvořen';
else echo 'nastala chyba';
