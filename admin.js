document.addEventListener("DOMContentLoaded", function() {
    const adminForm = document.querySelector("#reservation-form");

    // Function to load reservations
    const loadReservations = () => {
        fetch('admin.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const reservationList = document.getElementById('reservation-list');
            reservationList.innerHTML = '';

            if (data.length === 0) {
                reservationList.innerHTML = '<p>No reservations found.</p>';
                return;
            }

            data.forEach(reservation => {
                const reservationItem = document.createElement('div');
                reservationItem.classList.add('reservation-item');
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
        .catch(error => {
            console.error('Error fetching reservations:', error);
            const reservationList = document.getElementById('reservation-list');
            reservationList.innerHTML = '<p>Error loading reservations. Please try again later.</p>';
        });
    };

    // Load reservations on page load
    loadReservations();

    // Handle form submission
    if (adminForm) {
        adminForm.addEventListener("submit", (event) => {
            event.preventDefault();

            const formData = new FormData(adminForm);

            fetch('admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    adminForm.reset();
                    loadReservations();
                } else {
                    alert('Error adding reservation: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error submitting reservation:', error);
            });
        });
    } else {
        console.error('Reservation form not found.');
    }
});