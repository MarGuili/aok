<?php
// includes/availability.php
// Reusable availability logic. Returns integer available count or false on error.

// Use an absolute or relative path that works from the includes directory,
// or use __DIR__ to make it reliable regardless of where it's included from.
require_once __DIR__ . '/../db/DBConn.php';

function count_available_rooms($roomTypeId, $checkIn, $checkOut) {
    $mysqli = connDB();

    // Ensure the connection is valid before proceeding
    if (!$mysqli instanceof mysqli) {
        // Optional: error_log("Database connection failed in count_available_rooms");
        return false;
    }

    // get total rooms for type
    $sql = "SELECT total_room FROM room_type WHERE room_type_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "i", $roomTypeId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_room);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!isset($total_room)) return false;

    // Count overlapping reservations:
    $sql = "SELECT COUNT(*) FROM reservation
            WHERE room_type_id = ?
            AND NOT (end_date <= ? OR begin_date >= ?)";
    $stmt = mysqli_prepare($mysqli, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "iss", $roomTypeId, $checkIn, $checkOut);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $taken);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $taken = intval($taken);
    return intval($total_room) - $taken;
}
?>