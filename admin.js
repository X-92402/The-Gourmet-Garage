document.addEventListener("DOMContentLoaded", function () {
  const reservationList = document.getElementById("reservation-list");
  const modal = document.getElementById("modify-modal");
  const closeModalButton = document.getElementById("close-modal");
  const modifyForm = document.getElementById("modify-form");
  const cancelButton = document.getElementById("cancel-button");

  // Load reservations from the server
  const loadReservations = () => {
    fetch("get_reservation.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch reservations.");
        }
        return response.json();
      })
      .then((data) => {
        reservationList.innerHTML = "";
        if (data.length === 0) {
          reservationList.innerHTML = "<p>No reservations found.</p>";
          return;
        }
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
            <button onclick="showModifyModal(${reservation.id})">Modify</button>
            <button onclick="deleteReservation(${reservation.id})">Delete</button>
          `;
          reservationList.appendChild(reservationItem);
        });
      })
      .catch((error) => {
        console.error(error);
        reservationList.innerHTML =
          "<p>Error loading reservations. Please try again later.</p>";
      });
  };

  // Show modal with reservation details for modification
  window.showModifyModal = function (reservationId) {
    console.log("Modify button clicked. ID:", reservationId); // Debugging log
    fetch(`get_reservation.php?id=${reservationId}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Failed to fetch reservation details: ${response.statusText}`);
        }
        return response.json();
      })
      .then((reservation) => {
        if (reservation.error) {
          console.error("Error from server:", reservation.error);
          alert(reservation.error);
        } else {
          // Populate the modal with the reservation data
          console.log("Fetched reservation:", reservation); // Debugging log
          document.getElementById("reservation-id").value = reservation.id;
          document.getElementById("name").value = reservation.name;
          document.getElementById("phone").value = reservation.phone;
          document.getElementById("persons").value = reservation.persons;
          document.getElementById("date").value = reservation.date;
          document.getElementById("time").value = reservation.time;
          document.getElementById("message").value = reservation.message;
          modal.style.display = "block"; // Show the modal
        }
      })
      .catch((error) => {
        console.error("Error fetching reservation:", error);
        alert("An error occurred while fetching reservation data.");
      });
  };

  // Delete reservation
  window.deleteReservation = function (reservationId) {
    if (confirm("Are you sure you want to delete this reservation?")) {
      fetch("delete_reservation.php", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: reservationId }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Failed to delete reservation.");
          }
          return response.text();
        })
        .then((message) => {
          alert(message);
          loadReservations();
        })
        .catch((error) => {
          console.error(error);
          alert("Failed to delete reservation.");
        });
    }
  };

  // Submit modified reservation data
  modifyForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const formData = new FormData(modifyForm);
    fetch("modify_reservation.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to update reservation.");
        }
        return response.text();
      })
      .then((data) => {
        if (data.startsWith("Error:")) {
          alert(data.replace("Error:", "").trim());
        } else if (data === "A reservation already exists for the selected date and time.") {
          alert(data);
        } else if (data === "Reservation time is outside of working hours.") {
          alert(data);
        } else {
          alert("Reservation updated successfully.");
          modal.style.display = "none";
          loadReservations();
        }
      })
      .catch((error) => {
        console.error(error);
        alert("Failed to update reservation.");
      });
  });

  // Close modal
  closeModalButton.addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Cancel button - Close modal without saving
  cancelButton.addEventListener("click", () => {
    modal.style.display = "none";
  });

  loadReservations();
});
