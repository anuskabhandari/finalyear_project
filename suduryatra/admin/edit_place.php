<?php
session_start();

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

require '../backend/func.php';
$conn = dbConnect();

// Get place ID
if (!isset($_GET['id'])) die("No place ID provided.");
$place_id = intval($_GET['id']);

// Fetch place info
$stmt = $conn->prepare("SELECT * FROM place WHERE id=?");
$stmt->bind_param("i", $place_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Place not found.");
$place = $result->fetch_assoc();

// Fetch provinces
$provinces = $conn->query("SELECT id, title FROM provinces ORDER BY title ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Place</title>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
  #map { width: 100%; height: 400px; }
</style>
</head>
<body>
<div class="container">
<main class="flex-1 p-8">
<h2 class="text-3xl font-bold text-emerald-900 mb-6">Edit Place</h2>

<form action="../backend/add_place.php" method="POST" enctype="multipart/form-data" name="editPlaceForm">
<input type="hidden" name="place_id" value="<?= $place['id'] ?>">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<!-- Place Name -->
<div>
<label class="block mb-1 font-medium text-emerald-800">Place Name</label>
<input type="text" name="name" class="w-full p-3 border rounded" value="<?= htmlspecialchars($place['name'], ENT_QUOTES) ?>" required/>
</div>

<!-- Province -->
<div>
<label class="block mb-1 font-medium text-emerald-800">Province</label>
<select name="province" id="provinceSelect" onchange="getDistricts(this.value)" class="w-full p-3 border rounded" required>
<option value="" disabled>Select Province</option>
<?php while($p = $provinces->fetch_assoc()): ?>
<option value="<?= $p['id'] ?>" <?= $p['id'] == $place['province_id'] ? 'selected' : '' ?>><?= $p['title'] ?></option>
<?php endwhile; ?>
</select>
</div>

<!-- District -->
<div>
<label class="block mb-1 font-medium text-emerald-800">District</label>
<select name="district" id="districtSelect" onchange="getMunicipalities(this.value)" class="w-full p-3 border rounded" required></select>
</div>

<!-- Municipality -->
<div>
<label class="block mb-1 font-medium text-emerald-800">Municipality</label>
<select name="municipality" id="municipalitySelect" class="w-full p-3 border rounded" required></select>
</div>

<!-- Description -->
<div class="md:col-span-2">
<label class="block mb-1 font-medium text-emerald-800">Description</label>
<textarea name="description" class="w-full p-3 border rounded" rows="4" required><?= htmlspecialchars($place['description'], ENT_QUOTES) ?></textarea>
</div>

<!-- Latitude / Longitude -->
<div>
<label class="block mb-1 font-medium text-emerald-800">Latitude</label>
<input type="text" name="lat" id="lat" class="w-full p-3 border bg-gray-100" value="<?= $place['latitude'] ?>" readonly/>
</div>
<div>
<label class="block mb-1 font-medium text-emerald-800">Longitude</label>
<input type="text" name="lng" id="lng" class="w-full p-3 border bg-gray-100" value="<?= $place['longitude'] ?>" readonly/>
</div>

<!-- Image -->
<div class="md:col-span-2">
<label class="block mb-1 font-medium text-emerald-800">Place Image</label>
<input type="file" name="image" accept="image/*" class="w-full p-3 border rounded"/>
<?php if($place['image_path']): ?>
<img src="../images/<?= $place['image_path'] ?>" alt="Place Image" style="width:150px;height:100px;object-fit:cover;margin-top:5px;">
<?php endif; ?>
</div>

<!-- Map -->
<div class="md:col-span-2">
<label class="block mb-2 font-medium text-emerald-800">Select Location on Map</label>
<div id="map" class="rounded shadow border"></div>
</div>

</div>

<button type="submit" class="mt-6 bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2 rounded shadow">
<i class="fa-solid fa-pen-to-square mr-2"></i> Update Place
</button>
</form>
</main>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
// Initialize map
let lat = <?= $place['latitude'] ?: 28.3949 ?>;
let lng = <?= $place['longitude'] ?: 84.1240 ?>;
let map = L.map('map').setView([lat, lng], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

let marker = L.marker([lat, lng], { draggable:true }).addTo(map);

marker.on('dragend', function(e){
    const pos = marker.getLatLng();
    document.getElementById('lat').value = pos.lat.toFixed(6);
    document.getElementById('lng').value = pos.lng.toFixed(6);
});

map.on('click', function(e){
    marker.setLatLng(e.latlng);
    document.getElementById('lat').value = e.latlng.lat.toFixed(6);
    document.getElementById('lng').value = e.latlng.lng.toFixed(6);
});

// Geocoder search
L.Control.geocoder({ defaultMarkGeocode: false })
.on('markgeocode', function(e){
    const center = e.geocode.center;
    marker.setLatLng(center);
    map.setView(center, 15);
    document.getElementById('lat').value = center.lat.toFixed(6);
    document.getElementById('lng').value = center.lng.toFixed(6);
}).addTo(map);

// Dependent dropdowns
function getDistricts(provinceID){
    let districtDropDown = document.getElementById('districtSelect');
    let municipalityDropDown = document.getElementById('municipalitySelect');
    districtDropDown.innerHTML = '<option value="" disabled>Select District</option>';
    municipalityDropDown.innerHTML = '<option value="" disabled>Select Municipality</option>';
    municipalityDropDown.disabled = true;

    if(!provinceID) return;

    fetch(`../backend/province.php?id=${provinceID}`)
    .then(res => res.json())
    .then(districts => {
        districts.forEach(d => {
            districtDropDown.innerHTML += `<option value="${d.id}" ${d.id == <?= $place['district_id'] ?> ? 'selected' : ''}>${d.title}</option>`;
        });
        districtDropDown.disabled = false;
        getMunicipalities(districtDropDown.value);
    });
}

function getMunicipalities(districtID){
    let municipalityDropDown = document.getElementById('municipalitySelect');
    municipalityDropDown.innerHTML = '<option value="" disabled>Select Municipality</option>';
    municipalityDropDown.disabled = true;
    if(!districtID) return;

    fetch(`../backend/district.php?id=${districtID}`)
    .then(res => res.json())
    .then(municipalities => {
        municipalities.forEach(m => {
            municipalityDropDown.innerHTML += `<option value="${m.id}" ${m.id == <?= $place['municipality_id'] ?> ? 'selected' : ''}>${m.title}</option>`;
        });
        municipalityDropDown.disabled = false;
    });
}

// Initialize districts & municipalities
getDistricts(<?= $place['province_id'] ?>);
</script>
</body>
</html>
