<?php
// Basit resim proxy: Instagram CDN resimlerini aynı origin'den servise çevirir.
// Amaç: Bazı tarayıcılarda cross-origin embed engellerini (NotSameOrigin/CORP) aşmak.

$src = isset($_GET['src']) ? (string)$_GET['src'] : '';
$src = trim($src);

if ($src === '') {
  http_response_code(400);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Missing src parameter';
  exit;
}

// SSRF koruması: yalnızca Instagram görsel CDN'leri.
// Host whitelist'i gerçek hayatta çok değiştiği için regex tabanlı geniş izin veriyoruz.
$allowedHosts = [
  'instagram.com',
  'www.instagram.com',
];

$parts = parse_url($src);
$host = isset($parts['host']) ? (string)$parts['host'] : '';
$scheme = isset($parts['scheme']) ? (string)$parts['scheme'] : '';

// alt domain yakalama (örn. scontent-xyz.cdninstagram.com / instagram.fala2-*.fna.fbcdn.net)
$isAllowedHost = false;
if ($host !== '') {
  foreach ($allowedHosts as $h) {
    if (strcasecmp($host, $h) === 0) {
      $isAllowedHost = true;
      break;
    }
  }

  if (!$isAllowedHost) {
    $isAllowedHost = (bool)preg_match('/(^|\\.)cdninstagram\\.com$/i', $host);
  }
  if (!$isAllowedHost) {
    $isAllowedHost = (bool)preg_match('/(^|\\.)fbcdn\\.net$/i', $host);
  }
}

if ($scheme !== 'http' && $scheme !== 'https') {
  http_response_code(400);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Invalid scheme';
  exit;
}

if (!$isAllowedHost) {
  http_response_code(403);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Host not allowed: ' . $host;
  exit;
}

$ch = curl_init($src);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_MAXREDIRS => 5,
  CURLOPT_CONNECTTIMEOUT => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0 Safari/537.36',
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
]);

$bin = @curl_exec($ch);
$contentType = (string)@curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = (int)@curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($bin === false || $httpCode < 200 || $httpCode >= 400) {
  http_response_code(502);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Failed to fetch image';
  exit;
}

if ($contentType === '') {
  $contentType = 'application/octet-stream';
}

header('Content-Type: ' . $contentType);
header('Cache-Control: public, max-age=3600');
header('X-Content-Type-Options: nosniff');
echo $bin;
exit;

