<?php
session_start();

function checkAuthentication() {
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized access"]);
        exit;
    }
    return $_SESSION["id"];
}

function validateRequestMethod($method) {
    if($_SERVER["REQUEST_METHOD"] != $method) {
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        exit;
    }
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

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
    case 'change_pin':
        handlePinChange();
        break;
    case 'fraud_alerts':
        handleFraudAlerts();
        break;
    case 'resolve_fraud':
        handleResolveFraudAlert();
        break;
    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
        exit;
}

function handleFreezeCard() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    if(!isset($_POST['card_id']) || !isset($_POST['freeze_status'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    $card_id = sanitizeInput($_POST['card_id']);
    $freeze_status = sanitizeInput($_POST['freeze_status']);
    
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
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

function handleReportCard() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    if(!isset($_POST['card_id']) || !isset($_POST['report_type']) || !isset($_POST['report_details'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    $card_id = sanitizeInput($_POST['card_id']);
    $report_type = sanitizeInput($_POST['report_type']);
    $report_details = sanitizeInput($_POST['report_details']);
    
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
    $masked_number = '****' . substr($card_details['card_number'], -4);
    $card_name = $card_details['card_type'] . ' ' . $card_details['card_level'] . ' ' . $masked_number;
    
    $reference_number = 'REF' . date('YmdHis') . rand(1000, 9999);
    
    echo json_encode([
        "success" => true,
        "message" => "Your card {$card_name} has been reported {$report_type} and will be canceled",
        "reference_number" => $reference_number,
        "replacement" => "A replacement card will be shipped to your address on file"
    ]);
}

function handleSetLimits() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    if(!isset($_POST['card_id']) || !isset($_POST['dining_limit']) || !isset($_POST['retail_limit']) || 
       !isset($_POST['travel_limit']) || !isset($_POST['online_limit'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    $card_id = sanitizeInput($_POST['card_id']);
    $dining_limit = intval(sanitizeInput($_POST['dining_limit']));
    $retail_limit = intval(sanitizeInput($_POST['retail_limit']));
    $travel_limit = intval(sanitizeInput($_POST['travel_limit']));
    $online_limit = intval(sanitizeInput($_POST['online_limit']));
    
    if($dining_limit < 0 || $retail_limit < 0 || $travel_limit < 0 || $online_limit < 0) {
        http_response_code(400);
        echo json_encode(["error" => "Spending limits cannot be negative"]);
        exit;
    }
    
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
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

function handlePinChange() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    if(!isset($_POST['card_id']) || !isset($_POST['current_pin']) || !isset($_POST['new_pin']) || !isset($_POST['confirm_pin'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    $card_id = sanitizeInput($_POST['card_id']);
    $current_pin = sanitizeInput($_POST['current_pin']);
    $new_pin = sanitizeInput($_POST['new_pin']);
    $confirm_pin = sanitizeInput($_POST['confirm_pin']);
    
    if(!preg_match('/^\d{4}$/', $new_pin)) {
        http_response_code(400);
        echo json_encode(["error" => "PIN must be 4 digits"]);
        exit;
    }
    
    if($new_pin !== $confirm_pin) {
        http_response_code(400);
        echo json_encode(["error" => "New PIN and confirmation do not match"]);
        exit;
    }
    
    $card_details = simulateGetCardDetails($card_id, $user_id);
    
    $masked_number = '****' . substr($card_details['card_number'], -4);
    $card_name = $card_details['card_type'] . ' ' . $card_details['card_level'] . ' ' . $masked_number;
    
    echo json_encode([
        "success" => true,
        "message" => "PIN for " . $card_name . " has been successfully changed",
        "note" => "Please allow up to 24 hours for the new PIN to be active"
    ]);
}

function handleFraudAlerts() {
    validateRequestMethod("GET");
    $user_id = checkAuthentication();
    
    $alerts = simulateGetFraudAlerts($user_id);
    
    echo json_encode([
        "success" => true,
        "alerts" => $alerts
    ]);
}

function handleResolveFraudAlert() {
    validateRequestMethod("POST");
    $user_id = checkAuthentication();
    
    if(!isset($_POST['alert_id']) || !isset($_POST['action'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    $alert_id = sanitizeInput($_POST['alert_id']);
    $action = sanitizeInput($_POST['action']);
    
    echo json_encode([
        "success" => true,
        "message" => "Fraud alert #" . $alert_id . " has been " . ($action == 'confirm' ? 'confirmed as legitimate' : 'disputed as fraudulent'),
        "status" => "resolved"
    ]);
}

function simulateGetCardDetails($card_id, $user_id) {
    
}

function simulateGetFraudAlerts($user_id) {
    
}
?>