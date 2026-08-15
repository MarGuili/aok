<?php
require_once "db/DBConn.php";
connDB();
$mysqli = $GLOBALS['mysqli'];

$queries = [
    "INSERT INTO room_type (room_type_name, total_room) VALUES ('Single', 3)",
    "INSERT INTO room_type (room_type_name, total_room) VALUES ('Double', 3)",
    "INSERT INTO room_type (room_type_name, total_room) VALUES ('Family', 3)"
];

foreach ($queries as $q) {
    mysqli_query($mysqli, $q);
}
echo "<p>Rows inserted (duplicates possible on re-run).</p>";
mysqli_close($mysqli);
?>