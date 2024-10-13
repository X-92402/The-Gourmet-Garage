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

// Form Submission Validation
function validateForm() {
  let name = document.getElementById("name").value;
  let phone = document.getElementById("phone").value;
  let persons = document.getElementById("persons").value;
  let date = document.getElementById("date").value;
  let time = document.getElementById("time").value;

  if (name === "") {
    alert("Name is required.");
    event.preventDefault();
    return false;
  }

  const phonePattern = /^[0-9]{10}$/;
  if (!phonePattern.test(phone)) {
    alert("Please enter a valid 10-digit phone number.");
    event.preventDefault();
    return false;
  }

  if (persons === "") {
    alert("Please select the number of persons.");
    event.preventDefault();
    return false;
  }

  if (date === "") {
    alert("Please select a date.");
    event.preventDefault();
    return false;
  }

  if (time === "") {
    alert("Please select a time.");
    event.preventDefault();
    return false;
  }

  if (message === "") {
    alert("Please enter a message.");
    event.preventDefault();
    return false;
  }

  alert("Form submitted successfully!");
  return true;
}

// Form submission with validation
document.querySelector('form').addEventListener('submit', function (event) {
  let username = document.querySelector('[name="username"]').value;
  let password = document.querySelector('[name="password"]').value;

  if (username === '' || password === '') {
      event.preventDefault();
      alert('Both fields are required');
  }
});

// Example AJAX function for reservations
function sendReservation() {
  fetch('/reservation_endpoint', {
      method: 'POST',
      body: new FormData(document.querySelector('form'))
  }).then(response => response.json())
    .then(data => alert('Reservation successful!'))
    .catch(error => console.error('Error:', error));
}
