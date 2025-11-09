<?php
include "db.php";
$message = "";

// Film Ekleme veya Güncelleme
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $filmAdi = $_POST['filmAdi'];
    $filmTuru = $_POST['filmTuru'];
    $biletFiyati = $_POST['biletFiyati'];
    $filmId = $_POST['filmId'] ?? null;

    if (!empty($_FILES['resim']['tmp_name'])) {
        // Yeni resim dosyasını oku
        $resim = file_get_contents($_FILES['resim']['tmp_name']);
    } else {
        // Güncellemede resim yüklenmediyse mevcut resmi koru
        if ($filmId) {
            $stmt = $pdo->prepare("SELECT Resim FROM Filmler WHERE FilmID = :filmId");
            $stmt->execute([":filmId" => $filmId]);
            $resim = $stmt->fetchColumn();
        } else {
            $resim = null; // Yeni film ekleniyorsa ve resim yoksa hata dönebilir
        }
    }

    if ($filmId) {
        // Film Güncelle
        $stmt = $pdo->prepare("UPDATE Filmler SET FilmAdi = :filmAdi, FilmTuru = :filmTuru, BiletFiyati = :biletFiyati, Resim = :resim WHERE FilmID = :filmId");
        $stmt->execute([
            ":filmAdi" => $filmAdi,
            ":filmTuru" => $filmTuru,
            ":biletFiyati" => $biletFiyati,
            ":resim" => $resim,
            ":filmId" => $filmId
        ]);
        $message = "Film başarıyla güncellendi!";
    } else {
        // Yeni Film Ekle
        $stmt = $pdo->prepare("INSERT INTO Filmler (FilmAdi, FilmTuru, BiletFiyati, Resim) VALUES (:filmAdi, :filmTuru, :biletFiyati, :resim)");
        $stmt->execute([
            ":filmAdi" => $filmAdi,
            ":filmTuru" => $filmTuru,
            ":biletFiyati" => $biletFiyati,
            ":resim" => $resim
        ]);
        $message = "Film başarıyla eklendi!";
    }
}

// Film Silme
if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $stmt = $pdo->prepare("DELETE FROM Filmler WHERE FilmID = :filmId");
    $stmt->execute([":filmId" => $deleteId]);
    $message = "Film başarıyla silindi!";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>Film Yönetimi</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Film Ekleme Formu -->
        <form action="admin_filmler.php" method="post" enctype="multipart/form-data" class="mb-4">
            <input type="hidden" name="filmId" id="filmId">
            <div class="mb-3">
                <label for="filmAdi" class="form-label">Film Adı</label>
                <input type="text" name="filmAdi" id="filmAdi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="filmTuru" class="form-label">Film Türü</label>
                <input type="text" name="filmTuru" id="filmTuru" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="biletFiyati" class="form-label">Bilet Fiyatı</label>
                <input type="number" name="biletFiyati" id="biletFiyati" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="resim" class="form-label">Film Resmi</label>
                <input type="file" name="resim" id="resim" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Kaydet</button>
        </form>

        <!-- Film Listesi -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Film Adı</th>
                    <th>Film Türü</th>
                    <th>Bilet Fiyatı</th>
                    <th>Resim</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM Filmler");
                while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <tr>
                        <td>{$film['FilmAdi']}</td>
                        <td>{$film['FilmTuru']}</td>
                        <td>{$film['BiletFiyati']} TL</td>
                        <td><img src='data:image/jpeg;base64," . base64_encode($film['Resim']) . "' style='width: 100px; height: 70px; object-fit: cover;'></td>
                        <td>
                            <button class='btn btn-warning btn-sm' onclick='editFilm({$film['FilmID']}, \"{$film['FilmAdi']}\", \"{$film['FilmTuru']}\", {$film['BiletFiyati']})'>Düzenle</button>
                            <a href='admin_filmler.php?deleteId={$film['FilmID']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Bu filmi silmek istediğinizden emin misiniz?\")'>Sil</a>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function editFilm(id, adi, turu, fiyat) {
            document.getElementById('filmId').value = id;
            document.getElementById('filmAdi').value = adi;
            document.getElementById('filmTuru').value = turu;
            document.getElementById('biletFiyati').value = fiyat;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
