document.addEventListener("DOMContentLoaded", () => {
  const adminForm = document.querySelector("form");
  const reservationList = document.getElementById("reservationList");

  function fetchReservations() {
    fetch("/get_reservations.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        displayReservations(data);
      })
      .catch((error) => {
        console.error("Error fetching reservations:", error);
        reservationList.innerHTML =
          '<tr><td colspan="5">Failed to load reservations. Please refresh the page.</td></tr>';
      });
  }

  function displayReservations(data) {
    if (data.length === 0) {
      reservationList.innerHTML =
        '<tr><td colspan="5">No current reservations.</td></tr>';
      return;
    }

    let tableHtml =
      "<tr><th>Name</th><th>Phone</th><th>Persons</th><th>Date</th><th>Time</th></tr>";
    data.forEach((reservation) => {
      tableHtml += `
          <tr>
            <td>${reservation.name}</td>
            <td>${reservation.phone}</td>
            <td>${reservation.persons}</td>
            <td>${reservation.date}</td>
            <td>${reservation.time}</td>
          </tr>
        `;
    });

    reservationList.innerHTML = tableHtml;
  }

  function submitForm(event) {
    event.preventDefault();


    // Send reservationData to the server using AJAX or Fetch API
  }

  adminForm.addEventListener("submit", submitForm);
  fetchReservations();
});
