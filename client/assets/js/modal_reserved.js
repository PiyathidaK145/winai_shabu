document.getElementById('checkBookingBtn').addEventListener('click', function () {
  const code = document.getElementById('bookingCodeInput').value.trim();

  if (code === "") {
    alert("กรุณากรอกรหัสการจอง");
    return;
  }

  fetch(`check_booking.php?code=${encodeURIComponent(code)}`)
    .then(response => response.json())
    .then(data => {
      if (data.found) {
        document.getElementById('reservedFirstName').textContent = data.first_name;
        document.getElementById('reservedLastName').textContent = data.last_name;
        document.getElementById('reservedGuests').textContent = data.guests;
        document.getElementById('bookingDetails').style.display = 'block';
        document.getElementById('bookingNotFound').style.display = 'none';
        document.getElementById('confirmReservationBtn').classList.remove('d-none');
      } else {
        document.getElementById('bookingDetails').style.display = 'none';
        document.getElementById('bookingNotFound').style.display = 'block';
        document.getElementById('confirmReservationBtn').classList.add('d-none');
      }
    });
});
