<!DOCTYPE html>
<!-- İmza: Umut Yılmaz (https://github.com/umtylmzl) -->
<html lang="tr">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Hakkında | InstaPP – Instagram Profil Resmi Görüntüleyici</title>
  <meta name="description" content="InstaPP – Umut Yılmaz tarafından geliştirilen Instagram profil resmi görüntüleme ve indirme aracı."/>
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
    .ig-text { background: linear-gradient(135deg, #f7971e, #ff5f6d, #d6249f, #285AEB); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .ig-btn  { background: linear-gradient(135deg, #f7971e 0%, #ff5f6d 50%, #d6249f 100%); }
  </style>
</head>
<body class="bg-surface text-on-surface font-body antialiased">

<!-- Nav -->
<header class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-lg border-b border-outline">
  <nav class="max-w-7xl mx-auto px-5 h-14 flex items-center justify-between">
    <a href="index.php" class="flex items-center gap-2.5 no-underline">
      <img src="img/icon-192.png" alt="InstaPP logo" class="w-7 h-7 rounded-lg object-cover"/>
      <div class="leading-tight">
        <span class="font-headline font-extrabold text-base tracking-tight ig-text">InstaPP</span>
        <span class="hidden sm:block text-[9px] font-headline text-on-muted tracking-wide leading-none">by Umut Yılmaz</span>
      </div>
    </a>
    <a href="index.php" class="flex items-center gap-1 text-xs font-headline font-semibold text-on-muted hover:text-on-surface transition-colors">
      <span class="material-symbols-outlined text-base">arrow_back</span>
      Ana Sayfa
    </a>
  </nav>
</header>

<main class="pt-14">
  <div class="max-w-3xl mx-auto px-5 py-20 space-y-16">

    <!-- Header -->
    <div class="text-center space-y-4">
      <div class="flex justify-center">
        <img src="img/icon-512.png" alt="InstaPP" class="w-20 h-20 rounded-3xl shadow-sm object-cover"/>
      </div>
      <div class="space-y-2">
        <h1 class="text-4xl font-headline font-extrabold ig-text">InstaPP</h1>
        <p class="text-sm font-headline text-on-muted">Instagram Profil Resmi Görüntüleyici ve İndirici</p>
      </div>
      <p class="text-base text-on-muted font-serif leading-relaxed max-w-xl mx-auto">
        Umut Yılmaz tarafından geliştirilen bu araç, Instagram kullanıcılarının profil fotoğraflarını HD kalitesinde görüntülemenizi ve indirmenizi sağlar.
      </p>
    </div>

    <!-- Özellikler -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <div class="bg-white rounded-2xl border border-outline p-6 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-ig-blue/10 flex items-center justify-center">
          <span class="material-symbols-outlined text-ig-blue">high_quality</span>
        </div>
        <h3 class="font-headline font-bold text-on-surface">HD Kalite</h3>
        <p class="text-sm text-on-muted font-serif leading-relaxed">Piksel kaybı olmadan orijinal çözünürlükte profil fotoğrafı görüntüleme.</p>
      </div>
      <div class="bg-white rounded-2xl border border-outline p-6 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-ig-blue/10 flex items-center justify-center">
          <span class="material-symbols-outlined text-ig-blue">lock_open</span>
        </div>
        <h3 class="font-headline font-bold text-on-surface">Gizli Profil Desteği</h3>
        <p class="text-sm text-on-muted font-serif leading-relaxed">Sizi engelleyen veya gizli olan hesapların profil fotoğraflarına erişim.</p>
      </div>
      <div class="bg-white rounded-2xl border border-outline p-6 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-ig-blue/10 flex items-center justify-center">
          <span class="material-symbols-outlined text-ig-blue">security</span>
        </div>
        <h3 class="font-headline font-bold text-on-surface">Anonim</h3>
        <p class="text-sm text-on-muted font-serif leading-relaxed">Hiçbir kayıt veya giriş gerektirmez. Tamamen anonim çalışır.</p>
      </div>
    </div>

    <!-- Geliştirici -->
    <div class="bg-white rounded-3xl border border-outline p-8 md:p-12 flex flex-col md:flex-row items-start gap-8">
      <img src="img/icon-512.png" alt="InstaPP" class="w-16 h-16 rounded-2xl object-cover flex-shrink-0"/>
      <div class="space-y-3">
        <h2 class="font-headline font-extrabold text-xl text-on-surface">Geliştirici</h2>
        <p class="font-headline font-bold text-on-surface">Umut Yılmaz</p>
        <p class="text-sm text-on-muted font-serif leading-relaxed">
          InstaPP, Instagram kullanıcılarının profil fotoğraflarına kolayca erişebilmesi için geliştirilmiş açık kaynaklı bir PHP uygulamasıdır.
        </p>
        <a href="https://github.com/umtylmzl" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-on-surface text-white text-sm font-headline font-bold hover:opacity-80 transition-opacity active:scale-95">
          <span class="material-symbols-outlined text-base">code</span>
          github.com/umtylmzl
        </a>
      </div>
    </div>

    <!-- Diğer Projeler -->
    <div class="space-y-6">
      <div class="text-center space-y-1">
        <h2 class="font-headline font-extrabold text-xl text-on-surface">Diğer Projelerim</h2>
        <p class="text-sm text-on-muted font-serif">İncelemek isteyebileceğiniz diğer açık kaynak scriptler</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- Heart-Print -->
        <a href="https://github.com/umtylmzl/Heart-Print" target="_blank" rel="noopener"
           class="group bg-white rounded-2xl border border-outline p-6 space-y-3 hover:border-ig-blue/40 hover:shadow-sm transition-all block">
          <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
              <span class="material-symbols-outlined text-red-500">favorite</span>
            </div>
            <span class="material-symbols-outlined text-on-muted group-hover:text-on-surface transition-colors text-sm">open_in_new</span>
          </div>
          <div>
            <h3 class="font-headline font-bold text-on-surface">Heart Print</h3>
            <p class="text-[11px] font-headline text-on-muted uppercase tracking-wider mt-0.5">HTML · JavaScript · Tailwind</p>
          </div>
          <p class="text-sm text-on-muted font-serif leading-relaxed">
            Kolye (madalyon) içine koyacağınız fotoğrafı milimetrik ölçülerde A4'e yazdırmak için basit bir web aracı. Kalp maskesiyle canlı önizleme + 10 farklı mm boyutu.
          </p>
          <div class="flex items-center gap-1 text-xs font-headline font-semibold text-ig-blue">
            github.com/umtylmzl/Heart-Print
          </div>
        </a>

        <!-- BestWp Chat -->
        <a href="https://github.com/umtylmzl/bestwp-chatsc" target="_blank" rel="noopener"
           class="group bg-white rounded-2xl border border-outline p-6 space-y-3 hover:border-ig-blue/40 hover:shadow-sm transition-all block">
          <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
              <span class="material-symbols-outlined text-ig-blue">chat</span>
            </div>
            <span class="material-symbols-outlined text-on-muted group-hover:text-on-surface transition-colors text-sm">open_in_new</span>
          </div>
          <div>
            <h3 class="font-headline font-bold text-on-surface">BestWp Chat</h3>
            <p class="text-[11px] font-headline text-on-muted uppercase tracking-wider mt-0.5">PHP · MySQL · Tailwind</p>
          </div>
          <p class="text-sm text-on-muted font-serif leading-relaxed">
            PHP &amp; MySQL ile çalışan genel kanal + özel mesaj destekli sohbet scripti. Yönetici paneli, kullanıcı yönetimi, isteğe bağlı 2FA (TOTP).
          </p>
          <div class="flex items-center gap-1 text-xs font-headline font-semibold text-ig-blue">
            github.com/umtylmzl/bestwp-chatsc
          </div>
        </a>

      </div>
    </div>

    <!-- CTA -->
    <div class="text-center">
      <a href="index.php" class="ig-btn inline-flex items-center gap-2 px-8 py-4 rounded-xl text-white font-headline font-bold text-sm hover:opacity-90 active:scale-95 transition-all">
        <span class="material-symbols-outlined text-base">search</span>
        Aramaya Başla
      </a>
    </div>

  </div>
</main>

<!-- Footer -->
<footer class="border-t border-outline bg-white mt-4">
  <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-5">
    <div class="flex items-center gap-3">
      <img src="img/icon-192.png" alt="InstaPP" class="w-8 h-8 rounded-xl object-cover"/>
      <div class="leading-tight">
        <div class="font-headline font-extrabold text-sm ig-text">InstaPP</div>
        <div class="text-[10px] text-on-muted font-headline">Umut Yılmaz tarafından oluşturulmuştur</div>
      </div>
    </div>
    <p class="text-[10px] font-headline uppercase tracking-widest text-on-muted text-center">
      © 2026 ·
      <a href="https://github.com/umtylmzl" target="_blank" rel="noopener" class="hover:text-on-surface transition-colors">github.com/umtylmzl</a>
    </p>
    <div class="flex gap-6">
      <a href="hakkimizda.php" class="text-[10px] font-headline uppercase tracking-widest text-on-muted hover:text-on-surface transition-colors">Hakkında</a>
      <a href="#" class="text-[10px] font-headline uppercase tracking-widest text-on-muted hover:text-on-surface transition-colors">Gizlilik</a>
    </div>
  </div>
</footer>

</body>
</html>
