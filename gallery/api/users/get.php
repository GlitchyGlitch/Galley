<?php
require_once "../../endpoint.php";
$ep = new Endpoint();
if (!$ep->resource_id) {
  $ep->require_admin();
  $users = $ep->repos->userRepo->get_all();
  $ep->send($photos->json_dump());
} else {
  $user = $ep->repos->userRepo->get_by_id($ep->resource_id);
  $ep->send($user->json_dump());
}
