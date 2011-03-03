<?php

/**
 * Spritemap Generator
 *
 * Concatenates a series of PNG images into one for use with
 * FlashPunk and other game frameworks. Assumes all images
 * are the same width and height, and are already cropped.
 * Very simple (as you can see), but it works. Requires PHP
 * with GD extension.
 *
 * Usage: php -f spritegen.php
 *
 * If you hit a memory limit error in PHP, run like this:
 *
 * php -dmemory_limit=512M -f spritegen.php
 *
 * Author: John Luxford <lux@samsquanchgames.com>
 * Website: http://www.samsquanchgames.com/
 * License: http://opensource.org/licenses/lgpl-3.0
 * Updates: http://github.com/samsquanchgames/spritegen
 */

// get the arguments interactively
$h = fopen ('php://stdin', 'r');

echo 'Folder: [.]: ';
$dir = fgets ($h);
$dir = (trim ($dir) == '') ? '.' : trim ($dir);

echo 'Output file [OUT.png]: ';
$out = fgets ($h);
$out = (trim ($out) == '') ? 'OUT.png' : trim ($out);

echo 'Max per line [0]: ';
$limit = fgets ($h);
$limit = (trim ($limit) == '') ? 0 : (int) trim ($limit);

fclose ($h);
unset ($h);

echo "Running, please wait...\n";

// read all the pngs from the folder
$d = dir ($dir);
$files = array ();
while (false !== ($entry = $d->read ())) {
	if (preg_match ('/\.png$/i', $entry)) {
		$files[] = $dir . '/' . $entry;
	}
}
$d->close ();
unset ($d);

// determine the max width of the image
$one = imagecreatefrompng ($files[0]);
$onex = imagesx ($one);
$oney = imagesy ($one);
imagedestroy ($one);
unset ($one);

if ($limit == 0) {
	$maxx = $onex * count ($files);
	$maxy = $oney;
} else {
	$maxx = $onex * $limit;
	$maxy = $oney * ceil (count ($files) / $limit);
}
$x = 0;
$y = 0;

// the new image
$img = imagecreatetruecolor ($maxx, $maxy);
imagealphablending ($img, false);
imagesavealpha ($img, true);
$trans_color = imagecolorallocatealpha ($img, 0, 0, 0, 127);
imagefill ($img, 0, 0, $trans_color);

// concat the images
foreach ($files as $c => $file) {
	if ($c > 0 && $limit > 0 && $c % $limit == 0) {
		$x = 0;
		$y += $oney;
	}
	$tmp = imagecreatefrompng ($file);
	imagesetbrush ($img, $tmp);
	imageline ($img, $x + ($onex / 2), $y + ($oney / 2), $x + ($onex / 2), $y + ($oney / 2), IMG_COLOR_BRUSHED);
	imagedestroy ($tmp);
	$x += $onex;
}

// output time
imagesavealpha ($img, true);
imagepng ($img, $out);
echo "Done.\n";

?>