<?php
/**
 * Created by PhpStorm.
 * User: riese
 * Date: 10/12/2016
 * Time: 9:11 PM
 * Download and resize images to 200x200
 */
if(!isset($_GET['u']))
{
    exit(http_response_code(404)); //the browser console will show an error. we are in an img tag now.
}
$src = $_GET['u'];
header('Content-Type: image/png');
function get_scaled_dim_array($img,$max_w = 100, $max_h = NULL)
{
    if(is_null($max_h))
    {
        $max_h = $max_w;
    }
    list($img_w,$img_h) = getimagesize($img);
    $f = min($max_w/$img_w, $max_h/$img_h, 1);
    $w = round($f * $img_w);
    $h = round($f * $img_h);
    return array($w,$h);
}

list($o_w, $o_h) = getimagesize($src);
$newDims = get_scaled_dim_array($src, 200);

$im = imagecreatetruecolor(200,  200);		//A4
$white = imagecolorallocate($im, 0xFF,0XFF,0XFF);
imagefilledrectangle($im, 0, 0, 200, 200, $white);


$img_info = getimagesize($src);

switch($img_info['mime']) //call correct function according to mime type
{
    case 'image/jpeg':
        $originalImage = imagecreatefromjpeg( $src );
        break;

    case 'image/png':
        $originalImage = imagecreatefrompng( $src );
        break;

    case 'image/gif':
        $originalImage = imagecreatefromgif( $src );
        break;

    default:

        break;
}
//Resize it to 200 by 200
imagecopyresized($im, $originalImage, (200 - $newDims[0]) / 2, (200 - $newDims[1]) / 2, 0, 0,  $newDims[0], $newDims[1], $o_w, $o_h);


imagepng($im);
imagedestroy($im);
ob_end_flush();
exit;