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

/* icon-large * /
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
$xStart = 463;
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
/* icon-new */
$cols = 10;
$height = $width = 14;
$dx = 21.0;
$dy = 21.0;
$x = $y = 0;
$prefix = '.icon-new';
$xStart = 0;//463+347+232+174;
/**/

echo $prefix.' { background-image: url("../img/glyphicons-new-compact.png"); width: '.$width.'px; height: '.$height.'px; }';
echo "\n";

$i = 0;
foreach ($dir as $file) {
	preg_match('~^.+_\d+_(.+).png$~', $file, $m);
	if (!isset($m[1])) { continue; }
	$name = $m[1];
	$name = str_replace('_', '-', $name);

	echo $prefix.'.icon-'.$name.' { background-position: '.round(-($x-2+$xStart)).'px '.round(-($y-3)).'px; }';
	echo "\n";

	$x = $x + $dx;
	$i++;
	if ($i >= $cols) {
		$i = 0;
		$x = 0;
		$y = $y + $dy;
	}
}