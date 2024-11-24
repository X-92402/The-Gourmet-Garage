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
            <button onclick="modifyReservation(${reservation.id})">Modify</button>
            <button onclick="deleteReservation(${reservation.id})">Delete</button>
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

  // Function to modify reservation
  window.modifyReservation = function(id) {
    fetch(`get_reservation.php?id=${id}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        // Populate the form with the reservation data
        document.getElementById("name").value = data.name;
        document.getElementById("phone").value = data.phone;
        document.getElementById("persons").value = data.persons;
        document.getElementById("date").value = data.date;
        document.getElementById("time").value = data.time;
        document.getElementById("message").value = data.message;
        document.getElementById("reservation-id").value = id; // Hidden input for ID
      })
      .catch((error) => console.error("Error fetching reservation:", error));
  };

  // Function to delete reservation
  window.deleteReservation = function(id) {
    if (confirm("Are you sure you want to delete this reservation?")) {
      fetch("delete_reservation.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: id }),
      })
        .then((response) => response.text())
        .then((data) => {
          alert(data);
          loadReservations(); // Refresh the reservation list
        })
        .catch((error) => {
          console.error("Error deleting reservation:", error);
        });
    }
  };

  // Load reservations when the page loads
  loadReservations();
});