<?php
// İmza: Umut Yılmaz (https://github.com/umtylmzl)

$hasUsername  = isset($_GET['username']) && (string)@$_GET['username'] !== '';
$hasResult    = $hasUsername && isset($sonuc) && $sonuc;
$isNotFound   = $hasUsername && isset($sonuc) && !$sonuc;
$usernameValue = htmlspecialchars((string)@$_GET['username'], ENT_QUOTES, 'UTF-8');

function formatCount($n): string {
    $n = (int)$n;
    if ($n >= 1_000_000_000) return round($n / 1_000_000_000, 1) . 'Mld';
    if ($n >= 1_000_000)     return round($n / 1_000_000, 1) . 'M';
    if ($n >= 1_000)         return round($n / 1_000, 1) . 'B';
    return number_format($n, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html class="light" lang="tr">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>InstaPP – Instagram Profil Resmi Görüntüleyici</title>
  <meta name="description" content="Instagram profil fotoğrafını HD kalitesinde görüntüle ve indir. Umut Yılmaz tarafından geliştirilmiştir."/>
  <meta name="author" content="Umut Yılmaz (https://github.com/umtylmzl)"/>

  <!-- Favicon -->
  <link rel="icon" href="img/favicon.ico" sizes="any"/>
  <link rel="apple-touch-icon" href="img/apple-touch-icon.png"/>

  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=Merriweather:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ig-blue':    '#0095f6',
            'ig-blue-dk': '#1877f2',
            'ig-pink':    '#d6249f',
            'ig-orange':  '#fd5949',
            'ig-yellow':  '#fdf497',
            'ig-purple':  '#285AEB',
            'outline':    '#dbdbdb',
            'surface':    '#fafafa',
            'surface-low':'#f5f5f5',
            'on-surface': '#262626',
            'on-muted':   '#8e8e8e',
          },
          fontFamily: {
            headline: ['Archivo', 'sans-serif'],
            body:     ['Inter', 'sans-serif'],
            serif:    ['Merriweather', 'serif'],
          }
        }
      }
    }
  </script>
  <style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
    .ig-ring { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); }
    .ig-btn  { background: linear-gradient(135deg, #f7971e 0%, #ff5f6d 50%, #d6249f 100%); }
    .ig-text { background: linear-gradient(135deg, #f7971e, #ff5f6d, #d6249f, #285AEB); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    input[type="text"]:focus { outline: none; box-shadow: none; }
    #imgModal { display: none; }
    #imgModal.open { display: flex !important; }
    #imgModal { animation: none; }
    #imgModal.open { animation: modalIn .18s ease; }
    @keyframes modalIn { from { opacity:0; transform:scale(.96); } to { opacity:1; transform:scale(1); } }
  </style>
</head>

<body class="bg-surface text-on-surface font-body antialiased selection:bg-ig-blue/10">

<!-- ────────── NAV ────────── -->
<header class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-lg border-b border-outline">
  <nav class="max-w-7xl mx-auto px-5 h-14 flex items-center justify-between">
    <!-- Logo + Marka -->
    <a href="index.php" class="flex items-center gap-2.5 no-underline">
      <img src="img/icon-192.png" alt="InstaPP logo" class="w-7 h-7 rounded-lg object-cover"/>
      <div class="leading-tight">
        <span class="font-headline font-extrabold text-base tracking-tight ig-text">InstaPP</span>
        <span class="hidden sm:block text-[9px] font-headline text-on-muted tracking-wide leading-none">by Umut Yılmaz</span>
      </div>
    </a>
    <!-- Sağ: Hakkında + GitHub -->
    <div class="flex items-center gap-5">
      <a href="hakkimizda.php"
         class="hidden sm:flex items-center gap-1 text-xs font-headline font-semibold text-on-muted hover:text-on-surface transition-colors">
        <span class="material-symbols-outlined text-base">info</span>
        Hakkında
      </a>
      <a href="https://github.com/umtylmzl" target="_blank" rel="noopener"
         class="flex items-center gap-1 text-xs font-headline font-semibold text-on-muted hover:text-on-surface transition-colors">
        <span class="material-symbols-outlined text-base">code</span>
        <span class="hidden sm:inline">github.com/umtylmzl</span>
      </a>
    </div>
  </nav>
</header>

<main class="pt-14">

  <!-- ────────── HERO / SEARCH ────────── -->
  <section class="relative overflow-hidden bg-white border-b border-outline">
    <div class="absolute -top-56 -right-56 w-[500px] h-[500px] ig-ring rounded-full blur-3xl opacity-[0.07] pointer-events-none"></div>
    <div class="relative max-w-3xl mx-auto px-5 py-16 md:py-24 text-center space-y-6">

      <!-- Badge -->
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-low border border-outline">
        <img src="img/icon-192.png" alt="" class="w-4 h-4 rounded"/>
        <span class="text-[10px] uppercase tracking-widest font-headline font-bold text-on-muted">Instagram HD Viewer</span>
      </div>

      <h1 class="text-4xl md:text-5xl font-headline font-extrabold leading-[1.1] tracking-tight text-on-surface">
        Instagram Profil Resmini<br/>
        <span class="ig-text">HD Kalitesinde</span> Görüntüle
      </h1>

      <p class="text-base text-on-muted font-serif leading-relaxed max-w-lg mx-auto">
        Kullanıcı adını yaz, profil fotoğrafını tam çözünürlükte görüntüle ve indir. Gizli profiller dahil.
      </p>

      <!-- Search Box -->
      <div class="mt-8 flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
        <label class="flex-1 flex items-center gap-3 bg-surface-low border border-outline rounded-xl px-4 focus-within:border-ig-blue focus-within:bg-white transition-all">
          <span class="material-symbols-outlined text-on-muted flex-shrink-0">alternate_email</span>
          <input
            id="username_input"
            type="text"
            value="<?php echo $usernameValue; ?>"
            placeholder="kullanici_adi"
            autocomplete="off"
            class="flex-1 py-3.5 bg-transparent border-none text-on-surface placeholder:text-on-muted font-headline text-sm"
          />
        </label>
        <button
          id="gosterbutton"
          type="button"
          class="ig-btn px-7 py-3.5 rounded-xl font-headline font-bold text-sm text-white transition-all active:scale-95 whitespace-nowrap flex items-center justify-center gap-2 hover:opacity-90"
        >
          <span class="material-symbols-outlined text-base">search</span>
          Görüntüle
        </button>
      </div>

      <?php if ($isNotFound): ?>
      <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 border border-red-100 rounded-xl text-red-600 text-sm font-headline font-semibold">
        <span class="material-symbols-outlined text-base">person_off</span>
        <strong><?php echo $usernameValue; ?></strong>&nbsp;bulunamadı
      </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- ────────── 3 KOLON: Sol Reklam | İçerik | Sağ Reklam ────────── -->
  <div class="max-w-7xl mx-auto px-4 py-8 flex items-start gap-6">

    <!-- SOL KENAR REKLAM ALANI GİRİŞ -->
    <aside class="hidden xl:flex flex-col items-center w-44 flex-shrink-0 sticky top-20">
      <!--  ALTTAKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
      <div class="rounded-xl border border-dashed border-outline/60 bg-surface-low w-[160px] flex items-center justify-center" style="height:600px;">
        <span class="text-[8px] text-outline font-headline tracking-wider uppercase" style="writing-mode:vertical-rl;">reklam</span>
      </div>
      <!--  ÜSTTEKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
    </aside>
    <!-- SOL KENAR REKLAM ALANI ÇIKIŞ -->

    <!-- ORTA İÇERİK -->
    <section class="flex-1 min-w-0 space-y-5">

      <!-- REKLAM ALANI GİRİŞ (üst şerit) -->
      <div class="flex justify-center">
        <!--  ALTTAKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
        <div class="rounded-xl border border-dashed border-outline/60 bg-surface-low w-full flex items-center justify-center" style="height:90px; max-width:728px;">
          <span class="text-[8px] text-outline font-headline tracking-wider uppercase">reklam</span>
        </div>
        <!--  ÜSTTEKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
      </div>
      <!-- REKLAM ALANI ÇIKIŞ -->

      <!-- ── Hakkımızda Teaser Kartı ── -->
      <a href="hakkimizda.php"
         class="group flex items-center justify-between gap-4 bg-white border border-outline rounded-2xl px-5 py-4 hover:border-ig-blue/40 hover:shadow-sm transition-all">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#f7971e,#ff5f6d,#d6249f);">
            <span class="material-symbols-outlined text-white text-lg">person</span>
          </div>
          <div>
            <div class="font-headline font-bold text-sm text-on-surface">Umut Yılmaz tarafından oluşturulmuştur</div>
            <div class="text-xs text-on-muted font-headline mt-0.5">Geliştirici hakkında bilgi al, diğer projelerini incele →</div>
          </div>
        </div>
        <span class="material-symbols-outlined text-on-muted group-hover:text-on-surface transition-colors flex-shrink-0">chevron_right</span>
      </a>

      <?php if ($hasResult):
        $picHD     = (string)($kullanicibilgileri['profile_pic_url_hd'] ?? '');
        $pic       = (string)($kullanicibilgileri['profile_pic_url']    ?? $picHD);
        $name      = htmlspecialchars((string)($kullanicibilgileri['full_name']          ?? $usernameValue), ENT_QUOTES, 'UTF-8');
        $bio       = htmlspecialchars((string)($kullanicibilgileri['biography']          ?? ''), ENT_QUOTES, 'UTF-8');
        $followers = (int)($kullanicibilgileri['edge_followed_by']['count'] ?? 0);
        $following = (int)($kullanicibilgileri['edge_follow']['count']      ?? 0);
        $proxyPic  = 'img-proxy.php?src=' . urlencode($pic);
        $proxyHD   = 'img-proxy.php?src=' . urlencode($picHD);
        $picHD_esc = htmlspecialchars($picHD, ENT_QUOTES, 'UTF-8');
      ?>

      <!-- ── Profil Kartı ── -->
      <div class="bg-white rounded-3xl border border-outline overflow-hidden">
        <div class="h-24 w-full ig-ring"></div>
        <div class="px-5 md:px-8 pb-6">
          <div class="flex items-end justify-between -mt-12 mb-5">
            <!-- Avatar -->
            <div class="ig-ring p-[3px] rounded-full shadow-lg">
              <div class="bg-white p-[3px] rounded-full">
                <div class="w-24 h-24 rounded-full overflow-hidden border border-outline">
                  <img alt="<?php echo $name; ?>" src="<?php echo $proxyPic; ?>" class="w-full h-full object-cover"/>
                </div>
              </div>
            </div>
            <!-- Butonlar -->
            <div class="flex items-center gap-2 pb-1">
              <a href="<?php echo $picHD_esc; ?>" target="_blank" rel="noopener"
                 class="ig-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-xs font-headline font-bold hover:opacity-90 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-sm">open_in_new</span>
                HD Aç
              </a>
              <a href="<?php echo $proxyHD; ?>" download="<?php echo $usernameValue; ?>_profile.jpg"
                 class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-surface-low border border-outline text-on-surface text-xs font-headline font-bold hover:bg-white transition-all active:scale-95">
                <span class="material-symbols-outlined text-sm">download</span>
                İndir
              </a>
            </div>
          </div>

          <!-- Bilgiler -->
          <div class="space-y-0.5 mb-5">
            <h2 class="font-headline font-extrabold text-lg text-on-surface"><?php echo $name; ?></h2>
            <p class="text-sm text-on-muted font-headline">@<?php echo $usernameValue; ?></p>
            <?php if ($bio): ?>
            <p class="text-sm text-on-surface leading-relaxed pt-2"><?php echo nl2br($bio); ?></p>
            <?php endif; ?>
          </div>

          <!-- İstatistikler -->
          <div class="flex gap-8 border-t border-outline pt-4">
            <div>
              <span class="font-headline font-extrabold text-on-surface"><?php echo formatCount((int)($kullanicibilgileri['edge_owner_to_timeline_media']['count'] ?? 0)); ?></span>
              <span class="text-xs text-on-muted font-headline ml-1">gönderi</span>
            </div>
            <div>
              <span class="font-headline font-extrabold text-on-surface"><?php echo formatCount($followers); ?></span>
              <span class="text-xs text-on-muted font-headline ml-1">takipçi</span>
            </div>
            <div>
              <span class="font-headline font-extrabold text-on-surface"><?php echo formatCount($following); ?></span>
              <span class="text-xs text-on-muted font-headline ml-1">takip</span>
            </div>
          </div>
        </div>
      </div>

      <!-- REKLAM ALANI GİRİŞ (orta şerit) -->
      <div class="flex justify-center">
        <!--  ALTTAKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
        <div class="rounded-xl border border-dashed border-outline/60 bg-surface-low w-full flex items-center justify-center" style="height:90px; max-width:728px;">
          <span class="text-[8px] text-outline font-headline tracking-wider uppercase">reklam</span>
        </div>
        <!--  ÜSTTEKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
      </div>
      <!-- REKLAM ALANI ÇIKIŞ -->

      <!-- ── HD Görsel Kartı ── -->
      <div class="bg-white rounded-3xl border border-outline overflow-hidden">
        <!-- Tab -->
        <div class="flex border-b border-outline px-1">
          <div class="flex items-center gap-2 px-5 py-3.5 text-xs font-headline font-extrabold uppercase tracking-widest text-on-surface border-b-2 border-on-surface">
            <span class="material-symbols-outlined text-sm">account_circle</span>
            Profil Resmi HD
          </div>
        </div>

        <div class="p-6 md:p-10 flex flex-col items-center gap-6">
          <!-- Büyük Görsel -->
          <div class="relative group w-full max-w-sm aspect-square bg-surface-low rounded-2xl overflow-hidden border border-outline shadow-sm cursor-zoom-in"
               onclick="openModal('<?php echo addslashes($proxyHD); ?>','<?php echo addslashes($proxyHD); ?>','<?php echo addslashes($usernameValue); ?>')">
            <img alt="<?php echo $name; ?> HD" src="<?php echo $proxyHD; ?>" class="w-full h-full object-cover pointer-events-none"/>
            <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
              <span class="material-symbols-outlined text-white text-5xl drop-shadow-lg">zoom_in</span>
            </div>
          </div>

          <!-- Butonlar -->
          <div class="flex flex-col sm:flex-row gap-3 w-full max-w-sm">
            <button type="button"
               onclick="openModal('<?php echo addslashes($proxyHD); ?>','<?php echo addslashes($proxyHD); ?>','<?php echo addslashes($usernameValue); ?>')"
               class="ig-btn flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl font-headline font-bold text-sm text-white hover:opacity-90 active:scale-95 transition-all">
              <span class="material-symbols-outlined text-base">fullscreen</span>
              Tam Ekran Aç
            </button>
            <a href="<?php echo $proxyHD; ?>" download="<?php echo $usernameValue; ?>_profile_hd.jpg"
               class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl bg-surface-low border border-outline text-on-surface font-headline font-bold text-sm hover:bg-white transition-all active:scale-95">
              <span class="material-symbols-outlined text-base">download</span>
              HD İndir
            </a>
          </div>
          <p class="text-[11px] text-on-muted font-headline">Orijinal çözünürlük · 1080 × 1080 px</p>
        </div>
      </div>

      <!-- REKLAM ALANI GİRİŞ (alt şerit) -->
      <div class="flex justify-center pb-4">
        <!--  ALTTAKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
        <div class="rounded-xl border border-dashed border-outline/60 bg-surface-low w-full flex items-center justify-center" style="height:90px; max-width:728px;">
          <span class="text-[8px] text-outline font-headline tracking-wider uppercase">reklam</span>
        </div>
        <!--  ÜSTTEKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
      </div>
      <!-- REKLAM ALANI ÇIKIŞ -->

      <?php endif; /* $hasResult */ ?>

    </section>
    <!-- ORTA İÇERİK BİTİŞ -->

    <!-- SAĞ KENAR REKLAM ALANI GİRİŞ -->
    <aside class="hidden xl:flex flex-col items-center w-44 flex-shrink-0 sticky top-20">
      <!--  ALTTAKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
      <div class="rounded-xl border border-dashed border-outline/60 bg-surface-low w-[160px] flex items-center justify-center" style="height:600px;">
        <span class="text-[8px] text-outline font-headline tracking-wider uppercase" style="writing-mode:vertical-rl;">reklam</span>
      </div>
      <!--  ÜSTTEKİ KODU SİLİP REKLAM KODUNUZU EKLEYİN -->
    </aside>
    <!-- SAĞ KENAR REKLAM ALANI ÇIKIŞ -->

  </div>

</main>

<?php if ($hasResult): ?>
<!-- ────────── LIGHTBOX MODAL ────────── -->
<div id="imgModal"
     class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-label="Profil fotoğrafı tam ekran">

  <!-- İçerik kutusu -->
  <div class="relative flex flex-col items-center gap-4 max-w-[90vw] max-h-[90vh]">

    <!-- Kapat -->
    <button id="modalClose"
            class="absolute -top-3 -right-3 z-10 w-9 h-9 rounded-full bg-white/90 text-on-surface flex items-center justify-center shadow-lg hover:bg-white transition-colors"
            aria-label="Kapat">
      <span class="material-symbols-outlined text-lg">close</span>
    </button>

    <!-- Resim -->
    <img id="modalImg"
         src=""
         alt="HD Profil Fotoğrafı"
         class="max-w-full max-h-[75vh] rounded-2xl shadow-2xl object-contain"
         style="min-width:280px;"/>

    <!-- Alt butonlar -->
    <div class="flex items-center gap-3">
      <a id="modalDownload"
         href=""
         download=""
         class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/90 text-on-surface font-headline font-bold text-sm hover:bg-white transition-all active:scale-95 shadow">
        <span class="material-symbols-outlined text-base">download</span>
        HD İndir
      </a>
      <a id="modalOpenTab"
         href=""
         target="_blank"
         rel="noopener"
         class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/20 border border-white/30 text-white font-headline font-bold text-sm hover:bg-white/30 transition-all active:scale-95">
        <span class="material-symbols-outlined text-base">open_in_new</span>
        Yeni Sekmede Aç
      </a>
    </div>

  </div>
</div>
<?php endif; ?>

<!-- ────────── FOOTER ────────── -->
<footer class="border-t border-outline bg-white mt-4">
  <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-5">

    <!-- Logo + İsim -->
    <div class="flex items-center gap-3">
      <img src="img/icon-192.png" alt="InstaPP" class="w-8 h-8 rounded-xl object-cover"/>
      <div class="leading-tight">
        <div class="font-headline font-extrabold text-sm ig-text">InstaPP</div>
        <div class="text-[10px] text-on-muted font-headline">Umut Yılmaz tarafından oluşturulmuştur</div>
      </div>
    </div>

    <!-- Telif -->
    <p class="text-[10px] font-headline uppercase tracking-widest text-on-muted text-center">
      © 2026 ·
      <a href="https://github.com/umtylmzl" target="_blank" rel="noopener" class="hover:text-on-surface transition-colors">github.com/umtylmzl</a>
    </p>

    <!-- Linkler -->
    <div class="flex gap-6">
      <a href="hakkimizda.php" class="text-[10px] font-headline uppercase tracking-widest text-on-muted hover:text-on-surface transition-colors">Hakkında</a>
      <a href="#" class="text-[10px] font-headline uppercase tracking-widest text-on-muted hover:text-on-surface transition-colors">Gizlilik</a>
    </div>

  </div>
</footer>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="sweetalert2.all.min.js"></script>
<script>
  /* ── Arama ── */
  function goSearch() {
    var val = $.trim($("#username_input").val());
    if (!val) return;
    window.location = "index.php?username=" + encodeURIComponent(val);
  }
  $("#gosterbutton").on("click", goSearch);
  $("#username_input").on("keypress", function(e) { if (e.which === 13) goSearch(); });

  /* ── Lightbox ── */
  function openModal(imgSrc, downloadSrc, username) {
    var modal = document.getElementById('imgModal');
    if (!modal) return;
    document.getElementById('modalImg').src = imgSrc;
    document.getElementById('modalDownload').href = downloadSrc;
    document.getElementById('modalDownload').download = (username || 'profile') + '_hd.jpg';
    document.getElementById('modalOpenTab').href = imgSrc;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    var modal = document.getElementById('imgModal');
    if (!modal) return;
    modal.classList.remove('open');
    document.body.style.overflow = '';
    setTimeout(function() { document.getElementById('modalImg').src = ''; }, 200);
  }

  document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('imgModal');
    if (!modal) return;

    /* Kapat butonu */
    document.getElementById('modalClose').addEventListener('click', closeModal);

    /* Dışarı tıklama (overlay) */
    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeModal();
    });

    /* Escape tuşu */
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeModal();
    });
  });
</script>

<?php if ($isNotFound): ?>
<script>
  Swal.fire({
    icon: "error",
    title: "Kullanıcı Bulunamadı",
    text: "<?php echo $usernameValue; ?> adlı hesap bulunamadı.",
    confirmButtonText: "Tamam",
    confirmButtonColor: "#0095f6"
  });
</script>
<?php endif; ?>

</body>
</html>
