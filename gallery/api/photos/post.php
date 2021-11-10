<?php


require_once "../../endpoint.php";
require_once "../../exceptions.php";

$ep = new Endpoint();
$photo = new Photo();
try {
  $photo->json_load(file_get_contents("php://input"));
} catch (LoadingException $e) {
  $ep->send_code(400);
}

$photo->owner = $ep->user_id;
$photo = $ep->repos->photosRepo->insert($photo);

$ep->send($photo->json_dump());
