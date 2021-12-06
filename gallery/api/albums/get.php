<?php
require_once "../../endpoint.php";
$ep = new Endpoint();
$albums = $ep->repos->albumRepo->get_all();
$ep->send($albums->json_dump());
