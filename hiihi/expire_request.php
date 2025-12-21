<?php
require 'db.php';

$room = $_GET['room'];

$db->query("UPDATE rooms SET status='empty', renter_id=NULL WHERE id=$room");

$db->query("UPDATE rental_requests SET status='expired' WHERE room_id=$room");

header("Location: manage_room.php");
