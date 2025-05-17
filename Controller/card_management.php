<?php
// Start session for user authentication
session_start();

// Check if user is logged in
function checkAuthentication() {
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "Unauthorized access"]);
        exit;
    }
    return $_SESSION["id"]; // Return user ID
}

// Helper function for validating request method
function validateRequestMethod($method) {
    if($_SERVER["REQUEST_METHOD"] != $method) {
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Method not allowed"]);
        exit;
    }
}

// Helper function to validate and sanitize inputs
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Get the action parameter to determine which operation to perform
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'freeze_card':
        handleFreezeCard();
        break;
    case 'report_card':
        handleReportCard();
        break;
    case 'set_limits':
        handleSetLimits();
        break;
    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
        exit;
}

// Function to handle freezing/unfreezing cards
function handleFreezeCard() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    // Validate form data
    if(!isset($_POST['card_id']) || !isset($_POST['freeze_status'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    // Sanitize inputs
    $card_id = sanitizeInput($_POST['card_id']);
    $freeze_status = sanitizeInput($_POST['freeze_status']);
    
    // In a real application, we would update the database here
    // For this example, we'll simulate success
    
    // Get card details (in a real app, this would come from database)
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
    // Create masked card number for response
    $masked_number = '****' . substr($card_details['card_number'], -4);
    $card_name = $card_details['card_type'] . ' ' . $card_details['card_level'] . ' ' . $masked_number;
    
    $status_text = ($freeze_status == 'true') ? 'frozen' : 'unfrozen';
    
    echo json_encode([
        "success" => true,
        "message" => "Card {$card_name} has been {$status_text}",
        "card_id" => $card_id,
        "is_frozen" => ($freeze_status == 'true')
    ]);
}

// Function to handle reporting lost/stolen cards
function handleReportCard() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    // Validate form data
    if(!isset($_POST['card_id']) || !isset($_POST['report_type']) || !isset($_POST['report_details'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    // Sanitize inputs
    $card_id = sanitizeInput($_POST['card_id']);
    $report_type = sanitizeInput($_POST['report_type']); // 'lost' or 'stolen'
    $report_details = sanitizeInput($_POST['report_details']);
    
    // In a real application, we would update the database and trigger card cancellation
    // For this example, we'll simulate success
    
    // Get card details (in a real app, this would come from database)
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
    // Create masked card number for response
    $masked_number = '****' . substr($card_details['card_number'], -4);
    $card_name = $card_details['card_type'] . ' ' . $card_details['card_level'] . ' ' . $masked_number;
    
    // Generate reference number for the report
    $reference_number = 'REF' . date('YmdHis') . rand(1000, 9999);
    
    echo json_encode([
        "success" => true,
        "message" => "Your card {$card_name} has been reported {$report_type} and will be canceled",
        "reference_number" => $reference_number,
        "replacement" => "A replacement card will be shipped to your address on file"
    ]);
}

// Function to handle setting spending limits
function handleSetLimits() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    // Validate form data
    if(!isset($_POST['card_id']) || !isset($_POST['dining_limit']) || !isset($_POST['retail_limit']) || 
       !isset($_POST['travel_limit']) || !isset($_POST['online_limit'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    // Sanitize inputs
    $card_id = sanitizeInput($_POST['card_id']);
    $dining_limit = intval(sanitizeInput($_POST['dining_limit']));
    $retail_limit = intval(sanitizeInput($_POST['retail_limit']));
    $travel_limit = intval(sanitizeInput($_POST['travel_limit']));
    $online_limit = intval(sanitizeInput($_POST['online_limit']));
    
    // Validate limit values
    if($dining_limit < 0 || $retail_limit < 0 || $travel_limit < 0 || $online_limit < 0) {
        http_response_code(400);
        echo json_encode(["error" => "Spending limits cannot be negative"]);
        exit;
    }
    
    // In a real application, we would update the database here
    // For this example, we'll simulate success
    
    // Get card details (in a real app, this would come from database)
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
    // Create masked card number for response
    $masked_number = '****' . substr($card_details['card_number'], -4);
    $card_name = $card_details['card_type'] . ' ' . $card_details['card_level'] . ' ' . $masked_number;
    
    echo json_encode([
        "success" => true,
        "message" => "Spending limits for " . $card_name . " have been updated",
        "limits" => [
            "dining" => $dining_limit,
            "retail" => $retail_limit,
            "travel" => $travel_limit,
            "online" => $online_limit
        ]
    ]);
}

// Function to handle PIN change
function handlePinChange() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    // Validate form data
    if(!isset($_POST['card_id']) || !isset($_POST['current_pin']) || !isset($_POST['new_pin']) || !isset($_POST['confirm_pin'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    // Sanitize inputs
    $card_id = sanitizeInput($_POST['card_id']);
    $current_pin = sanitizeInput($_POST['current_pin']);
    $new_pin = sanitizeInput($_POST['new_pin']);
    $confirm_pin = sanitizeInput($_POST['confirm_pin']);
    
    // Validate PIN format (numbers only, 4 digits)
    if(!preg_match('/^\d{4}$/', $new_pin)) {
        http_response_code(400);
        echo json_encode(["error" => "PIN must be 4 digits"]);
        exit;
    }
    
    // Check if new PIN matches confirmation
    if($new_pin !== $confirm_pin) {
        http_response_code(400);
        echo json_encode(["error" => "New PIN and confirmation do not match"]);
        exit;
    }
    
    // In a real application, we would verify the current PIN and update with the new one
    // For this example, we'll simulate success
    
    // Get card details (in a real app, this would come from database)
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
    // Create masked card number for response
    $masked_number = '****' . substr($card_details['card_number'], -4);
    $card_name = $card_details['card_type'] . ' ' . $card_details['card_level'] . ' ' . $masked_number;
    
    echo json_encode([
        "success" => true,
        "message" => "PIN for " . $card_name . " has been successfully changed",
        "note" => "Please allow up to 24 hours for the new PIN to be active"
    ]);
}

// Function to handle fraud alerts
function handleFraudAlerts() {
    validateRequestMethod("GET");
    $user_id = checkAuthentication();
    
    // In a real application, we would fetch fraud alerts from a database
    // For this example, we'll simulate some alerts
    
    $alerts = simulateGetFraudAlerts($user_id);
    
    echo json_encode([
        "success" => true,
        "alerts" => $alerts
    ]);
}

// Function to acknowledge/resolve a fraud alert
function handleResolveFraudAlert() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    // Validate form data
    if(!isset($_POST['alert_id']) || !isset($_POST['action'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    // Sanitize inputs
    $alert_id = sanitizeInput($_POST['alert_id']);
    $action = sanitizeInput($_POST['action']); // 'confirm' or 'dispute'
    
    // In a real application, we would update the alert status in the database
    // For this example, we'll simulate success
    
    echo json_encode([
        "success" => true,
        "message" => "Fraud alert #" . $alert_id . " has been " . ($action == 'confirm' ? 'confirmed as legitimate' : 'disputed as fraudulent'),
        "status" => "resolved"
    ]);
}

// Simulation functions for testing (replace with actual database calls in production)

// Simulate fetching card details
function simulateGetCardDetails($card_id, $user_id) {
    // This would normally come from database
    $cards = [
        '1' => [
            'id' => 1,
            'user_id' => 1,
            'card_type' => 'Visa',
            'card_level' => 'Platinum',
            'card_number' => '4111111111111111',
            'expiry' => '12/25',
            'is_frozen' => false
        ],
        '2' => [
            'id' => 2,
            'user_id' => 1,
            'card_type' => 'Mastercard',
            'card_level' => 'Gold',
            'card_number' => '5555555555554444',
            'expiry' => '10/26',
            'is_frozen' => false
        ]
    ];
    
    // Check if card belongs to user
    if(isset($cards[$card_id]) && $cards[$card_id]['user_id'] == $user_id) {
        return $cards[$card_id];
    }
    
    // If card not found or doesn't belong to user
    http_response_code(403);
    echo json_encode(["error" => "You don't have permission to modify this card"]);
    exit;
}

// Simulate fetching fraud alerts
function simulateGetFraudAlerts($user_id) {
    return [
        [
            'id' => 101,
            'card_id' => 1,
            'transaction_date' => '2025-05-15 14:32:11',
            'merchant' => 'Online Electronics Store',
            'amount' => 899.99,
            'location' => 'Online',
            'status' => 'pending',
            'type' => 'unusual_amount'
        ],
        [
            'id' => 102,
            'card_id' => 2,
            'transaction_date' => '2025-05-16 08:15:45',
            'merchant' => 'Gas Station',
            'amount' => 75.50,
            'location' => 'New York, NY',
            'status' => 'pending',
            'type' => 'unusual_location'
        ]
    ];
}
?>