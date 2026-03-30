# InstaPP – Instagram Profil Resmi Görüntüleyici

**Umut Yılmaz** tarafından oluşturulmuştur · [github.com/umtylmzl](https://github.com/umtylmzl)

Instagram kullanıcılarının profil fotoğraflarını HD kalitesinde görüntüleyen ve indiren açık kaynaklı PHP scripti.

---

## Özellikler

- Profil fotoğrafını tam/HD çözünürlükte görüntüleme ve indirme
- Gizli profil ve sizi engelleyen hesaplar için de çalışır
- Üç katmanlı veri çekme: Apify → Instagram API → HTML ayrıştırma
- Tailwind CSS ile modern, mobil uyumlu arayüz
- Reklam alanları hazır (AdSense vb. kolay entegrasyon)
- Kullanıcı adı veya profil linki ile arama

---

## Kurulum

1. Dosyaları web sunucunuza (Apache/Nginx + PHP 7.4+) yükleyin.
2. `mod_rewrite` aktif olmalıdır (`.htaccess` URL yönlendirmesi için).
3. *(İsteğe bağlı)* Apify entegrasyonu için ortam değişkeni tanımlayın:

```bash
APIFY_TOKEN=your_apify_token_here
```

4. Tarayıcıdan projenin klasörüne gidin — hepsi bu kadar.

---

## Dosya Yapısı

```
├── index.php          # Ana uygulama (veri çekme mantığı)
├── ui-tailwind.php    # Arayüz şablonu (Tailwind CSS)
├── hakkimizda.php     # Hakkında sayfası
├── img-proxy.php      # Resim proxy (CORP sorununu çözer)
├── .htaccess          # URL yönlendirme kuralları
├── sweetalert2.all.min.js
└── UmutYilmaz.png     # Favicon
```

---

## Gereksinimler

- PHP 7.4+
- Apache + mod_rewrite **veya** Nginx (rewrite kuralı ile)
- cURL uzantısı (önerilir, yoksa `file_get_contents` kullanılır)

---

## Lisans

MIT — dilediğiniz gibi kullanabilir, geliştirebilir ve dağıtabilirsiniz.

---

*© 2026 [Umut Yılmaz](https://github.com/umtylmzl)*
