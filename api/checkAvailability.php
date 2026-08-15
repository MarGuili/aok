<?php
// api/checkAvailability.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/availability.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST required']);
    exit;
}

$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$roomtype = intval($_POST['roomtype'] ?? 0);

if (!$checkin || !$checkout || $roomtype <= 0) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$available = count_available_rooms($roomtype, $checkin, $checkout);

if ($available === false) {
    echo json_encode(['success' => false, 'message' => 'Error checking availability']);
    exit;
}

if ($available > 0) {
    echo json_encode(['success' => true, 'available' => $available, 'message' => "Available: $available"]);
} else {
    echo json_encode(['success' => true, 'available' => 0, 'message' => "No rooms available"]);
}
?>