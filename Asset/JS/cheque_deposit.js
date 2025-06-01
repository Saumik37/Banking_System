document.addEventListener('DOMContentLoaded', function() {
    let frontImageData = null;
    let backImageData = null;
    let selectedAccountId = 1;

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
    
    function goToStep(stepNumber) {
        document.querySelectorAll('.step-content').forEach(function(content) {
            content.classList.remove('active');
        });
        
        document.getElementById(`step${stepNumber}Content`).classList.add('active');
        updateStepIndicators(stepNumber);
    }
    
    function updateStepIndicators(currentStep) {
        for (let i = 1; i <= 3; i++) {
            document.getElementById(`step${i}Circle`).classList.remove('active');
            document.getElementById(`step${i}Label`).classList.remove('active');
            
            if (i < 3) {
                document.getElementById(`line${i}`).classList.remove('active');
            }
        }
        
        for (let i = 1; i <= currentStep; i++) {
            document.getElementById(`step${i}Circle`).classList.add('active');
            document.getElementById(`step${i}Label`).classList.add('active');
            
            if (i < currentStep) {
                document.getElementById(`line${i}`).classList.add('active');
            }
        }
    }
    
    function setupImageCapture() {
        frontCaptureArea.addEventListener('click', function() {
            if (frontCapturePlaceholder.style.display !== 'none') {
                openCamera('front');
            }
        });
        
        backCaptureArea.addEventListener('click', function() {
            if (frontImageData && backCapturePlaceholder.style.display !== 'none') {
                openCamera('back');
            }
        });
        
        retakeFrontBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            resetFrontImage();
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
    }
    
    function resetBackImage() {
        backImageData = null;
        backCapturePlaceholder.style.display = 'flex';
        backImageContainer.style.display = 'none';
    }
    
    function openCamera(side) {
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
                        
                        backCapturePlaceholder.textContent = 'Tap to capture';
                        backCapturePlaceholder.classList.remove('disabled');
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
    
    function validateStep1() {
        if (frontImageData && backImageData) {
            continueToStep2.classList.remove('disabled');
            continueToStep2.disabled = false;
        } else {
            continueToStep2.classList.add('disabled');
            continueToStep2.disabled = true;
        }
    }
    
    function setupStepTransitions() {
        continueToStep2.addEventListener('click', function() {
            if (!continueToStep2.classList.contains('disabled')) {
                goToStep(2);
            }
        });
        
        backToStep1.addEventListener('click', function() {
            goToStep(1);
        });
        
        continueToStep3.addEventListener('click', function() {
            prepareStep3();
            goToStep(3);
        });
        
        backToStep2.addEventListener('click', function() {
            goToStep(2);
        });
        
        submitDeposit.addEventListener('click', function() {
            submitChequeDeposit();
        });
        
        doneButton.addEventListener('click', function() {
            window.location.href = '../View/Account_Dashboard/Account_Dashboard.html';
        });
    }
    
    function prepareStep3() {
        const now = new Date();
        const dateTimeStr = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
        depositDate.textContent = dateTimeStr;
        
        frontThumbnail.src = frontImageData;
        backThumbnail.src = backImageData;
    }
    
    function submitChequeDeposit() {
        statusScreen.style.display = 'flex';
        processingStatus.style.display = 'none';
        
        const dateStamp = new Date().toISOString().slice(0, 8).replace(/-/g, '');
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        const ref = `DEP-${dateStamp}${randomNum}`;
        
        referenceNumber.textContent = ref;
        successStatus.style.display = 'flex';
    }
    
    function init() {
        setupImageCapture();
        setupStepTransitions();
        goToStep(1);
        validateStep1();
    }
    
    init();
});