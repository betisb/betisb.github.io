<?php
/**
 * Custom PHP Email Form Handler
 * Replaces the premium PHP Email Form library
 */

// Configuration
$receiving_email_address = 'baherib@canton.edu';
$subject_prefix = '[Quantum SHIELD Lab Contact] ';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to send email
function send_contact_email($to, $from_name, $from_email, $subject, $message) {
    global $subject_prefix;
    
    // Sanitize inputs
    $from_name = sanitize_input($from_name);
    $from_email = sanitize_input($from_email);
    $subject = sanitize_input($subject);
    $message = sanitize_input($message);
    
    // Validate email
    if (!validate_email($from_email)) {
        return json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    }
    
    // Validate required fields
    if (empty($from_name) || empty($from_email) || empty($message)) {
        return json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
    }
    
    // Prepare email content
    $full_subject = $subject_prefix . $subject;
    $email_body = "New contact form submission from Quantum SHIELD Lab website:\n\n";
    $email_body .= "Name: " . $from_name . "\n";
    $email_body .= "Email: " . $from_email . "\n";
    $email_body .= "Subject: " . $subject . "\n\n";
    $email_body .= "Message:\n" . $message . "\n\n";
    $email_body .= "---\n";
    $email_body .= "Sent from: " . $_SERVER['HTTP_HOST'] . "\n";
    $email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $email_body .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
    $email_body .= "Date: " . date('Y-m-d H:i:s') . "\n";
    
    // Email headers
    $headers = array();
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/plain; charset=UTF-8';
    $headers[] = 'From: ' . $from_name . ' <' . $from_email . '>';
    $headers[] = 'Reply-To: ' . $from_email;
    $headers[] = 'X-Mailer: PHP/' . phpversion();
    $headers[] = 'X-Originating-IP: ' . $_SERVER['REMOTE_ADDR'];
    
    // Send email
    if (mail($to, $full_subject, $email_body, implode("\r\n", $headers))) {
        return json_encode(['status' => 'success', 'message' => 'Your message has been sent. Thank you!']);
    } else {
        return json_encode(['status' => 'error', 'message' => 'Sorry, there was an error sending your message. Please try again later.']);
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set content type for JSON response
    header('Content-Type: application/json');
    
    // Check if it's an AJAX request
    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    // Get form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : 'General Inquiry';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    
    // Send email
    $result = send_contact_email($receiving_email_address, $name, $email, $subject, $message);
    
    if ($is_ajax) {
        // Return JSON response for AJAX requests
        echo $result;
    } else {
        // Handle non-AJAX requests
        $response = json_decode($result, true);
        if ($response['status'] === 'success') {
            // Redirect to thank you page or back to contact with success message
            header('Location: ../contact.html?success=1');
        } else {
            // Redirect back to contact with error message
            header('Location: ../contact.html?error=' . urlencode($response['message']));
        }
    }
    exit;
} else {
    // Not a POST request
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}
?>