// script.js
document.addEventListener("DOMContentLoaded", () => {
  fetchReservations();
});

function fetchReservations() {
  fetch('get_reservations.php') // PHP script to fetch reservations
      .then(response => {
          if (!response.ok) {
              throw new Error("Network response was not ok " + response.statusText);
          }
          return response.json();
      })
      .then(data => {
          displayReservations(data);
      })
      .catch(error => {
          console.error("Error fetching reservations:", error);
          document.getElementById('reservation-list').innerHTML = "Failed to load reservations.";
      });
}

function displayReservations(reservations) {
  const reservationList = document.getElementById('reservation-list');
  reservationList.innerHTML = ""; // Clear previous content

  if (reservations.length === 0) {
      reservationList.innerHTML = "<p>No current reservations.</p>";
      return;
  }

  const table = document.createElement('table');
  const headerRow = document.createElement('tr');
  headerRow.innerHTML = "<th>Name</th><th>Phone</th><th>Persons</th><th>Date</th><th>Time</th><th>Message</th>";
  table.appendChild(headerRow);

  reservations.forEach(reservation => {
      const row = document.createElement('tr');
      row.innerHTML = `<td>${reservation.name}</td><td>${reservation.phone}</td><td>${reservation.persons}</td><td>${reservation.date}</td><td>${reservation.time}</td><td>${reservation.message}</td>`;
      table.appendChild(row);
  });

  reservationList.appendChild(table);
}
