document.addEventListener("DOMContentLoaded", function () {
  const reservationList = document.getElementById("reservation-list");

  // Function to load reservations
  const loadReservations = () => {
    fetch("get_reservations.php", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        reservationList.innerHTML = ""; // Clear the existing content

        if (data.length === 0) {
          reservationList.innerHTML = "<p>No reservations found.</p>";
          return;
        }

        // Loop through the reservations and display each one
        data.forEach((reservation) => {
          const reservationItem = document.createElement("div");
          reservationItem.classList.add("reservation-item");
          reservationItem.innerHTML = `
            <p><strong>Name:</strong> ${reservation.name}</p>
            <p><strong>Phone:</strong> ${reservation.phone}</p>
            <p><strong>Persons:</strong> ${reservation.persons}</p>
            <p><strong>Date:</strong> ${reservation.date}</p>
            <p><strong>Time:</strong> ${reservation.time}</p>
            <p><strong>Message:</strong> ${reservation.message}</p>
            <p><strong>Created At:</strong> ${reservation.created_at}</p>
          `;
          reservationList.appendChild(reservationItem);
        });
      })
      .catch((error) => {
        console.error("Error fetching reservations:", error);
        reservationList.innerHTML =
          "<p>Error loading reservations. Please try again later.</p>";
      });
  };

  // Load reservations when the page loads
  loadReservations();
});
