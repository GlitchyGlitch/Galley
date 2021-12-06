<?php
require_once "../../endpoint.php";
$ep = new Endpoint();
$photos = $ep->repos->photoRepo->get_all();
$ep->send($photos->json_dump());
