<?php
require("desk.class.php");

header('Content-Type: image/png');

$desk = new Desk();

$desk->Draw();


?>