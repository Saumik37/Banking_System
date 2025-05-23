// Sample ATM data - in a real app, this would come from an API
const atms = [
    // ATMs will be populated from API in real application
];

// Track the currently selected ATM
let selectedATM = null;

// Initialize the application
function init() {
    // In a real application, we would initialize the map here
    // For this example, we'll just show "No ATMs found" message
    const tbody = document.querySelector("#atmTable tbody");
    tbody.innerHTML = `<tr><td colspan="5">Enter a location to find ATMs in Bangladesh</td></tr>`;
    
    // Initialize map with Bangladesh center coordinates
    const map = document.getElementById("map");
    map.innerHTML = `<div style="padding: 20px; text-align: center;">
        <p>Interactive Map Would Display Here</p>
        <p>Centered on Bangladesh</p>
    </div>`;
}
//back button functionality     
document.getElementById("back-btn").addEventListener("click", function () {
    window.location.href = "../Account_Dashboard/Account_Dashboard.html";
  });

// Find ATMs based on location and filters
function findATMs() {
    const location = document.getElementById("locationInput").value.trim();
    
    if (!location) {
        alert("Please enter a location in Bangladesh");
        return;
    }
    
    // Show searching message
    const tbody = document.querySelector("#atmTable tbody");
    tbody.innerHTML = `<tr><td colspan="5">Searching for ATMs in ${location}, Bangladesh...</td></tr>`;
    
    // In a real app, this would make an API call with the location and radius
    // For this demo, we'll just show that no ATMs were found
    setTimeout(() => {
        const tbody = document.querySelector("#atmTable tbody");
        tbody.innerHTML = `<tr><td colspan="5">No ATMs found in ${location}, Bangladesh. Try a different location.</td></tr>`;
        
        // Update map
        const map = document.getElementById("map");
        map.innerHTML = `<div style="padding: 20px; text-align: center;">
            <p>Interactive Map Would Display Here</p>
            <p>No ATMs found in ${location}, Bangladesh</p>
        </div>`;
    }, 1000);
}

// Filter and display ATMs based on the current filter settings
function filterAndDisplayATMs() {
    const surchargeFilter = document.getElementById("surchargeFilter").checked;
    const accessibleFilter = document.getElementById("accessibleFilter").checked;
    const openNowFilter = document.getElementById("openNowFilter").checked;
    
    // Filter ATMs based on criteria
    const filteredATMs = atms.filter(atm => {
        if (surchargeFilter && atm.surcharge) {
            return false;
        }
        if (accessibleFilter && !atm.accessible) {
            return false;
        }
        if (openNowFilter) {
            // In a real app, we would check if the ATM is currently open
            // For simplicity, we'll just assume 24/7 ATMs are always open
            if (atm.hours !== "24/7") {
                return false;
            }
        }
        return true;
    });
    
    // Update the map markers (in a real app)
    updateMapMarkers(filteredATMs);
    
    // Update the ATM list
    updateATMList(filteredATMs);
}

// Update map markers (placeholder function)
function updateMapMarkers(atms) {
    // In a real app, this would update the map markers
    console.log("Updating map with", atms.length, "ATMs");
    
    // For this example, we'll just update a placeholder message in the map container
    const map = document.getElementById("map");
    map.innerHTML = `<div style="padding: 20px; text-align: center;">
        <p>Interactive Map Would Display Here</p>
        <p>${atms.length} ATMs in Bangladesh match your filters</p>
    </div>`;
}

// Update the ATM list table
function updateATMList(atms) {
    const tbody = document.querySelector("#atmTable tbody");
    tbody.innerHTML = "";
    
    if (atms.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5">No ATMs found matching your criteria</td></tr>`;
        return;
    }
    
    atms.forEach(atm => {
        const row = `<tr>
            <td>${atm.name}<br><small>${atm.address}</small></td>
            <td>${atm.distance} km</td>
            <td>${atm.surcharge ? "Fee applies" : "<span class='fee-free'>No fee</span>"}</td>
            <td>${atm.hours}</td>
            <td>
                <button class="action-button" onclick="viewATMDetails(${atm.id})">Details</button>
                <button class="action-button" onclick="getDirectionsToATM(${atm.id})">Directions</button>
            </td>
        </tr>`;
        tbody.innerHTML += row;
    });
}

// View details for a specific ATM
function viewATMDetails(atmId) {
    // Find the selected ATM
    selectedATM = atms.find(atm => atm.id === atmId);
    
    if (!selectedATM) {
        return;
    }
    
    // Update the detail panel
    document.getElementById("atmName").textContent = selectedATM.name;
    document.getElementById("atmAddress").textContent = selectedATM.address;
    
    // Display hours
    const hoursDiv = document.getElementById("atmHours");
    hoursDiv.innerHTML = `<h4>Operating Hours:</h4>
        <p>${selectedATM.hours}</p>`;
    
    // Display features
    const featuresDiv = document.getElementById("atmFeatures");
    featuresDiv.innerHTML = `<h4>Features:</h4>
        <ul>
            ${selectedATM.features.map(feature => `<li>${feature}</li>`).join("")}
            ${selectedATM.accessible ? "<li>Wheelchair Accessible</li>" : ""}
            ${!selectedATM.surcharge ? "<li class='fee-free'>Surcharge-Free</li>" : ""}
        </ul>`;
    
    // Show the detail panel and hide the list
    document.getElementById("atmListContainer").classList.add("hidden");
    document.getElementById("atmDetailPanel").classList.remove("hidden");
}

// Hide the details panel and show the list
function hideDetails() {
    document.getElementById("atmDetailPanel").classList.add("hidden");
    document.getElementById("atmListContainer").classList.remove("hidden");
    selectedATM = null;
}

// Get directions to an ATM
function getDirectionsToATM(atmId) {
    // Find the ATM
    const atm = atms.find(atm => atm.id === atmId);
    
    if (!atm) {
        return;
    }
    
    // In a real app, we would open a map with directions
    alert(`Getting directions to ${atm.name} at ${atm.address}\n\nIn a real app, this would open a map with turn-by-turn directions.`);
}

// General function for the details page get directions button
function getDirections() {
    if (selectedATM) {
        getDirectionsToATM(selectedATM.id);
    }
}

// Add event listeners for filters
document.getElementById("surchargeFilter").addEventListener("change", filterAndDisplayATMs);
document.getElementById("accessibleFilter").addEventListener("change", filterAndDisplayATMs);
document.getElementById("openNowFilter").addEventListener("change", filterAndDisplayATMs);

// Initialize the app when the page loads
window.onload = init;