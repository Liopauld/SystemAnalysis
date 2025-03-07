<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <h2>Request Trash Pickup</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php elseif (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <form action="controllers/submit_pickup.php" method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="waste_type" class="form-label">Waste Type</label>
                <select id="waste_type" name="waste_type" class="form-control" required>
                    <option value="" disabled selected>Select waste type</option>
                    <option value="Biodegradable">Biodegradable</option>
                    <option value="Recyclable">Recyclable</option>
                    <option value="Non-Biodegradable">Non-Biodegradable</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="pickup_day" class="form-label">Preferred Pickup Day</label>
                <select id="pickup_day" name="pickup_day" class="form-control" required>
                    <option value="" disabled selected>Select a day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="collection_time" class="form-label">Preferred Pickup Time</label>
                <input type="time" id="collection_time" name="collection_time" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="pickup_location" class="form-label">Pickup Location</label>
                <input type="text" id="pickup_location" name="pickup_location" class="form-control" required readonly placeholder="Select from map">
            </div>
        </div>

        <!-- Leaflet Map -->
        <div class="mb-3">
            <label class="form-label">Select Pickup Location on Map</label>
            <div id="map" style="width: 100%; height: 300px; border-radius: 8px;"></div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </div>

        <button type="submit" class="btn btn-primary w-100 p-3">🚛 Submit Pickup Request</button>
    </form>
</div>

<!-- Leaflet.js (Free Map API) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let defaultLocation = [14.5096, 121.0346]; // Default location (User-requested)

        let map = L.map('map').setView(defaultLocation, 15);
        
        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

        function updateLocation(lat, lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            // Reverse geocoding using OpenStreetMap's Nominatim API
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById("pickup_location").value = data.display_name;
                    } else {
                        document.getElementById("pickup_location").value = "Location not found";
                    }
                })
                .catch(() => {
                    document.getElementById("pickup_location").value = "Error fetching address";
                });
        }

        marker.on("dragend", function (e) {
            let position = marker.getLatLng();
            updateLocation(position.lat, position.lng);
        });

        updateLocation(defaultLocation[0], defaultLocation[1]); // Set initial location
    });
</script>

<?php include 'includes/footer.php'; ?>
