<?php
header('Content-Type: application/json');

// CORS headers if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Pouze POST požadavky jsou povoleny.']);
    exit;
}

// Get user IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

$user_ip = getUserIP();

// Rate limiting - 1 email per IP address per hour
$rate_limit_file = __DIR__ . '/.rate_limit.json';
$rate_limit_data = file_exists($rate_limit_file) ? json_decode(file_get_contents($rate_limit_file), true) : [];
$current_time = time();
$rate_limit_window = 3600; // 1 hour in seconds

// Clean old entries
$rate_limit_data = array_filter($rate_limit_data, function($timestamp) use ($current_time, $rate_limit_window) {
    return ($current_time - $timestamp) < $rate_limit_window;
});

// Check if IP has sent email recently
if (isset($rate_limit_data[$user_ip])) {
    $time_left = $rate_limit_window - ($current_time - $rate_limit_data[$user_ip]);
    $minutes_left = ceil($time_left / 60);
    http_response_code(429);
    echo json_encode([
        'success' => false, 
        'message' => 'Můžete odesílat pouze 1 zprávu za hodinu. Zkuste to znovu za ' . $minutes_left . ' minut.'
    ]);
    exit;
}

// Cloudflare Turnstile verification
$turnstile_token = isset($_POST['cf-turnstile-response']) ? $_POST['cf-turnstile-response'] : '';

if (empty($turnstile_token)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Prosím, dokončete ověření CAPTCHA.']);
    exit;
}

// Verify Turnstile token
$turnstile_secret = '0x4AAAAAACNsagsGtqiNAykAXlIAp538GGo';
$verify_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

$verify_data = [
    'secret' => $turnstile_secret,
    'response' => $turnstile_token,
    'remoteip' => $user_ip
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($verify_data)
    ]
];

$context = stream_context_create($options);
$verify_response = @file_get_contents($verify_url, false, $context);

if ($verify_response === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Chyba při ověřování CAPTCHA.']);
    exit;
}

$verify_result = json_decode($verify_response, true);

if (!isset($verify_result['success']) || $verify_result['success'] !== true) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'CAPTCHA ověření selhalo. Zkuste to znovu.']);
    exit;
}

// Sanitize and validate inputs
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$language = isset($_POST['language']) ? trim($_POST['language']) : 'cs';

// Honeypot check (if honeypot field is filled, it's a bot)
$honeypot = isset($_POST['website']) ? trim($_POST['website']) : '';
if (!empty($honeypot)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Neplatný požadavek.']);
    exit;
}

// Validation
$errors = [];

if (empty($name) || strlen($name) < 2 || strlen($name) > 50) {
    $errors[] = 'Jméno musí mít 2-50 znaků.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Zadejte platnou emailovou adresu.';
}

if (empty($subject) || strlen($subject) < 3 || strlen($subject) > 100) {
    $errors[] = 'Předmět musí mít 3-100 znaků.';
}

if (empty($message) || strlen($message) < 10 || strlen($message) > 1000) {
    $errors[] = 'Zpráva musí mít 10-1000 znaků.';
}

// Additional spam checks
if (preg_match('/<script|javascript:|<iframe|<object|<embed/i', $name . $email . $subject . $message)) {
    $errors[] = 'Neplatný obsah zprávy.';
}

// Check for too many URLs in message
if (substr_count(strtolower($message), 'http://') + substr_count(strtolower($message), 'https://') > 3) {
    $errors[] = 'Příliš mnoho odkazů ve zprávě.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// SMTP Configuration
$smtp_host = 'mail.jirka086.is-a.dev';
$smtp_port = 25;
$smtp_user = 'admin@jirka086.is-a.dev';
$smtp_pass = '11Lava11';
$smtp_from = 'admin@jirka086.is-a.dev';
$smtp_to = 'adamec08@seznam.cz';

// Function to send email via SMTP
function sendEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $from, $to, $subject, $htmlBody, $textBody, $replyTo = null, $fromName = null) {
    $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, 30);
    
    if (!$socket) {
        throw new Exception("Nelze se připojit k SMTP serveru: $errstr ($errno)");
    }
    
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) != '220') {
        fclose($socket);
        throw new Exception("Server neodpověděl správně: $response");
    }
    
    fputs($socket, "EHLO {$smtp_host}\r\n");
    $response = fgets($socket, 515);
    
    while ($response && substr($response, 3, 1) == '-') {
        $response = fgets($socket, 515);
    }
    
    fputs($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 515);
    
    if (substr($response, 0, 3) == '334') {
        fputs($socket, base64_encode($smtp_user) . "\r\n");
        $response = fgets($socket, 515);
        
        if (substr($response, 0, 3) != '334') {
            fclose($socket);
            throw new Exception("SMTP autentizace selhala při username: $response");
        }
        
        fputs($socket, base64_encode($smtp_pass) . "\r\n");
        $response = fgets($socket, 515);
        
        if (substr($response, 0, 3) != '235') {
            fclose($socket);
            throw new Exception("SMTP autentizace selhala při hesle: $response");
        }
    }
    
    fputs($socket, "MAIL FROM: <{$from}>\r\n");
    fgets($socket, 515);
    
    fputs($socket, "RCPT TO: <{$to}>\r\n");
    fgets($socket, 515);
    
    fputs($socket, "DATA\r\n");
    fgets($socket, 515);
    
    $boundary = md5(time());
    $fromHeader = $fromName ? "{$fromName} <{$from}>" : "<{$from}>";
    
    fputs($socket, "From: {$fromHeader}\r\n");
    fputs($socket, "To: <{$to}>\r\n");
    fputs($socket, "Subject: {$subject}\r\n");
    if ($replyTo) {
        fputs($socket, "Reply-To: {$replyTo}\r\n");
    }
    fputs($socket, "Message-ID: <" . time() . "." . md5($to . $from) . "@jirka086.is-a.dev>\r\n");
    fputs($socket, "List-Unsubscribe: <mailto:admin@jirka086.is-a.dev?subject=unsubscribe>\r\n");
    fputs($socket, "X-Mailer: PHP/" . phpversion() . "\r\n");
    fputs($socket, "MIME-Version: 1.0\r\n");
    fputs($socket, "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n");
    fputs($socket, "\r\n");
    
    fputs($socket, "--{$boundary}\r\n");
    fputs($socket, "Content-Type: text/plain; charset=UTF-8\r\n\r\n");
    fputs($socket, $textBody . "\r\n");
    
    fputs($socket, "--{$boundary}\r\n");
    fputs($socket, "Content-Type: text/html; charset=UTF-8\r\n\r\n");
    fputs($socket, $htmlBody . "\r\n");
    
    fputs($socket, "--{$boundary}--\r\n");
    fputs($socket, "\r\n.\r\n");
    fgets($socket, 515);
    
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    
    return true;
}

// Translations
$translations = [
    'cs' => [
        'email_title' => 'Zpráva odeslána',
        'email_subtitle' => 'Děkuji za váš zájem',
        'greeting' => 'Ahoj',
        'thanks_msg' => 'Děkuji za odeslání zprávy. Vaše zpráva byla úspěšně přijata a brzy se vám ozvu!',
        'summary_title' => 'Shrnutí zprávy',
        'subject_label' => 'Předmět',
        'email_label' => 'Váš email',
        'message_label' => 'Vaše zpráva',
        'response_time' => 'Obvykle odpovídám do 24-48 hodin',
        'button_text' => 'Navštívit portfolio',
        'signature' => 'Developer & IT Specialist',
        'admin_title' => 'Nová zpráva z portfolia',
        'name_label' => 'Jméno',
    ],
    'en' => [
        'email_title' => 'Message Sent',
        'email_subtitle' => 'Thank you for your interest',
        'greeting' => 'Hi',
        'thanks_msg' => 'Thank you for sending a message. Your message has been successfully received and I will get back to you soon!',
        'summary_title' => 'Message Summary',
        'subject_label' => 'Subject',
        'email_label' => 'Your email',
        'message_label' => 'Your message',
        'response_time' => 'I usually respond within 24-48 hours',
        'button_text' => 'Visit portfolio',
        'signature' => 'Developer & IT Specialist',
        'admin_title' => 'New message from portfolio',
        'name_label' => 'Name',
    ]
];

$t = $translations[$language];

// Plain text versions
$admin_text = "=================================================\n" . $t['admin_title'] . "\n=================================================\n\n" . $t['name_label'] . ": " . $name . "\n" . $t['email_label'] . ": " . $email . "\n" . $t['subject_label'] . ": " . $subject . "\n\n" . $t['message_label'] . ":\n" . str_repeat("-", 50) . "\n" . $message . "\n" . str_repeat("-", 50) . "\n\nContact Form Submission - Portfolio Website\nTimestamp: " . date('Y-m-d H:i:s') . "\njirka086.is-a.dev";

$confirmation_text = $t['greeting'] . " " . $name . ",\n\n" . $t['thanks_msg'] . "\n\n" . $t['summary_title'] . ":\n" . str_repeat("-", 50) . "\n" . $t['subject_label'] . ": " . $subject . "\n\n" . $t['message_label'] . ":\n" . $message . "\n" . str_repeat("-", 50) . "\n\n" . $t['response_time'] . "\n\nThank you again for reaching out. I look forward to working with you!\n\nBest regards,\nJirka086\n\nPortfolio: https://jirka086.is-a.dev\nDirect Contact: admin@jirka086.is-a.dev";

// HTML emails matching website design exactly
$admin_html = '<!DOCTYPE html><html lang="' . $language . '"><head><meta charset="UTF-8"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"></head><body style="margin:0;padding:0;font-family:Inter,sans-serif;background:linear-gradient(135deg,#1a2744 0%,#1e2236 50%,#2a1e36 100%)"><table width="100%" cellpadding="0" cellspacing="0" style="padding:60px 20px"><tr><td align="center"><table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:rgba(20,30,50,0.6);border:1px solid rgba(255,255,255,0.15);border-radius:16px;overflow:hidden"><tr><td style="padding:50px 40px;text-align:center"><h1 style="margin:0 0 10px;color:#fff;font-size:32px;font-weight:600;letter-spacing:-0.5px">' . htmlspecialchars($t['admin_title']) . '</h1><div style="width:60px;height:3px;background:linear-gradient(90deg,hsl(217,51%,45%),hsl(315,45%,55%));margin:0 auto;border-radius:2px"></div></td></tr><tr><td style="padding:0 40px 50px"><table width="100%" cellpadding="0" cellspacing="0" style="border-spacing:0 15px"><tr><td style="padding:20px 24px;background:rgba(15,25,45,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px"><p style="margin:0 0 8px;font-size:11px;font-weight:600;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:1.2px">' . htmlspecialchars($t['name_label']) . '</p><p style="margin:0;font-size:17px;color:#fff;font-weight:500">' . htmlspecialchars($name) . '</p></td></tr><tr><td style="padding:20px 24px;background:rgba(15,25,45,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px"><p style="margin:0 0 8px;font-size:11px;font-weight:600;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:1.2px">' . htmlspecialchars($t['email_label']) . '</p><p style="margin:0;font-size:17px;color:#fff;font-weight:500">' . htmlspecialchars($email) . '</p></td></tr><tr><td style="padding:20px 24px;background:rgba(15,25,45,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px"><p style="margin:0 0 8px;font-size:11px;font-weight:600;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:1.2px">' . htmlspecialchars($t['subject_label']) . '</p><p style="margin:0;font-size:17px;color:#fff;font-weight:500">' . htmlspecialchars($subject) . '</p></td></tr><tr><td style="padding:20px 24px;background:rgba(15,25,45,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px"><p style="margin:0 0 12px;font-size:11px;font-weight:600;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:1.2px">' . htmlspecialchars($t['message_label']) . '</p><p style="margin:0;font-size:15px;color:rgba(255,255,255,0.85);line-height:1.7;white-space:pre-wrap">' . htmlspecialchars($message) . '</p></td></tr></table></td></tr><tr><td style="padding:30px;text-align:center;border-top:1px solid rgba(255,255,255,0.1)"><p style="margin:0;color:rgba(255,255,255,0.5);font-size:13px">jirka086.is-a.dev</p></td></tr></table></td></tr></table></body></html>';

$confirm_html = '<!DOCTYPE html><html lang="' . $language . '"><head><meta charset="UTF-8"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"></head><body style="margin:0;padding:0;font-family:Inter,sans-serif;background:linear-gradient(135deg,#1a2744 0%,#1e2236 30%,#2a1e36 70%,#2e1d3a 100%)"><table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 20px"><tr><td align="center"><table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:rgba(30,42,66,0.75);border:1px solid rgba(255,255,255,0.15);border-radius:16px;overflow:hidden"><tr><td style="padding:40px 40px 30px;text-align:center"><img src="https://jirka086.is-a.dev/images/profile.webp" alt="Jirka086" style="width:80px;height:80px;border-radius:50%;margin:0 auto 20px;display:block;border:3px solid rgba(255,255,255,0.1)"><h1 style="margin:0 0 8px;color:#fff;font-size:28px;font-weight:600;letter-spacing:-0.5px">' . htmlspecialchars($t['email_title']) . '</h1><p style="margin:0;color:rgba(255,255,255,0.65);font-size:15px">' . htmlspecialchars($t['email_subtitle']) . '</p></td></tr><tr><td style="padding:0 40px 40px"><p style="margin:0 0 6px;color:#fff;font-size:19px;font-weight:600">' . htmlspecialchars($t['greeting']) . ' ' . htmlspecialchars($name) . ',</p><p style="margin:0 0 30px;color:rgba(255,255,255,0.7);font-size:15px;line-height:1.6">' . htmlspecialchars($t['thanks_msg']) . '</p><table width="100%" cellpadding="0" cellspacing="0" style="background:rgba(35,50,75,0.5);border:1px solid rgba(255,255,255,0.12);border-radius:12px;padding:24px;margin:0 0 25px"><tr><td><p style="margin:0 0 18px;color:rgba(255,255,255,0.7);font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.3px">' . htmlspecialchars($t['summary_title']) . '</p><table width="100%" cellpadding="0" cellspacing="0"><tr><td style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.1)"><p style="margin:0;font-size:12px;color:rgba(255,255,255,0.6);font-weight:500">' . htmlspecialchars($t['subject_label']) . '</p><p style="margin:6px 0 0;font-size:15px;color:#fff;font-weight:500">' . htmlspecialchars($subject) . '</p></td></tr><tr><td style="padding:16px 0 0"><p style="margin:0 0 8px;font-size:12px;color:rgba(255,255,255,0.6);font-weight:500">' . htmlspecialchars($t['message_label']) . '</p><p style="margin:0;font-size:14px;color:rgba(255,255,255,0.9);line-height:1.7;white-space:pre-wrap">' . htmlspecialchars($message) . '</p></td></tr></table></td></tr></table><p style="margin:0 0 30px;color:rgba(255,255,255,0.65);font-size:14px;text-align:center;line-height:1.5">' . htmlspecialchars($t['response_time']) . '</p><table width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid rgba(255,255,255,0.1);padding-top:25px"><tr><td style="text-align:center"><p style="margin:0 0 4px;color:rgba(255,255,255,0.85);font-size:16px;font-weight:600">Jirka086</p><p style="margin:0 0 6px;color:rgba(255,255,255,0.55);font-size:13px">' . htmlspecialchars($t['signature']) . '</p><p style="margin:0;color:rgba(255,255,255,0.45);font-size:12px">jirka086.is-a.dev</p></td></tr></table></td></tr></table></td></tr></table></body></html>';

try {
    sendEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $smtp_from, $smtp_to, "Portfolio: " . $subject, $admin_html, $admin_text, $email, 'Jirka086 Portfolio');
    
    sendEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $smtp_from, $email, $language === 'en' ? "Message Confirmation - Jirka086" : "Potvrzeni prijeti zpravy - Jirka086", $confirm_html, $confirmation_text, null, 'Jirka086');
    
    // Update rate limit tracker
    $rate_limit_data[$user_ip] = $current_time;
    file_put_contents($rate_limit_file, json_encode($rate_limit_data));
    
    echo json_encode([
        'success' => true,
        'message' => $language === 'en' ? 'Message sent successfully! We sent a confirmation to your email. I will get back to you soon.' : 'Zpráva byla úspěšně odeslána! Na váš email jsme poslali potvrzení. Brzy se vám ozvu.'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Chyba při odesílání zprávy: ' . $e->getMessage()]);
}
?>
