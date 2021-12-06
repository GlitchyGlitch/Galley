<?php
require_once __DIR__ . "/../../../endpoint.php";
require_once __DIR__ . "/../../../exceptions.php";
require_once __DIR__ . "/../../../models/rate.php";

$ep = new Endpoint();
$ep->require_user();
$rate = new Rate();
try {
  $rate->json_load($ep->body);
} catch (LoadingException $e) {
  $ep->send_code(400);
}

$rate->photo_id = $ep->resource_id;
$rate->owner_id = $ep->user_id;

try {
  $rate = $ep->repos->rateRepo->insert($rate);
} catch (ExistsException $e) {
  $ep->send_code(409);
}

$ep->send($rate->json_dump());
