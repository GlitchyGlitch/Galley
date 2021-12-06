<?php
require_once __DIR__ . "/../../../endpoint.php";
$ep = new Endpoint();
$comments = $ep->repos->commentRepo->get_by_photo_id($ep->resource_id);
$ep->send($comments->json_dump());
