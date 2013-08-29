<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Michael
 * Date: 8.2.13
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */

//header('content-type: text/css');

//define('__DIR__', '.');


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
	$trans = new Imagick(__DIR__.'/transparent.png');
	$trans->adaptiveResizeImage($width, $height);
	return $trans;
}


$echo = $prefix.' { background-image: url("../img/'.$spriteImageName.'.png"); width: '.$width.'px; height: '.$height.'px; }';
$echo.= "\n";

$img = imagetransparent($cols*$dx+$xStart, (round(count($dir)/$cols)+1)*$dy);

$i = 0;
foreach ($dir as $file) {
	$filename = __DIR__.'/imgs/'.$file;
	$newX = round(($x-2+$xStart));
	$newY = round(($y-3));
	// image
	try {
		$icon = new Imagick($filename);
	} catch (Exception $e) { continue; }

	$icon->adaptiveResizeImage($width, $height);
	// append
	$img->compositeImage($icon, Imagick::COMPOSITE_DSTIN, $newX, $newY);

	// CSS prepare
	preg_match('~^\d+_(.+).png$~', $file, $m);
	if (!isset($m[1])) { continue; }
	$name = $m[1];
	$name = str_replace('_', '-', $name);

	$echo.= $prefix.'.icon-'.$name.' { background-position: '.(-$newX).'px '.(-$newY).'px; }';
	$echo.= "\n";

	$x = $x + $dx;
	$i++;
	if ($i >= $cols) {
		$i = 0;
		$x = 0;
		$y = $y + $dy;
	}
}
$css = $echo;

file_put_contents('./'.$spriteCssName.'.css', $css);

$saved = $img->writeImage('./'.$spriteImageName.'.png');

if ($saved) echo 'sprite vytvořen';
else echo 'nastala chyba';
