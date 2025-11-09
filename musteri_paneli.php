<?php
session_start();

if (!isset($_SESSION['musteri'])) {
    header("Location: login.php");
    exit;
}

include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteri Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        /* Video Arka Plan */
#background-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

/* Hero İçerik */
.hero {
    position: relative;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    text-align: center;
    overflow: hidden;
}

.hero-content {
    z-index: 1;
    padding: 20px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Arama Formu */
.search-form {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.search-form .input-group {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-form .input-group-text {
    background-color: #6c63ff;
    color: white;
    border: none;
    border-radius: 10px 0 0 10px;
}

.search-form input.form-control {
    border-radius: 0 10px 10px 0;
    border: none;
    height: 50px;
    padding-left: 10px;
}

.search-form select.custom-select {
    height: 50px;
    padding-left: 10px;
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-form .search-btn {
    background-color: #ff6b6b;
    color: white;
    font-weight: bold;
    border: none;
    height: 50px;
    transition: transform 0.2s ease;
}

.search-form .search-btn:hover {
    background-color: #ff4757;
    transform: scale(1.05);
}

        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
            margin: 0 5px;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    
       
        .navbar {
            background: linear-gradient(to right, #a1c4fd, #a1c4fd);
        }
        .navbar a, .navbar-toggler-icon {
            color: white;
        }
        .hero {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            text-align: center;
            padding: 100px 20px;
        }
       
        .card img {
            height: 600px;
            object-fit: cover;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
        <a class="navbar-brand" href="#"><img src="img/logo.png" style="height:6rem;" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="musteri_paneli.php">Anasayfa</a></li>
                    <li class="nav-item"><a class="nav-link" href="hakkımızda.html">Hakkımızda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#film">Filmler</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Biletlerim</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Çıkış Yap</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <section class="hero">
    <video autoplay muted loop id="background-video">
        <source src="img/metflıx.mp4" type="video/mp4">
        Tarayıcınız bu videoyu desteklemiyor.
    </video>
    <div class="hero-content">
        <h1>Hoş Geldiniz!</h1>
        <p>En yeni filmleri burada keşfedin ve benzersiz bir sinema deneyimi yaşayın.</p>

        <!-- Arama Formu -->
        <form action="" method="GET" class="search-form row justify-content-center mt-4">
            <div class="col-md-4 mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-film"></i></span>
                    <input type="text" name="film_adi" class="form-control" placeholder="Film Adı Ara" value="<?= htmlspecialchars($_GET['film_adi'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <select name="film_turu" class="form-select custom-select">
                    <option value="">Film Türü Seç</option>
                    <?php
                    // Film türlerini veritabanından çek
                    $turler = $pdo->query("SELECT DISTINCT FilmTuru FROM Filmler")->fetchAll();
                    foreach ($turler as $tur) {
                        $selected = (isset($_GET['film_turu']) && $_GET['film_turu'] === $tur['FilmTuru']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($tur['FilmTuru']) . '" ' . $selected . '>' . htmlspecialchars($tur['FilmTuru']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-light w-100 search-btn">Ara</button>
            </div>
        </form>
    </div>
</section>

    <!-- Film Cards -->
    <div class="container my-5" id="film">
    <div class="row">
        <?php
        // Arama filtrelerini uygulama
        $query = "SELECT * FROM Filmler WHERE 1";
        $params = [];

        if (!empty($_GET['film_adi'])) {
            $query .= " AND FilmAdi LIKE :film_adi";
            $params['film_adi'] = '%' . $_GET['film_adi'] . '%';
        }
        if (!empty($_GET['film_turu'])) {
            $query .= " AND FilmTuru = :film_turu";
            $params['film_turu'] = $_GET['film_turu'];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        while ($film = $stmt->fetch()) {
            echo '
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="data:image/jpeg;base64,' . base64_encode($film['Resim']) . '" class="card-img-top" alt="Film Resmi">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($film['FilmAdi']) . '</h5>
                        <p class="card-text">Tür: ' . htmlspecialchars($film['FilmTuru']) . '</p>
                        <p class="card-text">Fiyat: ' . htmlspecialchars($film['BiletFiyati']) . ' TL</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="bilet_al.php?film_id=' . $film['FilmID'] . '" class="btn btn-primary">Bilet Al</a>
                    </div>
                </div>
            </div>';
        }

        if ($stmt->rowCount() === 0) {
            echo '<p class="text-center">Arama kriterlerinize uygun film bulunamadı.</p>';
        }
        ?>
    </div>
</div>
    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p>© 2024 Sinema Bileti Uygulaması</p>
            <p>İletişim: info@sinema.com | Tel: +90 123 456 7890</p>
            <div>
                <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
