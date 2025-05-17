<?php
/**
 * Cheque Deposit PHP Backend Handler
 * 
 * This file processes the cheque deposit data received from the frontend,
 * validates the data, saves images, and returns appropriate responses.
 */

// Set headers to handle AJAX requests
header('Content-Type: application/json');

// Create a log file for debugging
$logFile = 'deposit_log.txt';

// Helper function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Helper function to generate a unique reference number
function generateReferenceNumber() {
    $dateStamp = date('Ymd');
    $randomNum = mt_rand(1000, 9999);
    return "DEP-{$dateStamp}{$randomNum}";
}

// Helper function to validate deposit request
function validateDepositRequest($data) {
    $errors = [];
    
    // Check required fields
    if (!isset($data['action']) || $data['action'] !== 'submit_deposit') {
        $errors[] = 'Invalid action specified';
    }
    
    if (!isset($data['account_id']) || !is_numeric($data['account_id'])) {
        $errors[] = 'Invalid account ID';
    }
    
    if (!isset($data['front_image']) || empty($data['front_image'])) {
        $errors[] = 'Front image of cheque is required';
    }
    
    if (!isset($data['back_image']) || empty($data['back_image'])) {
        $errors[] = 'Back image of cheque is required';
    }
    
    return $errors;
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logMessage('Received POST request');
    
    // Get JSON data from the request
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    // If JSON parsing failed
    if ($data === null) {
        logMessage('Failed to parse JSON data');
        $response['message'] = 'Invalid request format';
        echo json_encode($response);
        exit;
    }
    
    // Log received data (excluding images for brevity)
    $logData = $data;
    if (isset($logData['front_image'])) {
        $logData['front_image'] = '[IMAGE DATA]';
    }
    if (isset($logData['back_image'])) {
        $logData['back_image'] = '[IMAGE DATA]';
    }
    logMessage('Received data: ' . json_encode($logData));
    
    // Validate request
    $validationErrors = validateDepositRequest($data);
    
    if (!empty($validationErrors)) {
        logMessage('Validation errors: ' . implode(', ', $validationErrors));
        $response['message'] = 'Validation failed: ' . implode(', ', $validationErrors);
        echo json_encode($response);
        exit;
    }
    
    // Process based on the action
    switch ($data['action']) {
        case 'submit_deposit':
            processDeposit($data);
            break;
            
        default:
            logMessage('Invalid action: ' . $data['action']);
            $response['message'] = 'Invalid action specified';
            echo json_encode($response);
            exit;
    }
} else {
    logMessage('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

/**
 * Process the deposit submission
 */
function processDeposit($data) {
    global $response;
    logMessage('Processing deposit for account ID: ' . $data['account_id']);
    
    try {
        // Directory to store cheque images
        $uploadDir = 'uploads/cheques/' . date('Y/m/d') . '/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }
        
        // Generate unique filename prefix
        $timestamp = time();
        $filePrefix = $data['account_id'] . '_' . $timestamp;
        
        // Save front image
        $frontImage = saveBase64Image($data['front_image'], $uploadDir, $filePrefix . '_front');
        
        // Save back image
        $backImage = saveBase64Image($data['back_image'], $uploadDir, $filePrefix . '_back');
        
        // Generate reference number
        $referenceNumber = generateReferenceNumber();
        
        // Insert record into database (simulated here)
        $depositId = simulateDatabaseInsert($data, $frontImage, $backImage, $referenceNumber);
        
        // Build success response
        $response = [
            'success' => true,
            'message' => 'Deposit submitted successfully',
            'data' => [
                'reference_number' => $referenceNumber,
                'date_time' => date('Y-m-d H:i:s'),
                'status' => 'pending_review'
            ]
        ];
        
        logMessage('Deposit successful: ' . $referenceNumber);
        echo json_encode($response);
        
    } catch (Exception $e) {
        logMessage('Error processing deposit: ' . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Failed to process deposit: ' . $e->getMessage(),
            'data' => []
        ];
        echo json_encode($response);
    }
}

/**
 * Save base64 encoded image to the server
 */
function saveBase64Image($base64Data, $directory, $fileNamePrefix) {
    logMessage('Saving image: ' . $fileNamePrefix);
    
    // Extract the base64 encoded data
    $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
    $imageData = base64_decode($base64Data);
    
    if ($imageData === false) {
        throw new Exception('Invalid image data');
    }
    
    // Determine image type
    $f = finfo_open();
    $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
    finfo_close($f);
    
    // Set file extension based on mime type
    $extension = '';
    switch ($mimeType) {
        case 'image/jpeg':
            $extension = 'jpg';
            break;
        case 'image/png':
            $extension = 'png';
            break;
        case 'image/gif':
            $extension = 'gif';
            break;
        default:
            $extension = 'jpg'; // Default extension
    }
    
    // Create a unique filename
    $fileName = $fileNamePrefix . '.' . $extension;
    $filePath = $directory . $fileName;
    
    // Save the file
    if (file_put_contents($filePath, $imageData) === false) {
        throw new Exception('Failed to save image');
    }
    
    logMessage('Image saved: ' . $filePath);
    return $filePath;
}

/**
 * Simulate database insertion (in a real app, this would use actual database queries)
 */
function simulateDatabaseInsert($data, $frontImagePath, $backImagePath, $referenceNumber) {
    logMessage('Simulating database insert for reference: ' . $referenceNumber);
    
    // In a real application, this would insert a record into the database
    // and return the ID of the inserted record
    
    // For demonstration purposes, we'll return a simulated ID
    return mt_rand(10000, 99999);
}

/**
 * Endpoint to retrieve endorsement instructions
 * This can be called separately if needed
 */
function getEndorsementInstructions() {
    $instructions = [
        'Sign the back of the cheque where indicated',
        'Write "For Mobile Deposit Only" below your signature',
        'Do not include any account numbers in the endorsement'
    ];
    
    return $instructions;
}

/**
 * Endpoint to check deposit status
 * Can be implemented as a separate endpoint if needed
 */
if (isset($_GET['action']) && $_GET['action'] === 'check_status' && isset($_GET['reference'])) {
    $referenceNumber = $_GET['reference'];
    logMessage('Checking status for reference: ' . $referenceNumber);
    
    // In a real app, we would query the database for the status
    // For demo purposes, we'll return a simulated status
    
    $response = [
        'success' => true,
        'data' => [
            'reference_number' => $referenceNumber,
            'date_time' => date('Y-m-d H:i:s'),
            'status' => 'pending_review'
        ]
    ];
    
    echo json_encode($response);
    exit;
}

/**
 * Endpoint for validating a cheque before submission
 * This can be called during the capture step to perform basic validation
 */
if (isset($_POST['action']) && $_POST['action'] === 'validate_cheque') {
    logMessage('Validating cheque');
    
    // In a real app, this would perform image analysis to validate the cheque
    // For demo purposes, we'll return success
    
    $response = [
        'success' => true,
        'message' => 'Cheque appears valid',
        'data' => []
    ];
    
    echo json_encode($response);
    exit;
}
?>