<?php
function img_resize($data, $target_width, $target_height)
{
  $target_width = (int) $target_width;
  $target_height = (int) $target_height;

  $img = imagecreatefromstring($data);
  [$width, $height] = getimagesizefromstring($data);
  $scale_x = $target_width / $width;
  $scale_y = $target_height / $height;
  if ($target_height <= 0) {
    $target_height = $height * $scale_x;
  } else if ($target_width <= 0) {
    $target_width = $width * $scale_y;
  }
  $img_resized = imagecreatetruecolor($target_width, $target_height);
  $ratio = $width / $height;

  $ratio_resize = $target_width / $target_height;
  if ($ratio_resize > $ratio) {
    $target_height = $target_width / $ratio;
  } else {
    $target_width = $target_height * $ratio;
  }

  imagecopyresampled(
    $img_resized,
    $img,
    0,
    0,
    0,
    0,
    $target_width,
    $target_height,
    $width,
    $height,
  );
  $stream = fopen('php://memory', 'r+');
  imagejpeg($img_resized, $stream); //TODO: Add dnamic format
  rewind($stream);
  $data = stream_get_contents($stream);
  return $data;
}
