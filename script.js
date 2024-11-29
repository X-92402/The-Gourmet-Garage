if (!window.fetch) {
  window.fetch = function (url, options) {
    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest();
      xhr.open(options.method || "GET", url, true);
      xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
          resolve(xhr.responseText);
        } else {
          reject(xhr.statusText);
        }
      };
      xhr.onerror = function () {
        reject(xhr.statusText);
      };
      xhr.send(options.body);
    });
  };
}

// PRELOADER
const preloader = document.querySelector(".preloader");

window.addEventListener("load", () => {
  preloader.classList.add("loaded");
  document.body.classList.add("loaded");
});

// HOME SLIDER SECTION
const slides = document.querySelectorAll(".slide");
let counter = 0;
let sliderInterval; // To handle auto-sliding interval
let interactionTimeout; // To track inactivity after user interaction

// Function to slide images
function slideImage() {
  slides.forEach((slide) => {
    slide.style.transform = `translateX(-${counter * 100}%)`;
  });
}

// Function to start auto-sliding
function startAutoSlide() {
  sliderInterval = setInterval(() => {
    counter++;
    if (counter >= slides.length) {
      counter = 0;
    }
    slideImage();
  }, 4000);
}

// Function to reset the auto-sliding after interaction
function resetAutoSlide() {
  clearInterval(sliderInterval); // Stop auto-slide immediately
  clearTimeout(interactionTimeout); // Clear any previously set timeout

  // Set timeout to resume auto-sliding after 5 seconds of no interaction
  interactionTimeout = setTimeout(() => {
    startAutoSlide(); // Restart auto-sliding
  }, 5000);
}

// Initialize the slider
slides.forEach((slide, index) => {
  slide.style.left = `${index * 100}%`;
  slide.style.transition = "transform 2s ease";
});

// Start the auto-sliding when the page loads
startAutoSlide();

// Next Slide Button Event
document.querySelector(".next_slide").addEventListener("click", () => {
  clearInterval(sliderInterval); // Stop auto-sliding on interaction
  counter++;
  if (counter >= slides.length) {
    counter = 0;
  }
  slideImage();
  resetAutoSlide(); // Restart auto-sliding after interaction
});

// Previous Slide Button Event
document.querySelector(".prev_slide").addEventListener("click", () => {
  clearInterval(sliderInterval); // Stop auto-sliding on interaction
  counter--;
  if (counter < 0) {
    counter = slides.length - 1;
  }
  slideImage();
  resetAutoSlide(); // Restart auto-sliding after interaction
});

// View Menu Button Event
document.querySelector(".home_menu").addEventListener("click", () => {
  document.querySelector("#menu").scrollIntoView({
    behavior: "smooth",
  });
});

// Header Links Scrolling Behavior
document.querySelectorAll(".nav-links a").forEach((link) => {
  link.addEventListener("click", (event) => {
    event.preventDefault();
    const sectionId = link.getAttribute("href");
    const section = document.querySelector(sectionId);
    const sectionRect = section.getBoundingClientRect();
    const scrollY = sectionRect.top + window.scrollY;
    window.scrollTo({
      top: scrollY,
      behavior: "smooth",
    });
  });
});

// Find Table Button Event
document.querySelectorAll(".find-table").forEach((button) => {
  button.addEventListener("click", () => {
    const contactSection = document.querySelector("#contact");
    const contactRect = contactSection.getBoundingClientRect();
    const scrollY = contactRect.top + window.scrollY;
    window.scrollTo({
      top: scrollY,
      behavior: "smooth",
    });
  });
});

// Get modal elements
const modal = document.getElementById("confirmationModal");
const span = document.getElementsByClassName("close")[0];
const confirmButton = document.getElementById("confirmButton");
const cancelButton = document.getElementById("cancelButton");
const reservationDetails = document.getElementById("reservationDetails");
const detailName = document.getElementById("detailName");
const detailPhone = document.getElementById("detailPhone");
const detailPersons = document.getElementById("detailPersons");
const detailDate = document.getElementById("detailDate");
const detailTime = document.getElementById("detailTime");
const detailMessage = document.getElementById("detailMessage");

// Reservation Form Submission (with alert)
document.querySelector("#reservationForm").addEventListener("submit", function (e) {
  e.preventDefault(); // Prevent form submission

  // Collect form data
  const formData = new FormData(this);
  const name = formData.get('name').trim();
  const phone = formData.get('phone').trim();
  const persons = formData.get('persons').trim();
  const date = formData.get('date').trim();
  const time = formData.get('time').trim();
  const message = formData.get('message').trim();

  // Validate form fields
  if (!name || !phone || !persons || !date || !time) {
    alert('Please fill in all required fields.');
    return;
  }

  // Validate reservation time against working hours
  const dayOfWeek = new Date(date).getDay(); // 0 (for Sunday) through 6 (for Saturday)
  const reservationTime = new Date(`${date}T${time}`);
  const lunchStart = new Date(`${date}T10:00`);
  const lunchEnd = new Date(`${date}T14:30`);
  const dinnerStart = new Date(`${date}T17:00`);
  const dinnerEnd = new Date(`${date}T22:00`);

  // Adjust end times to be one hour before closing
  const lunchEndAdjusted = new Date(lunchEnd.getTime() - 60 * 60 * 1000);
  const dinnerEndAdjusted = new Date(dinnerEnd.getTime() - 60 * 60 * 1000);

  if ((dayOfWeek >= 0 && dayOfWeek <= 5 && (reservationTime < lunchStart || reservationTime > lunchEndAdjusted) && (reservationTime < dinnerStart || reservationTime > dinnerEndAdjusted)) ||
      (dayOfWeek == 6 && (reservationTime < dinnerStart || reservationTime > dinnerEndAdjusted))) {
    alert('Reservation time is outside of working hours.');
    return;
  }

  // Display reservation details in the modal
  detailName.textContent = name;
  detailPhone.textContent = phone;
  detailPersons.textContent = persons;
  detailDate.textContent = date;
  detailTime.textContent = time;
  detailMessage.textContent = message;

  // Show the modal with fade effect
  modal.classList.add("show");
  modal.style.display = "block";
});

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.classList.remove("show");
  setTimeout(() => modal.style.display = "none", 500); // Wait for fade-out effect
}

// When the user clicks on cancel button, close the modal
cancelButton.onclick = function() {
  modal.classList.remove("show");
  setTimeout(() => modal.style.display = "none", 500); // Wait for fade-out effect
}

// When the user clicks on confirm button, submit the form
confirmButton.onclick = function() {
  // Collect form data
  const formData = new FormData(document.querySelector("#reservationForm"));

  // Send data to the PHP script
  fetch('insert_reservation.php', { // Updated PHP script path
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    // Show alert message based on server response
    if (data.startsWith('Error: ')) {
      alert(data.replace('Error: ', '')); // Remove "Error: " prefix
    } else {
      alert("Reservation successfully created!"); // Show success message
    }
    modal.classList.remove("show");
    setTimeout(() => modal.style.display = "none", 500); // Wait for fade-out effect
  })
  .catch(error => {
    alert("An error occurred: " + error.message); // Error alert for network issues
    modal.classList.remove("show");
    setTimeout(() => modal.style.display = "none", 500); // Wait for fade-out effect
  });
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.classList.remove("show");
    setTimeout(() => modal.style.display = "none", 500); // Wait for fade-out effect
  }
}
