<?php
require_once "../../endpoint.php";
$ep = new Endpoint();
// TODO: validate input
[$img, $mime] = $ep->repos->photoRepo->get_img_by_id($ep->resource_id, $ep->get["width"] ?? 0, $ep->get["height"] ?? 0);
$ep->set_mime($mime);
$ep->send($img);
