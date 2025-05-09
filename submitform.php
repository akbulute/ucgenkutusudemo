<?php
// Hata ayıklamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// PHPMailer kütüphanesini dahil et
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer ve diğer bağımlılıkların yolu

$mail = new PHPMailer(true);

try {
    // SMTP ayarları
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // SMTP sunucusu
    $mail->SMTPAuth = true;
    $mail->Username = 'ucgenkutusu@gmail.com'; // SMTP kullanıcı adı
    $mail->Password = 'kzpe grcs ghbk glnu'; // Gmail uygulama şifresi
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; // SMTP portu (587 TLS için yaygın olarak kullanılır)

    // Form verilerini al ve temizle
    $name = htmlspecialchars($_POST['name'] ?? 'Bilgi yok');
    $email = htmlspecialchars($_POST['email'] ?? 'Bilgi yok');
    $message = htmlspecialchars($_POST['message'] ?? 'Bilgi yok');

    // Gönderici ve alıcı bilgileri
    $mail->setFrom($email, $name); // Gönderen, formu dolduran kişinin e-posta adresi
    $mail->addAddress('ucgenkutusu@gmail.com', 'Recipient Name'); // Alıcı e-posta adresi

    // Eğer yanıt vermek isterseniz, bu email adresine cevap gönderilsin
    $mail->addReplyTo($email, $name); // Cevap adresi formdaki email

    // Karakter setini UTF-8 olarak ayarlamak
    $mail->CharSet = 'UTF-8';

    // E-posta içeriği
    $mail->isHTML(true);
    $mail->Subject = 'Yeni İletişim Formu Mesajı';
    $mail->Body    = 'Ad: ' . $name . '<br>Email: ' . $email . '<br>Mesaj: ' . nl2br($message); // Mesajda satır başlarını koruyarak HTML düzenlemesi

    // E-postayı gönder
    $mail->send();
    
    // Yönlendirme: 3 saniye sonra ana sayfaya yönlendir
    header("Refresh: 3; url=index.php");
    echo 'Mesajınız başarıyla gönderildi! Yönlendiriliyorsunuz...';
} catch (Exception $e) {
    // Hata mesajını bir günlük dosyasına kaydet
    error_log("Mesaj gönderilemedi. Hata: {$mail->ErrorInfo}");
    echo 'Mesaj gönderilemedi. Lütfen tekrar deneyin.';
}
?>
