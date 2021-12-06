<?php
require_once "../../endpoint.php";
require_once "../../models/user.php";

$ep = new Endpoint();
$user_input = new UserInput();
$user_input->json_load($ep->body);
if ($user_input->role !== 'regular') {
  $ep->require_admin();
}

try {
  $user = $ep->repos->userRepo->insert($user_input);
} catch (ExistsException $e) {
  $ep->send_code(409);
}

$ep->send($user->json_dump());
