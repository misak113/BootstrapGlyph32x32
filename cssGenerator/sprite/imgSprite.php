<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Michael
 * Date: 8.2.13
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */

header('content-type: text/html');

define('BASE_DIR', __DIR__.'/');
define('BUILD_DIR', __DIR__.'/../../build/');

$types = array(
/* android icon-large */
	array(
		'src' => 'android-dark/',
		'spriteImageName' => 'android-iconography-large',
		'spriteCssName' => 'android-iconography-large',
		'cols' => 10,
		'height' => 32,
		'width' => 32,
		'dx' => 48.0,
		'dy' => 48.0,
		'x' => 0,
		'y' => 0,
		'prefix' => '.icon-large',
		'xStart' => 0,
	),
/**/
/* glyph icon-large */
	array(
		'src' => 'glyphicons/',
		'spriteImageName' => 'glyphicon-large',
		'spriteCssName' => 'glyphicon-large',
		'cols' => 10,
		'height' => 32,
		'width' => 32,
		'dx' => 48.0,
		'dy' => 48.0,
		'x' => 0,
		'y' => 0,
		'prefix' => '.icon-large',
		'xStart' => 0,
	),
/**/
/* glyph icon-medium */
	array(
		'src' => 'glyphicons/',
		'spriteImageName' => 'glyphicon-medium',
		'spriteCssName' => 'glyphicon-medium',
		'cols' => 10,
		'height' => 24,
		'width' => 24,
		'dx' => 36.0,
		'dy' => 36.0,
		'x' => 0,
		'y' => 0,
		'prefix' => '.icon-medium',
		'xStart' => 0,
	),
/**/
/*glyph  icon-standard */
	array(
		'src' => 'glyphicons/',
		'spriteImageName' => 'glyphicon-standard',
		'spriteCssName' => 'glyphicon-standard',
		'cols' => 10,
		'height' => 16,
		'width' => 16,
		'dx' => 24.0,
		'dy' => 24.0,
		'x' => 0,
		'y' => 0,
		'prefix' => '.icon-standard',
		'xStart' => 0,
	),
/**/
/* glyph icon-new */
	array(
		'src' => 'glyphicons/',
		'spriteImageName' => 'glyphicon-new',
		'spriteCssName' => 'glyphicon-new',
		'cols' => 10,
		'height' => 14,
		'width' => 14,
		'dx' => 21.0,
		'dy' => 21.0,
		'x' => 0,
		'y' => 0,
		'prefix' => '.icon-new',
		'xStart' => 0,
	),
/**/
/* glyph icon-mini */
	array(
		'src' => 'glyphicons/',
		'spriteImageName' => 'glyphicon-mini',
		'spriteCssName' => 'glyphicon-mini',
		'cols' => 10,
		'height' => 12,
		'width' => 12,
		'dx' => 18.0,
		'dy' => 18.0,
		'x' => 0,
		'y' => 0,
		'prefix' => '.icon-mini',
		'xStart' => 0,
	),
/**/
);

function imagetransparent($width, $height) {
	if (class_exists('Imagick')) {
		$trans = new Imagick(__DIR__.'/transparent.png');
		$trans->adaptiveResizeImage($width, $height);
		return $trans;
	
	} else {
		$trans = @imagecreatefrompng(__DIR__.'/transparent.png');
		list($old_width, $old_height) = getimagesize(__DIR__.'/transparent.png');
		$trans2 = imagecreatetruecolor($width, $height);
		// Resize
		$resized = imagecopyresized($trans2, $trans, 0, 0, 0, 0, $width, $height, $old_width, $old_height);
		imagecolortransparent($trans2, imagecolorallocate($trans2, 0,0,0));
		return $trans2;
	}
}

$table = '';
$links = '<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">';

function sprite($spriteImageName, $spriteCssName, $cols, $height, $width, $dx, $dy, $x, $y, $prefix, $xStart, $src) {
	global $table, $links;

	$dir = scandir(BASE_DIR.$src);

	$table.= '<table><thead><tr><th colspan="'.$cols.'">'.$spriteCssName.' '.$prefix.'</th></tr></thead><tbody><tr>';
	$ob = ob_start();

	$background = 'background-image: url("../img/'.$spriteImageName.'.png");';

	echo $prefix.' { width: '.$width.'px; height: '.$height.'px; }';
	echo "\n";

	$img = imagetransparent($cols*$dx+$xStart, (round(count($dir)/$cols)+1)*$dy);


	$i = 0;
	foreach ($dir as $file) {
		$filename = BASE_DIR.$src.$file;
		$newX = round(($x-2+$xStart));
		$newY = round(($y-3));


		if (class_exists('Imagick')) {
			// image
			try {
				$icon = new Imagick($filename);
			} catch (Exception $e) { continue; }

			$icon->adaptiveResizeImage($width, $height, true);
			// append
			$img->compositeImage($icon, Imagick::COMPOSITE_OVERLAY, $newX, $newY);

		} else {
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
		}


		preg_match('~^([a-zA-Z]+_)?\d+_(.+).png$~', $file, $m);
		if (!isset($m[2])) { continue; }
		$name = $m[2];
		$name = str_replace('_', '-', $name);

		echo $prefix.'.icon-'.$name.' { '.$background.' background-position: '.(-$newX).'px '.(-$newY).'px; }';
		echo "\n";

		$table.= '<td title="'.$prefix.'.icon-'.$name.'"><i class="'.str_replace('.', '', $prefix).' icon-'.$name.'"></i></td>';

		$x = $x + $dx;
		$i++;
		if ($i >= $cols) {
			$i = 0;
			$x = 0;
			$y = $y + $dy;
			$table.= '</tr><tr>';
		}
	}
	$css = ob_get_clean();
	$table.= '</tr></tbody></table>';

	file_put_contents(BUILD_DIR.'css/'.$spriteCssName.'.css', $css);
	$links.= '<link rel="stylesheet" href="./css/'.$spriteCssName.'.css" />';

	if (class_exists('Imagick')) 
		$saved = $img->writeImage(BUILD_DIR.'img/'.$spriteImageName.'.png');
	else
		$saved = imagepng($img, BUILD_DIR.'img/'.$spriteImageName.'.png');

	if ($saved) echo 'sprite created '.$spriteCssName.' <br />';
	else echo 'sprite failed '.$spriteCssName.' <br />';

}


foreach ($types as $type) {
	sprite($type['spriteImageName'], $type['spriteCssName'], $type['cols'], 
		$type['height'], $type['width'], $type['dx'], $type['dy'], $type['x'], 
		$type['y'], $type['prefix'], $type['xStart'], $type['src']
	);
}

$h = '<!DOCTYPE html>';
$h.= '<html>';
$h.= '<head>';
$h.= $links;
$h.= '</head>';
$h.= '<body>';
$h.= $table;
$h.= '</body>';
$h.= '</html>';


file_put_contents(BUILD_DIR.'docs.html', $h);
