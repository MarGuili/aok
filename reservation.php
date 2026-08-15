<?php
session_start();
if (isset($_SESSION['availability_msg'])) {
    echo '<div class="alert alert-info">' . htmlspecialchars($_SESSION['availability_msg']) . '</div>';
    unset($_SESSION['availability_msg']);
}
include("includes/header.php");
require_once __DIR__ . '/includes/availability.php';
$page_title = "Reservation";
?>
<div id="main" class="container">
    <form action="" method="post">
        <p>
            <label for="checkInDate">Pick Check In Date:</label>
            <input type="text" id="checkInDate" name="checkInDate" value="<?php echo isset($_POST['checkInDate']) ? htmlspecialchars($_POST['checkInDate']) : ''; ?>" size="10">
        </p>
        <p>
            <label for="checkOutDate">Pick Check Out Date:</label>
            <input type="text" id="checkOutDate" name="checkOutDate" value="<?php echo isset($_POST['checkOutDate']) ? htmlspecialchars($_POST['checkOutDate']) : ''; ?>" size="10">
        </p>
        <p>
            <label for="roomType">Pick Room Type:</label>
            <select name="roomType" id="roomType">
                <?php
                if (!isset($mysqli)) {
                    include("DBConn.php");
                    connDB();
                }
                $query = "SELECT * FROM room_type";
                $result = mysqli_query($mysqli, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . htmlspecialchars($row['room_type_id']) . '">'
                            . htmlspecialchars($row['room_type_name']) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <input type="submit" name="submit" value="Make Reservation">
        </p>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $checkInDate = $_POST['checkInDate'] ?? '';
        $checkOutDate = $_POST['checkOutDate'] ?? '';
        $roomTypeId = intval($_POST['roomType'] ?? 0);
        if (!$checkInDate || !$checkOutDate || $roomTypeId <= 0) {
            echo "<p class='text-danger'>Please provide valid dates and room type.</p>";
        } else {
            $available = count_available_rooms($roomTypeId, $checkInDate, $checkOutDate);
            if ($available === false) {
                echo "<p class='text-danger'>Error checking availability.</p>";
            } elseif ($available > 0) {
                // create confirmation code
                $confirmNumber = 'LS-' . strtoupper(bin2hex(random_bytes(4)));
                $mysqli = connDB();
                $sql = "INSERT INTO reservation (room_type_id, begin_date, end_date, confirm_number) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($mysqli, $sql);
                mysqli_stmt_bind_param($stmt, "isss", $roomTypeId, $checkInDate, $checkOutDate, $confirmNumber);
                $ok = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                if ($ok) {
                    echo "<p class='text-success'>Reservation success. Your confirmation number is " . htmlspecialchars($confirmNumber) . "</p>";
                } else {
                    echo "<p class='text-danger'>Reservation failed. Try again.</p>";
                }
            } else {
                echo "<p class='text-warning'>Sorry, not available. Select a different date or a different room type</p>";
            }
        }
    }
    ?>
</div>
<?php include("includes/footer.php"); ?>