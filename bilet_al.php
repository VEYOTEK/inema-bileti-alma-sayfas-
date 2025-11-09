<?php
include "db.php";

// Film bilgilerini al
$film_id = $_GET['film_id'];
$stmt = $pdo->prepare("SELECT * FROM Filmler WHERE FilmID = :film_id");
$stmt->execute(['film_id' => $film_id]);
$film = $stmt->fetch();

// Başlangıçta boş bir dizi tanımlıyoruz
$occupiedSeats = [];
$tarih = isset($_GET['tarih']) ? $_GET['tarih'] : date('Y-m-d');
$saat = isset($_GET['saat']) ? $_GET['saat'] : '10:00:00';

// Belirtilen tarih ve saat için dolu koltukları getir
$stmt = $pdo->prepare("SELECT koltuk_numarasi FROM Biletler WHERE film_id = :film_id AND tarih = :tarih AND saat = :saat");
$stmt->execute([
    'film_id' => $film_id,
    'tarih' => $tarih,
    'saat' => $saat,
]);
while ($row = $stmt->fetch()) {
    $occupiedSeats[] = $row['koltuk_numarasi'];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet Al - <?= htmlspecialchars($film['FilmAdi']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2>Bilet Al: <?= htmlspecialchars($film['FilmAdi']) ?></h2>
    <form action="" method="GET" id="tarihSaatForm">
        <input type="hidden" name="film_id" value="<?= $film['FilmID'] ?>">

        <!-- Tarih seçimi -->
        <div class="mb-3">
            <label for="tarih" class="form-label">Tarih</label>
            <input type="date" class="form-control" name="tarih" value="<?= $tarih ?>" onchange="document.getElementById('tarihSaatForm').submit();">
        </div>

        <!-- Saat seçimi -->
        <div class="mb-3">
            <label for="saat" class="form-label">Saat</label>
            <select class="form-control" name="saat" onchange="document.getElementById('tarihSaatForm').submit();">
                <option value="10:00:00" <?= $saat == '10:00:00' ? 'selected' : '' ?>>10:00</option>
                <option value="14:00:00" <?= $saat == '14:00:00' ? 'selected' : '' ?>>14:00</option>
                <option value="18:00:00" <?= $saat == '18:00:00' ? 'selected' : '' ?>>18:00</option>
                <option value="21:00:00" <?= $saat == '21:00:00' ? 'selected' : '' ?>>21:00</option>
            </select>
        </div>
    </form>

    <form action="bilet_al_kaydet.php" method="POST" id="biletForm">
        <input type="hidden" name="film_id" value="<?= $film['FilmID'] ?>">
        <input type="hidden" name="tarih" value="<?= $tarih ?>">
        <input type="hidden" name="saat" value="<?= $saat ?>">

        <!-- Koltuk seçimi -->
        <div class="mb-3">
            <label for="koltuk" class="form-label">Koltuk Seç</label>
            <div class="row">
                <?php
                // 20 koltuk olduğunu varsayıyoruz
                for ($i = 1; $i <= 20; $i++) {
                    // Eğer koltuk doluysa tıklanamaz ve kırmızı olur
                    $disabled = in_array($i, $occupiedSeats) ? 'disabled' : '';
                    $btnClass = in_array($i, $occupiedSeats) ? 'danger' : 'success';
                    echo '
                    <div class="col-2 mb-2">
                        <button type="button" class="btn btn-' . $btnClass . ' col-12" id="koltuk_' . $i . '" ' . $disabled . ' onclick="selectSeat(' . $i . ')">' . $i . '</button>
                        <input type="hidden" name="koltuk_input_' . $i . '" id="koltuk_input_' . $i . '" value="">
                    </div>';
                }
                ?>
            </div>
        </div>

        <!-- Koltuk numarasını gösteren text box -->
        <div class="mb-3">
            <label for="koltuk_numarasi_text" class="form-label">Seçilen Koltuk Numarası</label>
            <input type="text" class="form-control" id="koltuk_numarasi_text" name="koltuk_numarasi" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Bilet Al</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Koltuk seçildiğinde, seçilen koltuk numarasını formda gizli input'a ve text box'a yerleştir
    function selectSeat(seatNumber) {
        // Tüm koltukları kontrol et
        for (let i = 1; i <= 20; i++) {
            const seatButton = document.getElementById('koltuk_' + i);
            if (!seatButton.disabled) {
                seatButton.classList.remove("btn-danger");
                seatButton.classList.add("btn-success");
            }
        }

        // Seçilen koltuğu işaretle
        const selectedButton = document.getElementById('koltuk_' + seatNumber);
        selectedButton.classList.remove("btn-success");
        selectedButton.classList.add("btn-danger");

        // Seçilen koltuk numarasını formdaki inputlara yerleştir
        document.getElementById('koltuk_numarasi_text').value = seatNumber;
    }

    // Form gönderilmeden önce koltuk seçimi kontrol et
    document.getElementById('biletForm').onsubmit = function(event) {
        const seatNumber = document.getElementById('koltuk_numarasi_text').value;
        if (!seatNumber) {
            alert('Lütfen bir koltuk seçin.');
            event.preventDefault();
        }
    };
</script>
</body>
</html>
