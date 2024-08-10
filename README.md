<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
  <a href="https://www.docker.com" target="_blank" style="margin-left: 20px;">
    <img src="https://www.vectorlogo.zone/logos/docker/docker-ar21.svg" width="200" alt="Docker Logo">
  </a>
</p>



<h2>1. Proje Tanımı</h2>
Bu proje, PHP ile geliştirilmiş bir RESTful API servislerini içermektedir. İki ana görev üzerinden gerçekleştirilen bu çalışmada, sipariş işlemleri ve indirim hesaplamaları için API servisleri oluşturulmuştur. Proje Docker ile yapılandırılmış ve test edilmiştir.

<h2>2. Teknolojiler ve Araçlar</h2>
<ul>
  <li><strong>Programlama Dili:</strong> PHP</li>
  <li><strong>Framework/Library:</strong> Laravel 11</li>
  <li><strong>Veritabanı:</strong> MySQL (phpMyAdmin)</li>
  <li><strong>Docker:</strong> Proje Docker ile yapılandırılmış ve çalıştırılmıştır.</li>
</ul>

<h2>3. Görevler</h2>

<h3>Görev 1 - Siparişler</h3>
<p><strong>Açıklama:</strong> Bu görev, siparişlerin eklenmesi, silinmesi ve listeleme işlemlerini gerçekleştiren bir RESTful API servisi oluşturmayı hedeflemektedir.</p>
<ul>
  <li><strong>Fonksiyonlar:</strong>
    <ul>
      <li><strong>Ekleme:</strong> Yeni sipariş eklerken, satın alınan ürünün stoğu yeterli değilse bir hata mesajı döndürülür.</li>
      <li><strong>Silme:</strong> Belirli bir siparişi silme işlevi sağlar.</li>
      <li><strong>Listeleme:</strong> Tüm siparişlerin listesini döndürür.</li>
    </ul>
  </li>
  <li><strong>Özellikler:</strong>
    <ul>
      <li><strong>Payload Validasyonu:</strong> Gelen verilerin doğruluğunu kontrol eder.</li>
    </ul>
  </li>
</ul>

<h3>Görev 2 - İndirimler</h3>
<p><strong>Açıklama:</strong> Bu görev, verilen siparişler için belirli indirim kurallarını hesaplayan bir RESTful API servisi oluşturmayı hedeflemektedir.</p>
<ul>
  <li><strong>İndirim Kuralları:</strong>
    <ul>
      <li><strong>%10 İndirim:</strong> Toplam 1000TL ve üzerinde alışveriş yapan müşterilere siparişin tamamından %10 indirim uygulanır.</li>
      <li><strong>Ücretsiz Ürün:</strong> 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.</li>
      <li><strong>%20 İndirim:</strong> 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.</li>
    </ul>
  </li>
</ul>
