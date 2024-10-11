// Fetch reservations from database
fetch('admin.php')
  .then(response => response.json())
  .then(data => {
    const reservationList = document.getElementById("reservation-list");

    // Render each reservation
    data.forEach((reservation) => {
      const reservationItem = document.createElement("div");
      reservationItem.innerHTML = `
        <p><strong>${reservation.name}</strong> - ${reservation.people} people at ${new Date(reservation.time).toLocaleString()}</p>
      `;
      reservationList.appendChild(reservationItem);
    });
  })
  .catch(error => console.error('Error:', error));
