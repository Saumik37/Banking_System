<?php
header('Content-Type: application/json');

$logFile = 'deposit_log.txt';

function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

function generateReferenceNumber() {
    $dateStamp = date('Ymd');
    $randomNum = mt_rand(1000, 9999);
    return "DEP-{$dateStamp}{$randomNum}";
}

function validateDepositRequest($data) {
    $errors = [];
    
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

$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logMessage('Received POST request');
    
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    if ($data === null) {
        logMessage('Failed to parse JSON data');
        $response['message'] = 'Invalid request format';
        echo json_encode($response);
        exit;
    }
    
    $logData = $data;
    if (isset($logData['front_image'])) {
        $logData['front_image'] = '[IMAGE DATA]';
    }
    if (isset($logData['back_image'])) {
        $logData['back_image'] = '[IMAGE DATA]';
    }
    logMessage('Received data: ' . json_encode($logData));
    
    $validationErrors = validateDepositRequest($data);
    
    if (!empty($validationErrors)) {
        logMessage('Validation errors: ' . implode(', ', $validationErrors));
        $response['message'] = 'Validation failed: ' . implode(', ', $validationErrors);
        echo json_encode($response);
        exit;
    }
    
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

function processDeposit($data) {
    global $response;
    logMessage('Processing deposit for account ID: ' . $data['account_id']);
    
    try {
        $uploadDir = 'uploads/cheques/' . date('Y/m/d') . '/';
        
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }
        
        $timestamp = time();
        $filePrefix = $data['account_id'] . '_' . $timestamp;
        
        $frontImage = saveBase64Image($data['front_image'], $uploadDir, $filePrefix . '_front');
        
        $backImage = saveBase64Image($data['back_image'], $uploadDir, $filePrefix . '_back');
        
        $referenceNumber = generateReferenceNumber();
        
        $depositId = simulateDatabaseInsert($data, $frontImage, $backImage, $referenceNumber);
        
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

function saveBase64Image($base64Data, $directory, $fileNamePrefix) {
    logMessage('Saving image: ' . $fileNamePrefix);
    
    $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
    $imageData = base64_decode($base64Data);
    
    if ($imageData === false) {
        throw new Exception('Invalid image data');
    }
    
    $f = finfo_open();
    $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
    finfo_close($f);
    
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
            $extension = 'jpg';
    }
    
    $fileName = $fileNamePrefix . '.' . $extension;
    $filePath = $directory . $fileName;
    
    if (file_put_contents($filePath, $imageData) === false) {
        throw new Exception('Failed to save image');
    }
    
    logMessage('Image saved: ' . $filePath);
    return $filePath;
}

function simulateDatabaseInsert($data, $frontImagePath, $backImagePath, $referenceNumber) {
    logMessage('Simulating database insert for reference: ' . $referenceNumber);
    
    return mt_rand(10000, 99999);
}

function getEndorsementInstructions() {
    $instructions = [
        'Sign the back of the cheque where indicated',
        'Write "For Mobile Deposit Only" below your signature',
        'Do not include any account numbers in the endorsement'
    ];
    
    return $instructions;
}

if (isset($_GET['action']) && $_GET['action'] === 'check_status' && isset($_GET['reference'])) {
    $referenceNumber = $_GET['reference'];
    logMessage('Checking status for reference: ' . $referenceNumber);
    
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

if (isset($_POST['action']) && $_POST['action'] === 'validate_cheque') {
    logMessage('Validating cheque');
    
    $response = [
        'success' => true,
        'message' => 'Cheque appears valid',
        'data' => []
    ];
    
    echo json_encode($response);
    exit;
}
?>