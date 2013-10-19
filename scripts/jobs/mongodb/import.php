<?php

// get SQL data
$dbh = new PDO('mysql:dbname=kanbanlite;host=127.0.0.1', 'root', 'root'); 
$stmt = $dbh->prepare('
    SELECT
    c.id,
    c.title,
    c.description,
    cso.created_at,
    cso.owner_id AS owner,
    cso.status_id AS status
    FROM card c
    INNER JOIN card_status_owner cso ON c.id = cso.card_id
    WHERE cso.id IN (
       SELECT MAX(cso.id) FROM card_status_owner cso GROUP BY cso.card_id
    )
');

$stmt->execute();

// import SQL data to NoSQL
$mongoClient = new MongoClient();
$mongoDb = $mongoClient->kanbanlite;
$collection = $mongoDb->cards;
$collection->remove();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $collection->insert($row);
}
