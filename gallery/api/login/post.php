<?php
require_once "../../endpoint.php";
require_once "../../models/login.php";
$ep = new Endpoint();
$login_input = new LoginInput();
$login_input->json_load($ep->body);
$login = $ep->repos->authRepo->authenticate($login_input);
if (is_null($login)) {
  $ep->send_code(401);
};
$ep->send($login->json_dump());
