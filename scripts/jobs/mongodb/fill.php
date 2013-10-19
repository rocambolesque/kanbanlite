<?php

$mysqli = new mysqli("localhost", "root", "root", "kanbanlite");

if ($mysqli->connect_errno) {
   printf("Connect failed: %s\n", $mysqli->connect_error);
   exit();
}

$err = false;
for ($i = 0 ; $i < 10000 ; $i++) {
   if (!$mysqli->query('INSERT INTO card (title, description) VALUES ("title'.$i.'", "description'.$i.'")')) {
      $err = true;
      break;
   }
}

if ($err) {
   echo $mysqli->errno . " " . $mysqli->error;
}

$err = false;
for ($i = 1 ; $i < 10000 ; $i++) {
   if (!$mysqli->query('INSERT INTO owner (name) VALUES ("owner'.$i.'")')) {
      $err = true;
      break;
   }
}

if ($err) {
   echo $mysqli->errno . " " . $mysqli->error;
}

$err = false;
for ($i = 1 ; $i < 10000 ; $i++) {
   $card = rand(1, 9999);
   $owner = rand(1, 9999);
   $status = rand(1, 3);
   $date = date('Y-m-d H:i:s', rand(strtotime('2000-01-01 12:00:00'), strtotime('2013-01-01 12:00:00')));
   if (!$mysqli->query('INSERT INTO card_status_owner (card_id, owner_id, status_id, created_at) VALUES ('.$card.', '.$owner.', '.$status.', "'.$date.'")')) {
      $err = true;
      break;
   }
}

if ($err) {
   echo $mysqli->errno . " " . $mysqli->error;
}
