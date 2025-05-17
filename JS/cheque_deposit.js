/**
 * Cheque Deposit JavaScript
 * 
 * Handles all client-side functionality for the mobile cheque deposit feature,
 * including camera access, image capture, form validation, and API interactions.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Global variables to store captured images and detected amount
    let frontImageData = null;
    let backImageData = null;
    let detectedAmount = 0;
    let userAmount = 0;
    let selectedAccountId = 1; // Default account ID

    // DOM elements
    const frontCaptureArea = document.getElementById('frontCaptureArea');
    const backCaptureArea = document.getElementById('backCaptureArea');
    const frontCapturePlaceholder = document.getElementById('frontCapturePlaceholder');
    const backCapturePlaceholder = document.getElementById('backCapturePlaceholder');
    const frontImageContainer = document.getElementById('frontImageContainer');
    const backImageContainer = document.getElementById('backImageContainer');
    const frontImage = document.getElementById('frontImage');
    const backImage = document.getElementById('backImage');
    const retakeFrontBtn = document.getElementById('retakeFrontBtn');
    const retakeBackBtn = document.getElementById('retakeBackBtn');
    const detectedAmountEl = document.getElementById('detectedAmount');
    const continueToStep2 = document.getElementById('continueToStep2');
    const continueToStep3 = document.getElementById('continueToStep3');
    const backToStep1 = document.getElementById('backToStep1');
    const backToStep2 = document.getElementById('backToStep2');
    const submitDeposit = document.getElementById('submitDeposit');
    const depositDate = document.getElementById('depositDate');
    const frontThumbnail = document.getElementById('frontThumbnail');
    const backThumbnail = document.getElementById('backThumbnail');
    const statusScreen = document.getElementById('statusScreen');
    const processingStatus = document.getElementById('processingStatus');
    const successStatus = document.getElementById('successStatus');
    const referenceNumber = document.getElementById('referenceNumber');
    const doneButton = document.getElementById('doneButton');
    
    // Step navigation functions
    function goToStep(stepNumber) {
        // Hide all step content
        document.querySelectorAll('.step-content').forEach(function(content) {
            content.classList.remove('active');
        });
        
        // Show requested step content
        document.getElementById(`step${stepNumber}Content`).classList.add('active');
        
        // Update progress indicators
        updateStepIndicators(stepNumber);
    }
    
    function updateStepIndicators(currentStep) {
        // Reset all indicators
        for (let i = 1; i <= 3; i++) {
            document.getElementById(`step${i}Circle`).classList.remove('active');
            document.getElementById(`step${i}Label`).classList.remove('active');
            
            if (i < 3) {
                document.getElementById(`line${i}`).classList.remove('active');
            }
        }
        
        // Set current and previous steps as active
        for (let i = 1; i <= currentStep; i++) {
            document.getElementById(`step${i}Circle`).classList.add('active');
            document.getElementById(`step${i}Label`).classList.add('active');
            
            if (i < currentStep) {
                document.getElementById(`line${i}`).classList.add('active');
            }
        }
    }
    
    // Image capture functions
    function setupImageCapture() {
        // Front image capture
        frontCaptureArea.addEventListener('click', function() {
            if (frontCapturePlaceholder.style.display !== 'none') {
                openCamera('front');
            }
        });
        
        // Back image capture (only enable after front is captured)
        backCaptureArea.addEventListener('click', function() {
            if (frontImageData && backCapturePlaceholder.style.display !== 'none') {
                openCamera('back');
            }
        });
        
        // Retake buttons
        retakeFrontBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            resetFrontImage();
            // If back was already captured, we need to reset it too
            if (backImageData) {
                resetBackImage();
            }
            validateStep1();
        });
        
        retakeBackBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            resetBackImage();
            validateStep1();
        });
    }
    
    function resetFrontImage() {
        frontImageData = null;
        frontCapturePlaceholder.style.display = 'flex';
        frontImageContainer.style.display = 'none';
        backCapturePlaceholder.textContent = 'Capture front first';
        backCapturePlaceholder.classList.add('disabled');
        detectedAmount = 0;
    }
    
    function resetBackImage() {
        backImageData = null;
        backCapturePlaceholder.style.display = 'flex';
        backImageContainer.style.display = 'none';
    }
    
    // Simulated camera access
    function openCamera(side) {
        // In a real app, this would use the device camera API
        // For simulation, we'll use a file input
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*';
        fileInput.capture = 'camera';
        
        fileInput.addEventListener('change', function(e) {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const imageData = e.target.result;
                    
                    if (side === 'front') {
                        frontImageData = imageData;
                        frontImage.src = imageData;
                        frontCapturePlaceholder.style.display = 'none';
                        frontImageContainer.style.display = 'block';
                        
                        // Enable back capture
                        backCapturePlaceholder.textContent = 'Tap to capture';
                        backCapturePlaceholder.classList.remove('disabled');
                        
                        // Simulate amount detection with a slight delay
                        simulateAmountDetection();
                    } else {
                        backImageData = imageData;
                        backImage.src = imageData;
                        backCapturePlaceholder.style.display = 'none';
                        backImageContainer.style.display = 'block';
                    }
                    
                    validateStep1();
                };
                
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
        
        fileInput.click();
    }
    
    function simulateAmountDetection() {
        detectedAmountEl.textContent = 'Detecting...';
        
        setTimeout(function() {
            // Generate random amount between $50 and $2000
            const dollars = Math.floor(Math.random() * 1950) + 50;
            const cents = Math.floor(Math.random() * 100);
            detectedAmount = dollars + (cents / 100);
            
            detectedAmountEl.textContent = `$${detectedAmount.toFixed(2)}`;
            
            // Auto-fill the amount in step 3
            userAmount = detectedAmount;
        }, 1500);
    }
    
    // Form validation
    function validateStep1() {
        if (frontImageData && backImageData) {
            continueToStep2.classList.remove('disabled');
            continueToStep2.disabled = false;
        } else {
            continueToStep2.classList.add('disabled');
            continueToStep2.disabled = true;
        }
    }
    
    // Step transitions
    function setupStepTransitions() {
        // Step 1 to Step 2
        continueToStep2.addEventListener('click', function() {
            if (!continueToStep2.classList.contains('disabled')) {
                goToStep(2);
            }
        });
        
        // Step 2 to Step 1 (back)
        backToStep1.addEventListener('click', function() {
            goToStep(1);
        });
        
        // Step 2 to Step 3
        continueToStep3.addEventListener('click', function() {
            prepareStep3();
            goToStep(3);
        });
        
        // Step 3 to Step 2 (back)
        backToStep2.addEventListener('click', function() {
            goToStep(2);
        });
        
        // Submit deposit button
        submitDeposit.addEventListener('click', function() {
            submitChequeDeposit();
        });
        
        // Done button (return to banking dashboard)
        doneButton.addEventListener('click', function() {
            // Redirect to dashboard or close the flow
            window.location.href = '../View/Account_Dashboard/Account_Dashboard.html';
        });
    }
    
    function prepareStep3() {
        // Set current date and time
        const now = new Date();
        const dateTimeStr = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
        depositDate.textContent = dateTimeStr;
        
        // Set thumbnails
        frontThumbnail.src = frontImageData;
        backThumbnail.src = backImageData;
    }
    
    // API interactions
    function submitChequeDeposit() {
        // Show processing screen
        statusScreen.style.display = 'flex';
        processingStatus.style.display = 'flex';
        
        // Prepare data for submission
        const depositData = {
            action: 'submit_deposit',
            account_id: selectedAccountId,
            amount: userAmount,
            front_image: frontImageData,
            back_image: backImageData
        };
        
        // In a real app, we would send this to the server
        // For demo, we'll simulate the server response after a delay
        setTimeout(function() {
            simulateServerResponse(depositData);
        }, 2000);
    }
    
    function simulateServerResponse(depositData) {
        // Hide processing screen
        processingStatus.style.display = 'none';
        
        // Generate a reference number
        const dateStamp = new Date().toISOString().slice(0, 8).replace(/-/g, '');
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        const ref = `DEP-${dateStamp}${randomNum}`;
        
        // Update success screen
        referenceNumber.textContent = ref;
        
        // Show success screen
        successStatus.style.display = 'flex';
    }
    
    // Initialize the app
    function init() {
        setupImageCapture();
        setupStepTransitions();
        goToStep(1);
        validateStep1();
    }
    
    // Start the app
    init();
});