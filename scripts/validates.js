function validate() {
    var startDate = document.getElementById('checkin').value;
    var endDate = document.getElementById('checkout').value;
    if (startDate && endDate) {
        if (startDate >= endDate) {
            alert("Check out date must be later than the check in date.");
            return false;
        }
    }
    return true;
}