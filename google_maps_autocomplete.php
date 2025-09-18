<?php
// google_maps_autocomplete.php - Google Maps Places Autocomplete Component
// Usage: Include this in your page like: <?php include 'google_maps_autocomplete.php';

// Configuration - Use the $key variable defined elsewhere
$googleMapsApiKey = $key; // Assumes $key is defined in header.php or config file

// Handle AJAX request for logging selected place
if (isset($_POST['selected_place'])) {
    ob_start();
    $selectedPlace = $_POST['selected_place'];
    error_log("Autocomplete selected: " . $selectedPlace);
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Place logged: ' . $selectedPlace]);
    exit;
}
?>

<!-- Google Maps Places Autocomplete Component -->
<div class="google-autocomplete-container" id="delivery-autocomplete" style="display: none;">
    <br>
    <label for="autocomplete-input" class="form-label small">Delivery Location in Lagos</label>
    <div class="input-group">
        <input type="text" class="form-control" id="autocomplete-input"
            placeholder="Enter delivery location in Lagos..." autocomplete="off">
        <button class="btn btn-outline-secondary" type="button" id="clear-search">Clear</button>
    </div>

    <!-- Suggestions dropdown -->
    <div id="autocomplete-suggestions" class="dropdown-menu w-100"
        style="display: none; max-height: 200px; overflow-y: auto;">
    </div>

    <!-- Show estimated delivery fee -->
    <div id="estimated-fee" class="mt-2 text-warning fw-bold"></div>

    <!-- Hidden input to store selected place -->
    <input type="hidden" id="selected-place" name="selected_place" value="<?php echo htmlspecialchars($selected_place); ?>">
</div>

<!-- Load Google Maps JavaScript API with Places and Distance Matrix -->
<script async
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsApiKey; ?>&libraries=places&callback=initAutocomplete"
    defer></script>

<style>
    .google-autocomplete-container {
        position: relative;
        margin-bottom: 1rem;
    }

    #autocomplete-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        border: 1px solid #ffc700;
        border-top: none;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .suggestion-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    .suggestion-item:hover,
    .suggestion-item.active {
        background-color: #ffc700;
        color: #000;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    #autocomplete-input:focus+#autocomplete-suggestions {
        display: block;
    }

    .input-group .btn-outline-secondary {
        border-left: none;
        background-color: #fff;
    }

    .input-group .btn-outline-secondary:hover {
        background-color: #ffc700;
        border-color: #ffc700;
        color: #000;
    }
</style>

<script>
    // Google Maps Autocomplete Component Script
    // Requires jQuery (assumes it's included in header.php)

    function initAutocomplete() {
        const apiKey = '<?php echo $googleMapsApiKey; ?>';
        const input = document.getElementById('autocomplete-input');
        const suggestions = document.getElementById('autocomplete-suggestions');
        const selectedPlaceInput = document.getElementById('selected-place');
        const clearBtn = document.getElementById('clear-search');
        let currentSuggestions = [];
        let selectedIndex = -1;

        if (!apiKey || apiKey === 'YOUR_GOOGLE_MAPS_API_KEY_HERE') {
            console.error('Please set your Google Maps API key in the component');
            input.placeholder = 'API Key not configured';
            input.disabled = true;
            document.getElementById('delivery-info').innerHTML = '';
            if (typeof window.updateShippingFee === 'function') {
                window.updateShippingFee(0);
            }
            $('#checkout-btn').prop('disabled', true);
            return;
        }

        if (!window.google || !window.google.maps || !window.google.maps.places) {
            console.error('Google Maps Places API not loaded');
            input.placeholder = 'Places API not available';
            input.disabled = true;
            document.getElementById('delivery-info').innerHTML = '';
            if (typeof window.updateShippingFee === 'function') {
                window.updateShippingFee(0);
            }
            $('#checkout-btn').prop('disabled', true);
            return;
        }

        // Initialize AutocompleteService and PlacesService
        const autocompleteService = new google.maps.places.AutocompleteService();
        const placesService = new google.maps.places.PlacesService(document.createElement('div'));

        // Debounced search function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Fetch suggestions from Google Places API, biased to Lagos
        function fetchSuggestions(query) {
            if (query.length < 2) {
                suggestions.style.display = 'none';
                document.getElementById('delivery-info').innerHTML = '<p style="color: white;">Enter your location</p>';
                $('#checkout-btn').prop('disabled', true);
                return;
            }

            autocompleteService.getPlacePredictions(
                {
                    input: query,
                    componentRestrictions: { country: 'ng' },
                    location: new google.maps.LatLng(6.5244, 3.3792), // Lagos center
                    radius: 50000 // 50km radius to cover Lagos
                },
                (predictions, status) => {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        currentSuggestions = predictions || [];
                        displaySuggestions(currentSuggestions);
                    } else {
                        console.error('Google Places API Error:', status);
                        suggestions.style.display = 'none';
                        document.getElementById('delivery-info').innerHTML = '<p style="color: red;">Error fetching locations</p>';
                        $('#checkout-btn').prop('disabled', true);
                    }
                }
            );
        }

        // Display suggestions in dropdown
        function displaySuggestions(predictions) {
            suggestions.innerHTML = '';
            if (predictions.length === 0) {
                suggestions.style.display = 'none';
                document.getElementById('delivery-info').innerHTML = '<p style="color: white;">No locations found</p>';
                $('#checkout-btn').prop('disabled', true);
                return;
            }

            predictions.forEach((prediction, index) => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.innerHTML = `
                <div><strong>${prediction.structured_formatting.main_text}</strong></div>
                <small class="text-muted">${prediction.structured_formatting.secondary_text}</small>
                <input type="hidden" value="${prediction.place_id}">
            `;
                div.addEventListener('click', () => selectSuggestion(prediction));
                div.addEventListener('mouseenter', () => {
                    document.querySelectorAll('.suggestion-item').forEach(item => item.classList.remove('active'));
                    div.classList.add('active');
                    selectedIndex = index;
                });
                suggestions.appendChild(div);
            });

            suggestions.style.display = 'block';
        }

        // Select a suggestion and calculate distance
        function selectSuggestion(prediction) {
            input.value = prediction.structured_formatting.main_text;
            selectedPlaceInput.value = JSON.stringify({
                place_id: prediction.place_id,
                description: prediction.description,
                main_text: prediction.structured_formatting.main_text,
                secondary_text: prediction.structured_formatting.secondary_text
            });

            // Log the selected place via AJAX
            logSelectedPlace(prediction);

            // Get coordinates and calculate distance
            document.getElementById('delivery-info').innerHTML = '<p style="color: white;">Calculating shipping fee...</p>';
            placesService.getDetails({ placeId: prediction.place_id, fields: ['geometry'] }, (place, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK && place.geometry && place.geometry.location) {
                    const destination = place.geometry.location;
                    calculateDistance(destination);
                } else {
                    console.error('Failed to get place details:', status);
                    document.getElementById('delivery-info').innerHTML = '<p style="color: red;">Error fetching place details</p>';
                    if (typeof window.updateShippingFee === 'function') {
                        window.updateShippingFee(0);
                    }
                    $('#checkout-btn').prop('disabled', true);
                }
            });

            suggestions.style.display = 'none';
            document.querySelectorAll('.suggestion-item').forEach(item => item.classList.remove('active'));
            selectedIndex = -1;
        }

        // Calculate distance using Distance Matrix API
        function calculateDistance(destination) {
            const origin = new google.maps.LatLng(6.5977624, 3.3452563); // Store location
            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination],
                    travelMode: 'DRIVING',
                    unitSystem: google.maps.UnitSystem.METRIC
                },
                (response, status) => {
                    if (status === 'OK' && response.rows[0].elements[0].status === 'OK') {
                        const distance = response.rows[0].elements[0].distance.value / 1000; // Convert to km
                        console.log('Distance to destination:', distance, 'km');
                        calculateShippingCost(distance);
                    } else {
                        console.error('Distance Matrix API Error:', status, response);
                        document.getElementById('delivery-info').innerHTML = '<p style="color: red;">Error calculating distance</p>';
                        if (typeof window.updateShippingFee === 'function') {
                            window.updateShippingFee(0);
                        }
                        $('#checkout-btn').prop('disabled', true);
                    }
                }
            );
        }

        // Calculate shipping cost based on delivery_rates
        function calculateShippingCost(distance) {
            $.ajax({
                url: 'fetch_delivery_rates.php',
                type: 'GET',
                dataType: 'json',
                success: function (rates) {
                    if (rates.error) {
                        console.error('Error from fetch_delivery_rates.php:', rates.error);
                        document.getElementById('estimated-fee').innerHTML = `<p style="color: red;">Error fetching delivery rates: ${rates.error}</p>`;
                        document.getElementById('delivery-info').innerHTML = '';
                        if (typeof window.updateShippingFee === 'function') {
                            window.updateShippingFee(0);
                        }
                        $('#checkout-btn').prop('disabled', true);
                        return;
                    }

                    let cost = 0;
                    if (distance < 5) {
                        cost = rates['Below 5km'] || 0;
                    } else if (distance >= 5 && distance <= 10) {
                        cost = rates['5 - 10km'] || 0;
                    } else if (distance > 10 && distance <= 20) {
                        cost = rates['10 - 20km'] || 0;
                    } else if (distance > 20 && distance <= 50) {
                        cost = rates['20 - 50km'] || 0;
                    } else {
                        cost = rates['Above 50km'] || 0;
                    }

                    console.log('Shipping cost: ₦' + cost);
                    document.getElementById('estimated-fee').innerHTML = `<p>Estimated Delivery Cost: ₦${cost}</p>`;
                    document.getElementById('delivery-info').innerHTML = `<p>Distance: ${distance.toFixed(2)} km</p>`;
                    if (typeof window.updateShippingFee === 'function') {
                        window.updateShippingFee(cost);
                    } else {
                        console.error('window.updateShippingFee not defined');
                        $('#checkout-btn').prop('disabled', true);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching delivery rates:', error, 'Response:', xhr.responseText);
                    document.getElementById('estimated-fee').innerHTML = `<p style="color: red;">Error fetching delivery rates: ${error}</p>`;
                    document.getElementById('delivery-info').innerHTML = '';
                    if (typeof window.updateShippingFee === 'function') {
                        window.updateShippingFee(0);
                    }
                    $('#checkout-btn').prop('disabled', true);
                }
            });
        }

        // Log selected place to server
        function logSelectedPlace(prediction) {
            const selectedPlaceData = {
                place_id: prediction.place_id,
                description: prediction.description,
                main_text: prediction.structured_formatting.main_text,
                secondary_text: prediction.structured_formatting.secondary_text
            };

            $.ajax({
                url: 'google_maps_autocomplete.php',
                type: 'POST',
                data: {
                    selected_place: JSON.stringify(selectedPlaceData)
                },
                dataType: 'json',
                success: function (response) {
                    console.log('Place logged successfully:', response);
                },
                error: function (xhr, status, error) {
                    console.error('Error logging place:', error, 'Response:', xhr.responseText);
                }
            });
        }

        // Input event handler
        const debouncedSearch = debounce(fetchSuggestions, 300);
        input.addEventListener('input', function (e) {
            debouncedSearch(e.target.value);
        });

        // Keyboard navigation
        input.addEventListener('keydown', function (e) {
            const items = document.querySelectorAll('.suggestion-item');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateKeyboardSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateKeyboardSelection(items);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                const selectedItem = items[selectedIndex];
                if (selectedItem) {
                    const placeId = selectedItem.querySelector('input[type="hidden"]').value;
                    const prediction = currentSuggestions.find(p => p.place_id === placeId);
                    if (prediction) {
                        selectSuggestion(prediction);
                    }
                }
            } else if (e.key === 'Escape') {
                suggestions.style.display = 'none';
                selectedIndex = -1;
                document.getElementById('delivery-info').innerHTML = '';
                if (typeof window.updateShippingFee === 'function') {
                    window.updateShippingFee(0);
                }
                $('#checkout-btn').prop('disabled', true);
            }
        });

        function updateKeyboardSelection(items) {
            items.forEach(item => item.classList.remove('active'));
            if (selectedIndex >= 0 && selectedIndex < items.length) {
                items[selectedIndex].classList.add('active');
                items[selectedIndex].scrollIntoView({ block: 'nearest' });
            }
            suggestions.scrollTop = 0;
        }

        // Clear button functionality
        clearBtn.addEventListener('click', function () {
            input.value = '';
            selectedPlaceInput.value = '';
            suggestions.style.display = 'none';
            selectedIndex = -1;
            currentSuggestions = [];
            document.getElementById('delivery-info').innerHTML = '';
            document.getElementById('estimated-fee').innerHTML = '';
            if (typeof window.updateShippingFee === 'function') {
                window.updateShippingFee(0);
            }
            $('#checkout-btn').prop('disabled', true);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = 'none';
                selectedIndex = -1;
            }
        });

        // Focus input to show suggestions if already typing
        input.addEventListener('focus', function () {
            if (input.value.length >= 2 && currentSuggestions.length > 0) {
                displaySuggestions(currentSuggestions);
            } else {
                document.getElementById('delivery-info').innerHTML = '<p style="color: white;">Enter your location</p>';
                $('#checkout-btn').prop('disabled', true);
            }
        });
    }
</script>