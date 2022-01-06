<?php
require_once "../../endpoint.php";
$ep = new Endpoint();
if (!$ep->resource_id) {
  $photos = $ep->repos->photoRepo->get_all();
  $ep->send($photos->json_dump());
} else {
  $photo = $ep->repos->photoRepo->get_by_id($ep->resource_id);
  $ep->send($photo->json_dump());
}
