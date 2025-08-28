<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}

require '../backend/func.php';
$conn = dbConnect();

// Fetch all active places
$places = $conn->query("
    SELECT p.*, 
           pr.title AS province_name, 
           d.title AS district_name, 
           m.title AS municipality_name
    FROM place p
    LEFT JOIN provinces pr ON p.province_id = pr.id
    LEFT JOIN districts d ON p.district_id = d.id
    LEFT JOIN municipalities m ON p.municipality_id = m.id
    WHERE p.status='active'
    ORDER BY p.id DESC
");

// Fetch provinces for dropdown
$provinces = $conn->query("SELECT id, title FROM provinces ORDER BY title ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Places</title>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
  #map { width: 100%; height: 400px; }
  .map-container { position: relative; }
</style>
</head>
<body>
<div class="container">
<nav class="sidebar" id="sidebar">
  <div class="logo">Admin</div> 
<div class="nav-links"> 
  <a href="#"> 
    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg> 
    <span>Dashboard</span> 
  </a> 
  <a href="manage_users.php"> 
    <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2 0-6 1-6 3v2h12v-2c0-2-4-3-6-3zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.06 1.97 3.45v2h6v-2c0-2-4-3-6-3z"/></svg> 
    <span>Manage Users</span> 
  </a> 
  <a href="manage_places.php" class="active"> 
    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg> 
    <span>Manage Places</span> 
  </a> 
  <a href="manage_packages.php"> 
    <svg viewBox="0 0 24 24"><path d="M20 8V6h-4V4h-4v2H4v2H2v4h2v2h4v2h4v-2h4v-2h2v-4h-2z"/></svg> 
    <span>Manage Packages</span> 
  </a> 
  <a href="manage_bookings.php"> 
    <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 21V8h14v13H5z"/></svg> 
    <span>Manage Bookings</span> 
  </a> 
  <a href="adminmanage_reviews.php"> 
    <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21 16.54 13.97 22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg> 
    <span>Manage Reviews</span> 
  </a> 
  <!-- New Manage Operators Link --> 
  <a href="manage_touroperator.php"> 
    <svg viewBox="0 0 24 24"> 
      <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2 0-6 1-6 3v2h12v-2c0-2-4-3-6-3zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.06 1.97 3.45v2h6v-2c0-2-4-3-6-3z"/> 
    </svg> 
    <span>Manage Operators</span> 
  </a> 
  <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?')"> 
    <svg viewBox="0 0 24 24"> 
      <path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/> 
    </svg> 
    <span>Logout</span> 
  </a> 
</div> 
<button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">&#9776;</button>

    <!-- Sidebar content (same as your code) -->
</nav>

<main class="flex-1 p-8">
<h2 class="text-3xl font-bold text-emerald-900 mb-6">Manage Places</h2>

<!-- Places Table -->
<div class="bg-white rounded shadow p-6 overflow-x-auto mb-6">
<table class="min-w-full text-left border-collapse">
<thead>
<tr class="bg-emerald-100 text-emerald-900">
<th class="p-3 border-b">Place Name</th>
<th class="p-3 border-b">Province</th>
<th class="p-3 border-b">District</th>
<th class="p-3 border-b">Municipality</th>
<th class="p-3 border-b">Image</th>
<th class="p-3 border-b text-center">Actions</th>
</tr>
</thead>
<tbody>
<?php while($row = $places->fetch_assoc()): ?>
<tr class="hover:bg-gray-50 transition cursor-pointer place-row"
    data-id="<?= $row['id'] ?>"
    data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
    data-description="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>"
    data-province="<?= $row['province_id'] ?? '' ?>"
    data-district="<?= $row['district_id'] ?? '' ?>"
    data-municipality="<?= $row['municipality_id'] ?? '' ?>"
    data-lat="<?= $row['latitude'] ?? '' ?>"
    data-lng="<?= $row['longitude'] ?? '' ?>"
>
<td class="p-3 border-b"><?= $row['name'] ?></td>
<td class="p-3 border-b"><?= $row['province_name'] ?></td>
<td class="p-3 border-b"><?= $row['district_name'] ?></td>
<td class="p-3 border-b"><?= $row['municipality_name'] ?></td>
<td class="p-3 border-b">
<?php if($row['image_path']): ?>
<img src="../images/<?= $row['image_path'] ?>" alt="<?= $row['name'] ?>" style="width:80px;height:50px;object-fit:cover;">
<?php endif; ?>
</td>
<td class="p-3 border-b text-center">
    <!-- Direct Edit Link -->
    <a href="edit_place.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">
        <i class="fa-solid fa-pen-to-square"></i> Edit
    </a>

    <!-- Delete Link -->
    <a href="soft_delete_place.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this place?')">
        <i class="fa-solid fa-trash"></i> Delete
    </a>
</td>

</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<!-- Add / Update Form -->
<form name="placeForm" action="../backend/add_place.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="place_id" id="place_id" value="">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<!-- Place Name -->
<div>
<label class="block mb-1 font-medium text-emerald-800">Place Name</label>
<input type="text" name="name" id="placeName" class="w-full p-3 border rounded" placeholder="Enter place name"/>
</div>

<!-- Province -->
<div>
<label class="block mb-1 font-medium text-emerald-800">Province</label>
<select name="province" id="provinceSelect" onchange="getDistricts(this.value,'placeForm')" class="w-full p-3 border rounded">
<option value="" selected disabled>Select Province</option>
<?php while($p = $provinces->fetch_assoc()): ?>
<option value="<?= $p['id'] ?>"><?= $p['title'] ?></option>
<?php endwhile; ?>
</select>
</div>

<!-- District -->
<div> 
<label class="block mb-1 font-medium text-emerald-800">District</label> 
<select name="district" id="districtSelect" onchange="getMunicipalities(this.value,'placeForm')" class="w-full p-3 border rounded" disabled> 
<option value="" selected disabled>Select District</option> 
</select> 
</div>

<!-- Municipality -->
<div> 
<label class="block mb-1 font-medium text-emerald-800">Municipality</label>
<select name="municipality" id="municipalitySelect" class="w-full p-3 border rounded" disabled> 
<option value="" selected disabled>Select Municipality</option> 
</select> 
</div>

<!-- Description -->
<div class="md:col-span-2">
<label class="block mb-1 font-medium text-emerald-800">Description</label>
<textarea name="description" id="placeDescription" class="w-full p-3 border rounded" rows="4" placeholder="Enter place description"></textarea>
</div>

<!-- Latitude / Longitude -->
<div>
<label class="block mb-1 font-medium text-emerald-800">Latitude</label>
<input id="lat" name="lat" type="text" class="w-full p-3 border bg-gray-100" readonly/>
</div>
<div>
<label class="block mb-1 font-medium text-emerald-800">Longitude</label>
<input id="lng" name="lng" type="text" class="w-full p-3 border bg-gray-100" readonly/>
</div>

<!-- Image Upload -->
<div class="md:col-span-2">
<label class="block mb-1 font-medium text-emerald-800">Place Image</label>
<input type="file" name="image" accept="image/*" class="w-full p-3 border rounded"/>
</div>

<!-- Map with Search -->
<div class="md:col-span-2">
<label class="block mb-2 font-medium text-emerald-800">Select Location on Map (Search places in Nepal)</label>
<div class="map-container">
<div id="map" class="rounded shadow border"></div>
</div>
</div>

</div>
<button type="submit" id="submitBtn" class="mt-6 bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2 rounded shadow">
<i class="fa-solid fa-plus mr-2"></i> Add / Update Place
</button>
</form>

</main>
</div>

<!-- Leaflet & Dependent Dropdown JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
let map = L.map('map').setView([28.3949, 84.1240], 7); // Nepal center
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);

let marker = L.marker([28.3949, 84.1240], {draggable:true}).addTo(map);
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

// Search box
L.Control.geocoder({ defaultMarkGeocode: false })
.on('markgeocode', function(e) {
    const center = e.geocode.center;
    marker.setLatLng(center);
    map.setView(center, 15);
    document.getElementById('lat').value = center.lat.toFixed(6);
    document.getElementById('lng').value = center.lng.toFixed(6);
}).addTo(map);

// Dependent dropdown functions
function getDistricts(provinceID, formName) {
    let districtDropDown = document.forms[formName].district;
    let municipalityDropDown = document.forms[formName].municipality;

    if (!provinceID) {
        districtDropDown.disabled = true;
        districtDropDown.innerHTML = '<option value="" selected disabled>Select District</option>';
        municipalityDropDown.disabled = true;
        municipalityDropDown.innerHTML = '<option value="" selected disabled>Select Municipality</option>';
        return;
    }

    fetch(`../backend/province.php?id=${provinceID}`)
    .then(res => res.json())
    .then(districts => {
        let out = '<option value="" selected disabled>Select District</option>';
        for(let d of districts){
            out += `<option value="${d.id}">${d.title}</option>`;
        }
        districtDropDown.innerHTML = out;
        districtDropDown.disabled = false;

        municipalityDropDown.disabled = true;
        municipalityDropDown.innerHTML = '<option value="" selected disabled>Select Municipality</option>';
    })
    .catch(err => console.error(err));
}

function getMunicipalities(districtID, formName) {
    let municipalityDropDown = document.forms[formName].municipality;

    if (!districtID) {
        municipalityDropDown.disabled = true;
        municipalityDropDown.innerHTML = '<option value="" selected disabled>Select Municipality</option>';
        return;
    }

    fetch(`../backend/district.php?id=${districtID}`)
    .then(res => res.json())
    .then(municipalities => {
        let out = '<option value="" selected disabled>Select Municipality</option>';
        for(let m of municipalities){
            out += `<option value="${m.id}">${m.title}</option>`;
        }
        municipalityDropDown.innerHTML = out;
        municipalityDropDown.disabled = false;
    })
    .catch(err => console.error(err));
}

// Edit button functionality
// Edit button functionality
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', async function(e){
        e.preventDefault();
        const row = this.closest('tr');

        // Fill form fields
        document.getElementById('place_id').value = row.dataset.id;
        document.getElementById('placeName').value = row.dataset.name;
        document.getElementById('placeDescription').value = row.dataset.description;
        document.getElementById('lat').value = row.dataset.lat;
        document.getElementById('lng').value = row.dataset.lng;

        // Update marker
        if(row.dataset.lat && row.dataset.lng){
            const lat = parseFloat(row.dataset.lat);
            const lng = parseFloat(row.dataset.lng);
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 15);
        }

        // Set province
        const provinceSelect = document.getElementById('provinceSelect');
        provinceSelect.value = row.dataset.province;

        // Load districts
        await fetch(`../backend/province.php?id=${row.dataset.province}`)
        .then(res => res.json())
        .then(districts => {
            const districtSelect = document.getElementById('districtSelect');
            districtSelect.innerHTML = '<option value="" disabled>Select District</option>';
            districts.forEach(d => {
                const selected = d.id == row.dataset.district ? 'selected' : '';
                districtSelect.innerHTML += `<option value="${d.id}" ${selected}>${d.title}</option>`;
            });
            districtSelect.disabled = false;
        });

        // Load municipalities
        await fetch(`../backend/district.php?id=${row.dataset.district}`)
        .then(res => res.json())
        .then(municipalities => {
            const municipalitySelect = document.getElementById('municipalitySelect');
            municipalitySelect.innerHTML = '<option value="" disabled>Select Municipality</option>';
            municipalities.forEach(m => {
                const selected = m.id == row.dataset.municipality ? 'selected' : '';
                municipalitySelect.innerHTML += `<option value="${m.id}" ${selected}>${m.title}</option>`;
            });
            municipalitySelect.disabled = false;
        });

        // Update submit button text
        document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-pen-to-square mr-2"></i> Update Place';
        window.scrollTo({top:0, behavior:'smooth'});
    });
});
</script>
