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

$cols = 10;
$height = $width = 32;
$dx = 48.0;
$dy = 48.0;
$x = $y = 0;

echo '.icon-large {  width: '.$width.'px; height: '.$height.'px; }';
echo "\n";

$i=0;
foreach ($dir as $file) {
	preg_match('~^.+_\d+_(.+).png$~', $file, $m);
	if (!isset($m[1])) { continue; }
	$name = $m[1];
	$name = str_replace('_', '-', $name);

	echo '.icon-large.icon-'.$name.' { background-image: url("../img/glyphicons.png"); background-position: '.round(-($x-2)).'px '.round(-($y-3)).'px; }';
	echo "\n";

	$x = $x + $dx;
	$i++;
	if ($i >= $cols) {
		$i=0;
		$x = 0;
		$y = $y + $dy;
	}
}