document.addEventListener("DOMContentLoaded", function() {
    // Scroll event for the header
    window.addEventListener("scroll", function() {
        const header = document.querySelector("header");
        if (header) {
            if (window.scrollY > 0) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        }
    });

    // Menu Toggle function
    const menuToggle = document.getElementById("menuToggle");
    if (menuToggle) {
        menuToggle.addEventListener("click", function() {
            const navMenu = document.getElementById("navMenu");
            if (navMenu) {
                navMenu.classList.toggle("active");
            }
        });
    }

    // Get modal elements
    const loginPopup = document.getElementById("loginPopup");
    const forgotPasswordPopup = document.getElementById("forgotPasswordPopup");
    const signInBtn = document.getElementById("signInBtn");
    const signUpBtn = document.getElementById("signUpBtn");
    const mapLogIn = document.getElementById("mapLogIn");
    const closeButtons = document.querySelectorAll(".close-btn");
    const forgotPasswordLink = document.querySelector(".reset-password a");

    // Get forms and inputs
    const loginForm = document.getElementById("loginForm");
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const forgotPasswordForm = document.getElementById("forgotPasswordForm");
    const resetEmailInput = document.getElementById("resetEmail");

    // Error message elements
    const usernameError = document.getElementById("usernameError");
    const passwordError = document.getElementById("passwordError");
    const resetEmailError = document.getElementById("resetEmailError");

    // Email validation regex pattern
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Utility functions to disable and enable body scroll
    function disableBodyScroll() {
        document.body.style.overflow = 'hidden';
    }

    function enableBodyScroll() {
        document.body.style.overflow = '';
    }

    // Open login modal on button click
    if (signInBtn && loginPopup) {
        signInBtn.addEventListener("click", function(event) {
            event.preventDefault();
            loginPopup.style.display = "block";
            disableBodyScroll();
        });
    }

    if (signUpBtn && loginPopup) {
        signUpBtn.addEventListener("click", function(event){
            event.preventDefault();
            loginPopup.style.display = "block";
            disableBodyScroll();
        });
    }

    // Map page login modal on button click
    if (mapLogIn && loginPopup) {
        mapLogIn.addEventListener("click", function(event){
            event.preventDefault();
            loginPopup.style.display = "block";
            disableBodyScroll();
        });
    }

    // Open forgot password modal on link click
    if (forgotPasswordLink && loginPopup && forgotPasswordPopup) {
        forgotPasswordLink.addEventListener("click", function(event) {
            event.preventDefault();
            loginPopup.style.display = "none";
            forgotPasswordPopup.style.display = "block";
            disableBodyScroll();
        });
    }

    // Close modal on close button click
    closeButtons.forEach(function(closeBtn) {
        closeBtn.addEventListener("click", function() {
            const popup = closeBtn.closest('.popup-form');
            if (popup) {
                popup.style.display = "none";
                enableBodyScroll();
            }
        });
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", function(event) {
        if (event.target === loginPopup || event.target === forgotPasswordPopup) {
            event.target.style.display = "none";
            enableBodyScroll();
        }
    });

    // Login form validation
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            event.preventDefault();
            resetLoginValidation();
            let isValid = true;

            if (usernameInput && usernameError) {
                if (usernameInput.value.trim() === "") {
                    isValid = false;
                    usernameInput.classList.add("invalid");
                    usernameError.innerHTML = "<i class='bx bxs-error-circle'></i> Please enter your email address.";
                    usernameError.style.display = "block";
                } else if (!emailPattern.test(usernameInput.value.trim())) {
                    isValid = false;
                    usernameInput.classList.add("invalid");
                    usernameError.innerHTML = "<i class='bx bxs-error-circle'></i> Please enter a valid email address.";
                    usernameError.style.display = "block";
                }
            }

            if (passwordInput && passwordError) {
                if (passwordInput.value.trim() === "") {
                    isValid = false;
                    passwordInput.classList.add("invalid");
                    passwordError.innerHTML = "<i class='bx bxs-error-circle'></i> Please enter your password.";
                    passwordError.style.display = "block";
                }
            }

            if (isValid) {
                console.log("Login form is valid");
                loginForm.submit();
            }
        });
    }

    // Forgot password form validation
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener("submit", function(event) {
            event.preventDefault();
            resetForgotPasswordValidation();
            let isValid = true;

            if (resetEmailInput && resetEmailError) {
                if (resetEmailInput.value.trim() === "") {
                    isValid = false;
                    resetEmailInput.classList.add("invalid");
                    resetEmailError.innerHTML = "<i class='bx bxs-error-circle'></i> Please enter your email address.";
                    resetEmailError.style.display = "block";
                } else if (!emailPattern.test(resetEmailInput.value.trim())) {
                    isValid = false;
                    resetEmailInput.classList.add("invalid");
                    resetEmailError.innerHTML = "<i class='bx bxs-error-circle'></i> Please enter a valid email address.";
                    resetEmailError.style.display = "block";
                }
            }

            if (isValid) {
                console.log("Reset password form is valid");
                forgotPasswordForm.submit();
            }
        });
    }

    // Sign up form validation
    document.getElementById('signUpForm').addEventListener('submit', function(event) {
        let isValid = true;
        
        // Clear previous error messages and styles
        document.querySelectorAll('.error-message').forEach(function(error) {
            error.innerHTML = '';
        });
        document.querySelectorAll('.error-input').forEach(function(input) {
            input.classList.remove('error-input');
        });
        
        // Function to check and remove errors when input is valid
        function checkInputValidity(inputId, errorId, errorMessage, validationFunction) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            
            // Check if input is valid
            if (!validationFunction(input.value.trim())) {
                // Display error
                error.innerHTML = `<i class='bx bxs-error-circle'></i> ${errorMessage}`;
                input.classList.add('error-input');  // Apply error styles
                isValid = false;
            } else {
                // Remove error if input is valid
                error.innerHTML = '';
                input.classList.remove('error-input');  // Remove error styles
            }
        }
    
        // Validate fields
        checkInputValidity('firstNameInput', 'firstNameError', 'Please enter your first name.', value => value !== '');
        checkInputValidity('lastNameInput', 'lastNameError', 'Please enter your last name.', value => value !== '');
        checkInputValidity('emailInput', 'emailError', 'Please enter your email address.', value => value !== '');
        checkInputValidity('passwordInput', 'passwordError', 'Please enter your password.', value => value !== '');
        checkInputValidity('confirmPasswordInput', 'confirmPasswordError', 'Please confirm your password.', value => value !== '');
        checkInputValidity('phoneNum', 'phoneNumError', 'Please enter your phone number.', value => value !== '');
        checkInputValidity('zipCode', 'zipCodeError', 'Please enter your zip code.', value => value !== '');
        
        // Validate birthday (optional)
        const dobValue = document.getElementById('DOB').value.trim();
        if (dobValue && !/^\d{4}-\d{2}-\d{2}$/.test(dobValue)) {
            document.getElementById('DOBError').innerHTML = "<i class='bx bxs-error-circle'></i> Please enter a valid birthday formatted as YYYY-MM-DD.";
            document.getElementById('DOB').classList.add('error-input');  // Apply error styles
            isValid = false;
        } else {
            document.getElementById('DOBError').innerHTML = '';
            document.getElementById('DOB').classList.remove('error-input');  // Remove error styles
        }
    
        // If form is invalid, prevent submission
        if (!isValid) {
            event.preventDefault();
        }
    });
    
    // Add event listeners to inputs to remove error messages when the user types or focuses out
    document.querySelectorAll('input').forEach(function(input) {
        input.addEventListener('input', function() {
            const errorId = input.id + 'Error';
            const errorMessage = document.getElementById(errorId);
            if (input.value.trim() !== '') {
                errorMessage.innerHTML = '';
                input.classList.remove('error-input');  // Remove error styles
            }
        });
    });
    
    

    // Reset validation functions
    function resetLoginValidation() {
        if (usernameInput && usernameError) {
            usernameInput.classList.remove("invalid");
            usernameError.style.display = "none";
        }
        if (passwordInput && passwordError) {
            passwordInput.classList.remove("invalid");
            passwordError.style.display = "none";
        }
    }

    function resetForgotPasswordValidation() {
        if (resetEmailInput && resetEmailError) {
            resetEmailInput.classList.remove("invalid");
            resetEmailError.style.display = "none";
        }
    }

    // Redirect for Create Account Button 
    document.getElementById("createAccountBtn").addEventListener("click", function() {
        window.location.href = "profile/sign-up.html"; // 
    });

    // Listen for input events to clear error styles in login form
    document.querySelectorAll("#loginPopup input").forEach(function(input) {
        input.addEventListener("input", function() {
            input.classList.remove("invalid");
            const nextElement = input.nextElementSibling;
            if (nextElement && nextElement.classList.contains("error-message")) {
                nextElement.style.display = "none";
            }
        });
    });

    // Listen for input events to clear error styles in forgot password form
    document.querySelectorAll("#forgotPasswordPopup input").forEach(function(input) {
        input.addEventListener("input", function() {
            input.classList.remove("invalid");
            const nextElement = input.nextElementSibling;
            if (nextElement && nextElement.classList.contains("error-message")) {
                nextElement.style.display = "none";
            }
        });
    });

    // Menu sliding buttons setup
    const menuTiles = document.querySelector('.menu-tiles');
    const exploreMenu = document.querySelector('.explore-menu');
    if (menuTiles && exploreMenu) {
        const leftButton = document.createElement('button');
        leftButton.textContent = '<';
        leftButton.classList.add('slider-btn', 'left-btn');

        const rightButton = document.createElement('button');
        rightButton.textContent = '>';
        rightButton.classList.add('slider-btn', 'right-btn');

        exploreMenu.appendChild(leftButton);
        exploreMenu.appendChild(rightButton);

        leftButton.addEventListener('click', () => {
            menuTiles.scrollBy({ left: -150, behavior: 'smooth' });
        });

        rightButton.addEventListener('click', () => {
            menuTiles.scrollBy({ left: 150, behavior: 'smooth' });
        });
    }
});

function toggleSlideMenu() { 
    const slideMenu = document.getElementById("slideMenu");
    const menuToggleBtn = document.getElementById("menuToggleBtn");
    const icon = menuToggleBtn.querySelector("i");
    
    slideMenu.classList.toggle("open");
    menuToggleBtn.classList.toggle("active");
    
    // Toggle icon and background color
    if (slideMenu.classList.contains("open")) {
        icon.classList.replace("bx-menu", "bx-x"); // Change to "X" icon
    } else {
        icon.classList.replace("bx-x", "bx-menu"); // Change back to "Menu" icon
    }
}

// Function to toggle the tablet menu
function toggleTabletMenu() {
    const menu = document.getElementById('tabletMenu');
    const toggleBtn = document.getElementById('tableToggleBtn');
    const icon = toggleBtn.querySelector('i');

    // Toggle the 'open' class on the menu
    menu.classList.toggle('open');

    // Change the icon
    if (menu.classList.contains('open')) {
        icon.classList.remove('bx-menu');
        icon.classList.add('bx-x');
    } else {
        icon.classList.remove('bx-x');
        icon.classList.add('bx-menu');
    }
}

// Order Page Map Function Section
// let map, autocomplete;

// function initMap() {
//   // Initialize Google Map
//   map = new google.maps.Map(document.getElementById("map"), {
//     center: { lat: 39.8283, lng: -98.5795 },
//     zoom: 4,
//   });

//   // Autocomplete for Delivery Form
//   const input = document.getElementById("address");
//   autocomplete = new google.maps.places.Autocomplete(input);
//   autocomplete.bindTo("bounds", map);
// }

document.addEventListener("DOMContentLoaded", () => {
  const pickupBtn = document.getElementById("pickup");
  const deliveryBtn = document.getElementById("delivery");
  const pickupForm = document.getElementById("pickup-form");
  const deliveryForm = document.getElementById("delivery-form");

  pickupBtn.addEventListener("click", () => {
    pickupBtn.classList.add("active");
    deliveryBtn.classList.remove("active");
    pickupForm.classList.add("active");
    deliveryForm.classList.remove("active");
  });

  deliveryBtn.addEventListener("click", () => {
    deliveryBtn.classList.add("active");
    pickupBtn.classList.remove("active");
    deliveryForm.classList.add("active");
    pickupForm.classList.remove("active");
  });
});


document.addEventListener("DOMContentLoaded", function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiY29kZWJ5a2F5IiwiYSI6ImNtNDNlcmQ5cTA5M2QybHF3NzY5a3hzbTUifQ.wyWZw_7kDLFn_r59ZWoezA';
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [-74.5, 40],
        zoom: 9
    });

    map.addControl(new mapboxgl.NavigationControl());

    const marker = new mapboxgl.Marker()
        .setLngLat([-74.5, 40])
        .addTo(map);

    document.getElementById('search-btn').addEventListener('click', function() {
        const locationInput = document.getElementById('location').value;
        if (locationInput) {
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(locationInput)}.json?access_token=${mapboxgl.accessToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        const [lng, lat] = data.features[0].geometry.coordinates;
                        map.flyTo({ center: [lng, lat], zoom: 10 });
                        marker.setLngLat([lng, lat]);
                    } else {
                        alert('Location not found');
                    }
                })
                .catch(error => console.error('Error geocoding address:', error));
        }
    });

    window.getCurrentLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lng = position.coords.longitude;
                const lat = position.coords.latitude;

                fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${mapboxgl.accessToken}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.features && data.features.length > 0) {
                            const place = data.features.find(feature => feature.place_type.includes('place'));
                            if (place) {
                                const city = place.text;
                                const state = place.context.find(context => context.id.startsWith('region')).text;
                                document.getElementById('location').value = `${city}, ${state}`;
                            } else {
                                document.getElementById('location').value = 'Location not found';
                            }
                        }
                    })
                    .catch(error => console.error('Error reverse geocoding coordinates:', error));

                map.flyTo({ center: [lng, lat], zoom: 10 });
                marker.setLngLat([lng, lat]);
            }, function(error) {
                console.error('Error getting current location:', error);
                alert('Unable to retrieve your location');
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    };

    const addressInput = document.getElementById('address');
    const addressSuggestions = document.getElementById('address-suggestions');
    const rightSection = document.getElementById('right-section');
    const slideRight = document.getElementById('slide-right');
    const backBtn = document.getElementById('backBtn');

    addressInput.addEventListener('input', function() {
        const query = addressInput.value;
        if (query.length > 2) {
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${mapboxgl.accessToken}`)
                .then(response => response.json())
                .then(data => {
                    addressSuggestions.innerHTML = '';
                    if (data.features && data.features.length > 0) {
                        data.features.forEach(feature => {
                            const suggestionItem = document.createElement('li');
                            suggestionItem.textContent = feature.place_name;
                            suggestionItem.addEventListener('click', () => {
                                addressInput.value = feature.place_name;
                                addressSuggestions.innerHTML = '';
    
                                // Show the map with the selected address
                                const [lng, lat] = feature.geometry.coordinates;
                                map.flyTo({ center: [lng, lat], zoom: 10 });
                                marker.setLngLat([lng, lat]);
    
                                // Pass the selected address to the #passAddr element
                                document.getElementById('passAddr').textContent = feature.place_name;
    
                                // Ensure the map loads before transitioning
                                map.once('idle', () => {
                                    // Slide right content in and right section out
                                    rightSection.classList.add('hide-right-section');
                                    slideRight.classList.add('show-slide-right');
                                });
                            });
                            addressSuggestions.appendChild(suggestionItem);
                        });
                    }
                })
                .catch(error => console.error('Error fetching address suggestions:', error));
        } else {
            addressSuggestions.innerHTML = '';
        }
    });
    

    backBtn.addEventListener('click', () => {
        // Slide right content out and right section back in
        slideRight.classList.remove('show-slide-right');
        rightSection.classList.remove('hide-right-section');
    });
});

// 
// Get the elements
const asapButton = document.querySelector('.ASAP');
const timeSelect = document.querySelector('.time-select-options');

// Add an event listener to the ASAP button
asapButton.addEventListener('click', () => {
  // Remove border from the ASAP button
  asapButton.style.border = 'none';

  // Reset the dropdown background color
  timeSelect.style.backgroundColor = '#e5e7e880';
});

// Add an event listener to the dropdown
timeSelect.addEventListener('change', () => {
  // Change the dropdown background color to white
  timeSelect.style.backgroundColor = '#fff';
  timeSelect.style.border = '3px solid var(--color-vivid-red)'

  // Optional: Add border back to ASAP button
  asapButton.style.border = '3px solid #e5e7e880';
  asapButton.style.backgroundColor = '#e5e7e880';
  asapButton.style.boxShadow = 'none';
});

