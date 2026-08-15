
<?php
// DBConn.php
// Shared DB connection and a couple helper functions.

function connDB()
{
    global $mysqli;
    $host = getenv('MYSQL_HOST') ?: 'localhost';
    $mysqli = mysqli_connect($host, "root", "", "hotelDB");

    if (mysqli_connect_errno()) {
        printf("Connection failed: %s\n", mysqli_connect_error());
        exit();
    }
}

// safe wrapper for prepared statements that return a single value (COUNT, total_room, etc.)
function fetch_single_value($mysqli, $sql, $types = "", $params = []) {
    $stmt = mysqli_prepare($mysqli, $sql);
    if ($stmt === false) return false;
    if ($types !== "") {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($res);
    mysqli_stmt_close($stmt);
    return $row ? array_values($row)[0] : null;
}
?>