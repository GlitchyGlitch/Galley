<?php
require_once __DIR__ . "/../../../endpoint.php";
$ep = new Endpoint();
$photos = $ep->repos->photoRepo->get_photos_by_user_id($ep->resource_id);
$ep->send($photos->json_dump());
