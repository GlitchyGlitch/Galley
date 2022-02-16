<?php

require_once "../../endpoint.php";
require_once "../../exceptions.php";

$ep = new Endpoint();
$ep->require_user();

$photo = new Photo();
try {
  $photo->json_load($ep->body);
} catch (LoadingException $e) {
  $ep->send_code(400);
}
if (!$photo->validate(true)) {
  $ep->send_code(400);
}

$photo->owner_id = $ep->user_id;
$photo = $ep->repos->photoRepo->insert($photo);

$ep->send($photo->json_dump());
