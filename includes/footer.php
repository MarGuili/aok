<?php
// footer.php (this file outputs the aside and footer and required scripts)
?>
<aside>
<form id="availForm" action="javascript:void(0);" method="post">
<label for="checkin">Check In:</label>
<input type="text" id="checkin" name="checkin" autocomplete="off">
<label for="checkout">Check Out:</label>
<input type="text" id="checkout" name="checkout" autocomplete="off">
<label for="roomtype">Room Type</label>
<select id="roomtype" name="roomtype">
<?php
if(!isset($mysqli)){
    INCLUDE ("db/DBConn.php");
    connDB();
}
$query = "SELECT * FROM room_type";
$result = mysqli_query($mysqli, $query);
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo '<option value="'.htmlspecialchars($row['room_type_id']).'">'
            .htmlspecialchars($row['room_type_name']).'</option>';
    }
}
?>
</select>
<input type="submit" id="availBtn" value="Check Availability">
<div id="availResult" aria-live="polite" style="margin-top:8px;"></div>
</form>
</aside>

<footer>
<span>Copyright &copy; Like Stay Hotel
    2000-<?php echo date("Y"); ?> </span>
    <div id="socialgroup">
    <img class="social" src="facebook.png" alt="facebook">
    <img class="social" src="linkedin.png" alt="linked in">
    <img class="social" src="twitter.png" alt="twitter">
    <img class="social" src="instagram.png" alt="instagram">
    </div>
</footer>

<!-- scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="scripts/calendar.js"></script>
<script src="scripts/validates.js"></script>

<script>
$(function() {
    // AJAX availability check
    $('#availForm').on('submit', function(e){
        e.preventDefault();
        var checkin = $('#checkin').val();
        var checkout = $('#checkout').val();
        var roomtype = $('#roomtype').val();
        if (!checkin || !checkout) {
            $('#availResult').text('Please fill both dates.');
            return;
        }
        $.post('api/checkAvailability.php', {checkin: checkin, checkout: checkout, roomtype: roomtype}, function(resp){
            if (resp.success) {
                $('#availResult').text(resp.message + (resp.available ? (' ('+resp.available+' left)') : ''));
            } else {
                $('#availResult').text('Error: ' + resp.message);
            }
        }, 'json').fail(function(){ $('#availResult').text('Request failed.');});
    });
});
</script>
</body>
</html>