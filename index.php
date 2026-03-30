<?php
// İmza: Umut Yılmaz (https://github.com/umtylmzl)

if (isset($_GET['username'])) {
  $username = trim((string) @$_GET['username']);
  // Instagram username sadece belirli karakterler içermeli; gereksiz / riskli karakterleri temizliyoruz.
  $username = preg_replace('/[^a-zA-Z0-9._]/', '', $username);

  $sonuc = false;
  $kullanicibilgileri = null;

  if ($username !== '') {
    // Önce Apify ile Instagram verisini çekmeyi deniyoruz.
    $apifyToken = getenv('APIFY_TOKEN') ?: ($_ENV['APIFY_TOKEN'] ?? '');
    if (is_string($apifyToken)) {
      $apifyToken = trim($apifyToken);
    }

    if (!empty($apifyToken)) {
      $directUrl = "https://www.instagram.com/" . $username . "/";
      $apifyInput = [
        'directUrls' => [$directUrl],
        'resultsType' => 'details',
        'resultsLimit' => 1,
        'addParentData' => false,
      ];

      $endpoint = "https://api.apify.com/v2/acts/apify~instagram-api-scraper/run-sync-get-dataset-items?token=" . urlencode($apifyToken);
      $payloadJson = json_encode($apifyInput, JSON_UNESCAPED_SLASHES);
      $apifyResp = null;

      if (function_exists('curl_init')) {
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_CONNECTTIMEOUT => 10,
          CURLOPT_TIMEOUT => 120,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => $payloadJson,
          CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
          ],
        ]);
        $apifyResp = @curl_exec($ch);
        $httpCode = (int) @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
      } else {
        $context = stream_context_create([
          'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
            'content' => $payloadJson,
            'timeout' => 120,
          ],
        ]);
        $apifyResp = @file_get_contents($endpoint, false, $context);
        $httpCode = 200;
      }

      if (!empty($apifyResp) && (empty($httpCode) || ($httpCode >= 200 && $httpCode < 500))) {
        $decoded = json_decode($apifyResp, true);
        $items = null;
        if (is_array($decoded)) {
          if (isset($decoded['items']) && is_array($decoded['items'])) {
            $items = $decoded['items'];
          } elseif (isset($decoded['data']['items']) && is_array($decoded['data']['items'])) {
            $items = $decoded['data']['items'];
          } elseif (isset($decoded[0])) {
            $items = $decoded;
          }
        }

        $item = null;
        if (is_array($items) && !empty($items)) {
          $item = $items[0];
        }

        if (is_array($item)) {
          $getFirst = function (array $arr, array $keys) {
            foreach ($keys as $k) {
              if (array_key_exists($k, $arr)) {
                $v = $arr[$k];
                if ($v !== null && $v !== '') {
                  return $v;
                }
              }
            }
            return null;
          };

          $target = $item;
          if (isset($item['user']) && is_array($item['user'])) {
            $target = $item['user'];
          } elseif (isset($item['profile']) && is_array($item['profile'])) {
            $target = $item['profile'];
          } elseif (isset($item['owner']) && is_array($item['owner'])) {
            $target = $item['owner'];
          }

          $profilePicHD = $getFirst($target, ['profile_pic_url_hd', 'profilePicUrlHD', 'profilePicUrlHd', 'profile_pic_url', 'profilePicUrl']);
          $profilePic = $getFirst($target, ['profile_pic_url', 'profilePicUrl', 'profile_pic_url_hd', 'profilePicUrlHD']);
          if ($profilePicHD === null && $profilePic !== null) {
            $profilePicHD = $profilePic;
          }
          if ($profilePic === null && $profilePicHD !== null) {
            $profilePic = $profilePicHD;
          }

          $fullName = $getFirst($target, ['full_name', 'fullName', 'name']);
          $bio = $getFirst($target, ['biography', 'bio']);

          $followers = 0;
          if (isset($target['followersCount'])) {
            $followers = (int) $target['followersCount'];
          } elseif (isset($target['edge_followed_by']['count'])) {
            $followers = (int) $target['edge_followed_by']['count'];
          } elseif (isset($target['followers'])) {
            $followers = (int) $target['followers'];
          }

          $following = 0;
          if (isset($target['followsCount'])) {
            $following = (int) $target['followsCount'];
          } elseif (isset($target['edge_follow']['count'])) {
            $following = (int) $target['edge_follow']['count'];
          } elseif (isset($target['follows'])) {
            $following = (int) $target['follows'];
          }

          $postCount = 0;
          if (isset($target['postsCount'])) {
            $postCount = (int) $target['postsCount'];
          } elseif (isset($target['mediaCount'])) {
            $postCount = (int) $target['mediaCount'];
          } elseif (isset($target['media_count'])) {
            $postCount = (int) $target['media_count'];
          } elseif (isset($target['edge_owner_to_timeline_media']['count'])) {
            $postCount = (int) $target['edge_owner_to_timeline_media']['count'];
          }

          if (!empty($profilePicHD) || !empty($fullName) || !empty($bio)) {
            $sonuc = true;
            $kullanicibilgileri = [
              'profile_pic_url_hd' => (string) ($profilePicHD ?? ''),
              'profile_pic_url' => (string) ($profilePic ?? $profilePicHD ?? ''),
              'full_name' => (string) ($fullName ?? $username),
              'biography' => (string) ($bio ?? ''),
              'edge_followed_by' => ['count' => $followers],
              'edge_follow' => ['count' => $following],
              'edge_owner_to_timeline_media' => ['count' => $postCount],
            ];
          }
        }
      }
    }

    // Apify başarısızsa: önce daha direkt profil endpoint'i dene
    if (!$sonuc) {
      $apiUrl = "https://i.instagram.com/api/v1/users/web_profile_info/?username=" . urlencode($username);
      $veri = null;

      $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0 Safari/537.36';
      $referer = "https://www.instagram.com/" . $username . "/";

      $headers = [
        'Accept: application/json',
        'x-ig-app-id: 936619743392459',
        'User-Agent: ' . $ua,
        'Referer: ' . $referer,
      ];

      if (function_exists('curl_init')) {
        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_MAXREDIRS => 3,
          CURLOPT_CONNECTTIMEOUT => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_HTTPHEADER => $headers,
        ]);
        $veri = @curl_exec($ch);
        $httpCode = (int) @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
      } else {
        // Curl yoksa: basit stream context ile istek atıyoruz.
        $context = stream_context_create([
          'http' => [
            'method' => 'GET',
            'header' => implode("\r\n", $headers) . "\r\n",
            'timeout' => 30,
          ],
          'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
          ],
        ]);
        $veri = @file_get_contents($apiUrl, false, $context);
        $httpCode = 200;
      }

      if (!empty($veri) && $httpCode >= 200 && $httpCode < 500) {
        $decoded = json_decode($veri, true);
        $user = $decoded['data']['user'] ?? $decoded['user'] ?? null;

        if (is_array($user)) {
          $fullName = $user['full_name'] ?? $user['fullName'] ?? $user['name'] ?? $username;
          $bio = $user['biography'] ?? $user['bio'] ?? '';

          $profilePicHD =
            $user['profile_pic_url_hd'] ??
            $user['profile_pic_urlHd'] ??
            $user['profilePicUrlHD'] ??
            ($user['hd_profile_pic_url_info']['url'] ?? null);

          $profilePic =
            $user['profile_pic_url'] ??
            $user['profilePicUrl'] ??
            ($user['profile_pic_url_info']['url'] ?? ($profilePicHD ?? null));

          $followers =
            (isset($user['follower_count']) ? (int) $user['follower_count'] : null);
          if ($followers === null && isset($user['edge_followed_by']['count'])) {
            $followers = (int) $user['edge_followed_by']['count'];
          }
          if ($followers === null && isset($user['followers'])) {
            $followers = (int) $user['followers'];
          }
          if ($followers === null) {
            $followers = 0;
          }

          $following =
            (isset($user['following_count']) ? (int) $user['following_count'] : null);
          if ($following === null && isset($user['edge_follow']['count'])) {
            $following = (int) $user['edge_follow']['count'];
          }
          if ($following === null && isset($user['follows'])) {
            $following = (int) $user['follows'];
          }
          if ($following === null) {
            $following = 0;
          }

          $postCount =
            (isset($user['media_count']) ? (int) $user['media_count'] : null);
          if ($postCount === null && isset($user['edge_owner_to_timeline_media']['count'])) {
            $postCount = (int) $user['edge_owner_to_timeline_media']['count'];
          }
          if ($postCount === null) {
            $postCount = 0;
          }

          if (!empty($profilePicHD) || !empty($profilePic)) {
            $sonuc = true;
            $kullanicibilgileri = [
              'profile_pic_url_hd' => (string) ($profilePicHD ?? $profilePic ?? ''),
              'profile_pic_url' => (string) ($profilePic ?? $profilePicHD ?? ''),
              'full_name' => (string) $fullName,
              'biography' => (string) $bio,
              'edge_followed_by' => ['count' => $followers],
              'edge_follow' => ['count' => $following],
              'edge_owner_to_timeline_media' => ['count' => $postCount],
            ];
          }
        }
      }
    }

    // Hâlâ başarısızsa: legacy Instagram HTML ayrıştırma
    if (!$sonuc) {
      $url = "https://www.instagram.com/" . $username . "/";
      $veri = null;

      if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_MAXREDIRS => 5,
          CURLOPT_CONNECTTIMEOUT => 10,
          CURLOPT_TIMEOUT => 20,
          CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0 Safari/537.36',
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: tr-TR,tr;q=0.9,en;q=0.8',
          ],
        ]);
        $veri = @curl_exec($ch);
        $httpCode = (int) @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!empty($veri) && $httpCode >= 200 && $httpCode < 400) {
          $kullanicibilgileri = null;
          $sharedStartMarker = 'window._sharedData = ';
          $sharedStart = strpos($veri, $sharedStartMarker);
          if ($sharedStart !== false) {
            $sharedStart += strlen($sharedStartMarker);
            $sharedEnd = strpos($veri, ';</script>', $sharedStart);
            if ($sharedEnd !== false) {
              $sharedJson = substr($veri, $sharedStart, $sharedEnd - $sharedStart);
              $decoded = json_decode($sharedJson, true);
              $kullanicibilgileri = $decoded['entry_data']['ProfilePage'][0]['graphql']['user'] ?? null;
            }
          }
          if (is_array($kullanicibilgileri) && !empty($kullanicibilgileri)) {
            $sonuc = true;
          }
        }
      } else {
        // Curl yoksa: basit stream context ile UA set edip https çekiyoruz.
        $context = stream_context_create([
          'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0\r\nAccept-Language: tr-TR,tr;q=0.9,en;q=0.8\r\n",
          ],
          'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
          ],
        ]);
        $veri = @file_get_contents($url, false, $context);
        if (!empty($veri)) {
          $sharedStartMarker = 'window._sharedData = ';
          $sharedStart = strpos($veri, $sharedStartMarker);
          if ($sharedStart !== false) {
            $sharedStart += strlen($sharedStartMarker);
            $sharedEnd = strpos($veri, ';</script>', $sharedStart);
            if ($sharedEnd !== false) {
              $sharedJson = substr($veri, $sharedStart, $sharedEnd - $sharedStart);
              $decoded = json_decode($sharedJson, true);
              $kullanicibilgileri = $decoded['entry_data']['ProfilePage'][0]['graphql']['user'] ?? null;
            }
          }
          if (is_array($kullanicibilgileri) && !empty($kullanicibilgileri)) {
            $sonuc = true;
          }
        }
      }
    }
  }
} else {
  $sonuc=false;
}
?>
<?php include 'ui-tailwind.php'; ?>

