<?php
session_start();
include "db.php";

// Formdan gelen veriler
$film_id = $_POST['film_id'];
$tarih = $_POST['tarih'];
$saat = $_POST['saat'];
$koltuk_numarasi = $_POST['koltuk_numarasi'];

// Kullanıcı oturum bilgisi
$kullanici_idsi = $_SESSION['id'] ?? null;

// Kullanıcı bilgilerini çek
$stmt = $pdo->prepare("SELECT ad, soyad FROM Uyeler WHERE id = :id");
$stmt->execute(['id' => $kullanici_idsi]);
$kullanici = $stmt->fetch();

// Film bilgilerini çek
$stmt = $pdo->prepare("SELECT * FROM Filmler WHERE FilmID = :film_id");
$stmt->execute(['film_id' => $film_id]);
$film = $stmt->fetch();

// Hata kontrolü
if (!$kullanici || !$film) {
    echo "Fatura oluşturulurken bir hata oluştu!";
    exit();
}
try {
    $stmt = $pdo->prepare("
        INSERT INTO Biletler (film_id, tarih, saat, koltuk_numarasi, kullanici_id)
        VALUES (:film_id, :tarih, :saat, :koltuk_numarasi, :kullanici_idsi)
    ");
    $stmt->execute([
        'film_id' => $film_id,
        'tarih' => $tarih,
        'saat' => $saat,
        'koltuk_numarasi' => $koltuk_numarasi,
        'kullanici_idsi' => $kullanici_idsi
    ]);

    // Başarılı işlem mesajı
    
    
} catch (Exception $e) {
    echo "Bilet kaydı sırasında bir hata oluştu: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet Faturası</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg max-w-3xl w-full p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">🎟️ Bilet Faturası</h2>
        <div class="border-t border-gray-200 my-4"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Müşteri Bilgileri</h3>
                <p class="text-gray-600 mt-2">Ad Soyad: <span class="font-medium"><?= htmlspecialchars($kullanici['ad'] . ' ' . $kullanici['soyad']) ?></span></p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Film Bilgileri</h3>
                <p class="text-gray-600 mt-2">Film Adı: <span class="font-medium"><?= htmlspecialchars($film['FilmAdi']) ?></span></p>
                <p class="text-gray-600 mt-1">Tür: <span class="font-medium"><?= htmlspecialchars($film['FilmTuru']) ?></span></p>
            </div>
        </div>

        <div class="border-t border-gray-200 my-4"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Seans Bilgileri</h3>
                <p class="text-gray-600 mt-2">Tarih: <span class="font-medium"><?= htmlspecialchars($tarih) ?></span></p>
                <p class="text-gray-600 mt-1">Saat: <span class="font-medium"><?= htmlspecialchars($saat) ?></span></p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Koltuk</h3>
                <p class="text-gray-600 mt-2">Koltuk No: <span class="font-medium"><?= htmlspecialchars($koltuk_numarasi) ?></span></p>
            </div>
        </div>

        <div class="border-t border-gray-200 my-4"></div>

        <div class="flex justify-between items-center">
            <button onclick="window.print()" class="bg-blue-500 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-600">Yazdır</button>
            <a href="musteri_paneli.php" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-md shadow-md hover:bg-gray-300">Ana Sayfaya Dön</a>
        </div>
    </div>
</div>

</body>
</html>
