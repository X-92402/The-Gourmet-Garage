document.addEventListener("DOMContentLoaded", function () {
  const reservationList = document.getElementById("reservation-list");
  const modal = document.getElementById("modify-modal");
  const closeModalButton = document.getElementById("close-modal");
  const modifyForm = document.getElementById("modify-form");

  // Function to load reservations dynamically
  const loadReservations = () => {
    fetch("get_reservations.php", {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch reservations");
        }
        return response.json();
      })
      .then((data) => {
        reservationList.innerHTML = ""; // Clear the existing list

        if (data.length === 0) {
          reservationList.innerHTML = "<p>No reservations found.</p>";
          return;
        }

        // Render each reservation in the list
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
            <button onclick="showModifyModal(${reservation.id})">Modify</button>
            <button onclick="deleteReservation(${reservation.id})">Delete</button>
          `;
          reservationList.appendChild(reservationItem);
        });
      })
      .catch((error) => {
        console.error("Error fetching reservations:", error);
        reservationList.innerHTML = "<p>Error loading reservations. Please try again later.</p>";
      });
  };

  // Function to open the modal and populate fields
  window.showModifyModal = function (reservationId) {
    fetch(`get_reservation.php?id=${reservationId}`, {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch reservation details");
        }
        return response.json();
      })
      .then((reservation) => {
        // Populate modal form with reservation details
        document.getElementById("reservation-id").value = reservation.id;
        document.getElementById("name").value = reservation.name;
        document.getElementById("phone").value = reservation.phone;
        document.getElementById("persons").value = reservation.persons;
        document.getElementById("date").value = reservation.date;
        document.getElementById("time").value = reservation.time;
        document.getElementById("message").value = reservation.message;

        modal.style.display = "block";
      })
      .catch((error) => {
        console.error("Error fetching reservation details:", error);
      });
  };

  // Function to handle form submission for updating reservations
  modifyForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const formData = new FormData(modifyForm);

    fetch("modify_reservation.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to update reservation");
        }
        return response.text();
      })
      .then(() => {
        alert("Reservation updated successfully!");
        modal.style.display = "none";
        loadReservations();
      })
      .catch((error) => {
        console.error("Error updating reservation:", error);
        alert("Failed to update reservation.");
      });
  });

  closeModalButton.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  // Delete function to remove reservation
  window.deleteReservation = function (id) {
    if (confirm("Are you sure you want to delete this reservation?")) {
      fetch("delete_reservation.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Failed to delete reservation");
          }
          return response.text();
        })
        .then(() => {
          alert("Reservation deleted successfully!");
          loadReservations();
        })
        .catch((error) => {
          console.error("Error deleting reservation:", error);
          alert("Failed to delete reservation.");
        });
    }
  };

  // Initial load of reservations
  loadReservations();
});
