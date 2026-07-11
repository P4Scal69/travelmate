<?php
header('Content-Type: application/json');
header('Cache-Control: no-store');

$lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
$lon = isset($_GET['lon']) ? (float)$_GET['lon'] : null;

if ($lat === null || $lon === null) {
    http_response_code(400);
    echo json_encode(['error' => 'lat and lon required']);
    exit;
}

$url = 'https://api.open-meteo.com/v1/forecast?latitude=' . $lat . '&longitude=' . $lon
     . '&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=auto';

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_USERAGENT => 'TravelMate/1.0',
]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($resp === false || $code !== 200) {
    http_response_code(502);
    echo json_encode(['error' => 'Weather service unavailable']);
    exit;
}

$data = json_decode($resp, true);
$current = $data['current'] ?? [];

$codes = [
    0 => 'Clear sky', 1 => 'Mainly clear', 2 => 'Partly cloudy', 3 => 'Overcast',
    45 => 'Fog', 48 => 'Rime fog', 51 => 'Light drizzle', 53 => 'Drizzle', 55 => 'Dense drizzle',
    61 => 'Light rain', 63 => 'Rain', 65 => 'Heavy rain', 71 => 'Light snow', 73 => 'Snow', 75 => 'Heavy snow',
    80 => 'Rain showers', 81 => 'Rain showers', 82 => 'Violent showers', 95 => 'Thunderstorm', 99 => 'Thunderstorm',
];

echo json_encode([
    'temperature' => $current['temperature_2m'] ?? null,
    'humidity' => $current['relative_humidity_2m'] ?? null,
    'wind' => $current['wind_speed_10m'] ?? null,
    'weather_code' => $current['weather_code'] ?? null,
    'description' => $codes[$current['weather_code'] ?? ''] ?? 'Unknown',
    'time' => $current['time'] ?? null,
]);
