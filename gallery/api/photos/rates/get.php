<?php
require_once __DIR__ . "/../../../endpoint.php";
$ep = new Endpoint();
$rates = $ep->repos->rateRepo->get_by_photo_id($ep->resource_id);
$ep->send($rates->json_dump());
