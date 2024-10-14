document.addEventListener("DOMContentLoaded", () => {
  const adminForm = document.querySelector("#reservation-form");
  const reservationList = document.getElementById("reservation-list");

  // Fetch reservations from the server and display them
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
          '<tr><td colspan="6">Failed to load reservations. Please refresh the page.</td></tr>';
      });
  }

  // Display fetched reservations in the table
  function displayReservations(data) {
    if (data.length === 0) {
      reservationList.innerHTML =
        '<tr><td colspan="6">No current reservations.</td></tr>';
      return;
    }

    let tableHtml =
      "<tr><th>Name</th><th>Phone</th><th>Persons</th><th>Date</th><th>Time</th><th>Message</th></tr>";
    data.forEach((reservation) => {
      tableHtml += `
          <tr>
            <td>${reservation.name}</td>
            <td>${reservation.phone}</td>
            <td>${reservation.persons}</td>
            <td>${reservation.date}</td>
            <td>${reservation.time}</td>
            <td>${reservation.message}</td>
          </tr>
        `;
    });

    reservationList.innerHTML = tableHtml;
  }

  // Handle form submission and send data to the server
  function submitForm(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Collect form data
    const formData = new FormData(adminForm);

    // Send reservation data to the server using AJAX or Fetch API
    fetch("/submit_reservation.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          fetchReservations(); // Refresh the reservations list
        } else {
          console.error("Error submitting reservation:", data.error);
        }
      })
      .catch((error) => {
        console.error("Error submitting reservation:", error);
      });
  }

  adminForm.addEventListener("submit", submitForm);
  fetchReservations();
});
