<?php
require_once 'menu.php';

// Set content type for all responses
if (!headers_sent()) {
    header('Content-type: text/plain');
}

// Robust error handling for USSD responses
set_exception_handler(function($e) {
    error_log("Fatal error: " . $e->getMessage());
    echo "End Thanks for using our Ordering system, you will receive a confirmation message later.";
    exit;
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno] $errstr on line $errline in file $errfile");
    echo "End Thanks for using our Ordering system, you will receive a confirmation message later.";
    exit;
});

// Check if the Menu class exists
if (!class_exists('Menu')) {
    echo "END Error: Menu class not found.";
    exit;
}

// Get and validate USSD parameters
$sessionId   = $_REQUEST['sessionId'] ?? '';
$serviceCode = $_REQUEST['serviceCode'] ?? '';
$phoneNumber = $_REQUEST['phoneNumber'] ?? '';
$text        = $_REQUEST['text'] ?? '';

// For testing in browser or Postman, set default values if missing
if (php_sapi_name() !== 'cli' && empty($sessionId)) {
    $sessionId = 'test-session';
}
if (php_sapi_name() !== 'cli' && empty($phoneNumber)) {
    $phoneNumber = '256700000000';
}
if (php_sapi_name() !== 'cli' && empty($serviceCode)) {
    $serviceCode = '*123#';
}
if (php_sapi_name() !== 'cli' && empty($text)) {
    $text = '';
}

if (empty($sessionId) || empty($phoneNumber)) {
    echo "END Missing required parameters.";
    exit;
}

// Process the USSD menu
$menu = new Menu($sessionId, $phoneNumber, $text);
$response = $menu->processMenu();

// Format response consistently
$response = trim($response);
if (stripos($response, 'CON') === 0) {
    echo $response;  // Already has CON prefix
} elseif (stripos($response, 'END') === 0) {
    echo $response;  // Already has END prefix
} elseif (stripos($response, 'Enter') === 0 || !str_contains($response, 'END')) {
    echo "CON " . $response;  // Continue with menu
} else {
    echo "END " . $response;  // End session
}
?> 