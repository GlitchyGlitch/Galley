<?php
require_once __DIR__ . "/../../../endpoint.php";
require_once __DIR__ . "/../../../exceptions.php";

$ep = new Endpoint();
$ep->require_user();
$comment = new Comment();

try {
  $comment->json_load($ep->body);
} catch (LoadingException $e) {
  $ep->send_code(400);
}

if (!$comment->validate(true)) {
  echo "chuj $ep->body";
  $ep->send_code(400);
}

$comment->photo_id = $ep->resource_id;
$comment->owner_id = $ep->user_id;
$comment = $ep->repos->commentRepo->insert($comment);

$ep->send($comment->json_dump());
