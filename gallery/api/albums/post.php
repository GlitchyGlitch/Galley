<?php

require_once "../../endpoint.php";
require_once "../../exceptions.php";

$ep = new Endpoint();
$ep->require_user();
$album = new Album();
try {
  $album->json_load($ep->body);
} catch (LoadingException $e) {
  $ep->send_code(400);
}
if (!$album->validate(true)) {
  $ep->send_code(400);
}

$album->owner_id = $ep->user_id;
try {
  $album = $ep->repos->albumRepo->insert($album);
} catch (ExistsException $e) {
  $ep->send_code(409);
}

$ep->send($album->json_dump());
