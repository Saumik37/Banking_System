const atms = [];
let selectedATM = null;

function init() {
    const tbody = document.querySelector("#atmTable tbody");
    if (tbody) {
        tbody.innerHTML = `<tr><td colspan="5">Enter a location to find ATMs in Bangladesh</td></tr>`;
    }
    
    const map = document.getElementById("map");
    if (map) {
        map.innerHTML = `<div style="padding: 20px; text-align: center;">
            <p>Interactive Map Would Display Here</p>
            <p>Centered on Bangladesh</p>
        </div>`;
    }
}

function setupBackButton() {
    const backBtn = document.getElementById("back-btn");
    if (backBtn) {
        backBtn.addEventListener("click", function () {
            window.location.href = "../Account_Dashboard/dashboard.php";
        });
    }
}

function findATMs() {
    const locationInput = document.getElementById("locationInput");
    if (!locationInput) return;
    
    const location = locationInput.value.trim();
    
    if (!location) {
        alert("Please enter a location in Bangladesh");
        return;
    }
    
    const tbody = document.querySelector("#atmTable tbody");
    if (tbody) {
        tbody.innerHTML = `<tr><td colspan="5">Searching for ATMs in ${location}, Bangladesh...</td></tr>`;
    }
    
    setTimeout(() => {
        const tbody = document.querySelector("#atmTable tbody");
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="5">No ATMs found in ${location}, Bangladesh. Try a different location.</td></tr>`;
        }
        
        const map = document.getElementById("map");
        if (map) {
            map.innerHTML = `<div style="padding: 20px; text-align: center;">
                <p>Interactive Map Would Display Here</p>
                <p>No ATMs found in ${location}, Bangladesh</p>
            </div>`;
        }
    }, 1000);
}

function filterAndDisplayATMs() {
    const surchargeFilter = document.getElementById("surchargeFilter");
    const accessibleFilter = document.getElementById("accessibleFilter");
    const openNowFilter = document.getElementById("openNowFilter");
    
    if (!surchargeFilter || !accessibleFilter || !openNowFilter) return;
    
    const filteredATMs = atms.filter(atm => {
        if (surchargeFilter.checked && atm.surcharge) {
            return false;
        }
        if (accessibleFilter.checked && !atm.accessible) {
            return false;
        }
        if (openNowFilter.checked && atm.hours !== "24/7") {
            return false;
        }
        return true;
    });
    
    updateMapMarkers(filteredATMs);
    updateATMList(filteredATMs);
}

function updateMapMarkers(atms) {
    console.log("Updating map with", atms.length, "ATMs");
    
    const map = document.getElementById("map");
    if (map) {
        map.innerHTML = `<div style="padding: 20px; text-align: center;">
            <p>Interactive Map Would Display Here</p>
            <p>${atms.length} ATMs in Bangladesh match your filters</p>
        </div>`;
    }
}

function updateATMList(atms) {
    const tbody = document.querySelector("#atmTable tbody");
    if (!tbody) return;
    
    tbody.innerHTML = "";
    
    if (atms.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5">No ATMs found matching your criteria</td></tr>`;
        return;
    }
    
    atms.forEach(atm => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${atm.name}<br><small>${atm.address}</small></td>
            <td>${atm.distance} km</td>
            <td>${atm.surcharge ? "Fee applies" : "<span class='fee-free'>No fee</span>"}</td>
            <td>${atm.hours}</td>
            <td>
                <button class="action-button" onclick="viewATMDetails(${atm.id})">Details</button>
                <button class="action-button" onclick="getDirectionsToATM(${atm.id})">Directions</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function viewATMDetails(atmId) {
    selectedATM = atms.find(atm => atm.id === atmId);
    
    if (!selectedATM) {
        return;
    }
    
    const atmName = document.getElementById("atmName");
    const atmAddress = document.getElementById("atmAddress");
    const hoursDiv = document.getElementById("atmHours");
    const featuresDiv = document.getElementById("atmFeatures");
    
    if (atmName) atmName.textContent = selectedATM.name;
    if (atmAddress) atmAddress.textContent = selectedATM.address;
    
    if (hoursDiv) {
        hoursDiv.innerHTML = `<h4>Operating Hours:</h4>
            <p>${selectedATM.hours}</p>`;
    }
    
    if (featuresDiv) {
        const featuresHTML = selectedATM.features ? selectedATM.features.map(feature => `<li>${feature}</li>`).join("") : "";
        const accessibleHTML = selectedATM.accessible ? "<li>Wheelchair Accessible</li>" : "";
        const freeHTML = !selectedATM.surcharge ? "<li class='fee-free'>Surcharge-Free</li>" : "";
        
        featuresDiv.innerHTML = `<h4>Features:</h4>
            <ul>
                ${featuresHTML}
                ${accessibleHTML}
                ${freeHTML}
            </ul>`;
    }
    
    const listContainer = document.getElementById("atmListContainer");
    const detailPanel = document.getElementById("atmDetailPanel");
    
    if (listContainer) listContainer.classList.add("hidden");
    if (detailPanel) detailPanel.classList.remove("hidden");
}

function hideDetails() {
    const detailPanel = document.getElementById("atmDetailPanel");
    const listContainer = document.getElementById("atmListContainer");
    
    if (detailPanel) detailPanel.classList.add("hidden");
    if (listContainer) listContainer.classList.remove("hidden");
    
    selectedATM = null;
}

function getDirectionsToATM(atmId) {
    const atm = atms.find(atm => atm.id === atmId);
    
    if (!atm) {
        return;
    }
    
    alert(`Getting directions to ${atm.name} at ${atm.address}\n\nIn a real app, this would open a map with turn-by-turn directions.`);
}

function getDirections() {
    if (selectedATM) {
        getDirectionsToATM(selectedATM.id);
    }
}

function setupEventListeners() {
    const surchargeFilter = document.getElementById("surchargeFilter");
    const accessibleFilter = document.getElementById("accessibleFilter");
    const openNowFilter = document.getElementById("openNowFilter");
    
    if (surchargeFilter) {
        surchargeFilter.addEventListener("change", filterAndDisplayATMs);
    }
    if (accessibleFilter) {
        accessibleFilter.addEventListener("change", filterAndDisplayATMs);
    }
    if (openNowFilter) {
        openNowFilter.addEventListener("change", filterAndDisplayATMs);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    init();
    setupBackButton();
    setupEventListeners();
});