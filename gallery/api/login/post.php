<?php
require_once "../../endpoint.php";
require_once "../../exceptions.php";
require_once "../../models/login.php";
$ep = new Endpoint();
$login_input = new LoginInput();

try {
  $login_input->json_load($ep->body);
} catch (LoadingException $e) {
  $ep->send_code(400);
}

if (!$login_input->validate(true)) {
  $ep->send_code(400);
}

if (is_null($login)) {
  $ep->send_code(401);
};
$ep->send($login->json_dump());
