<?php
include "db.php";

// Bildirim mesajı değişkeni
$notification = "";

// Kayıt işlemi
if (isset($_POST['submit'])) {
    $kimlik = $_POST['kimlik'];
    $parola = password_hash($_POST['parola'], PASSWORD_DEFAULT);
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];

    // Kullanıcı kimliği zaten varsa kontrol et
    $sql = "SELECT * FROM uyeler WHERE kimlik = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$kimlik]);
    if ($stmt->rowCount() > 0) {
        $notification = "Bu kimlik numarası zaten kayıtlı.";
    } else {
        // Yeni kullanıcı ekle
        $sql = "INSERT INTO uyeler (kimlik, parola, ad, soyad) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$kimlik, $parola, $ad, $soyad]);
        $notification = "Kayıt başarılı! Giriş yapabilirsiniz.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:#a1c4fd;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }
        .login-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
    max-width: 400px;
    width: 100%;
}
        .form-control {
            border-radius: 50px;
            padding: 10px 20px;
        }
              /*
CSS @property and the New Style
https://ryanmulligan.dev/blog/css-property-new-style/
*/
@import url("https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,500&display=swap");

:root {
  --shiny-cta-bg: #000000;
  --shiny-cta-bg-subtle: #1a1818;
  --shiny-cta-fg: #ffffff;
  --shiny-cta-highlight: #a1c4fd;
  --shiny-cta-highlight-subtle: #8484ff;
}

@property --gradient-angle {
  syntax: "<angle>";
  initial-value: 0deg;
  inherits: false;
}

@property --gradient-angle-offset {
  syntax: "<angle>";
  initial-value: 0deg;
  inherits: false;
}

@property --gradient-percent {
  syntax: "<percentage>";
  initial-value: 5%;
  inherits: false;
}

@property --gradient-shine {
  syntax: "<color>";
  initial-value: white;
  inherits: false;
}
form {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.shiny-cta {
    margin: 20px auto;
    --animation: gradient-angle linear infinite;
    --duration: 3s;
    --shadow-size: 2px;
    isolation: isolate;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    outline-offset: 4px;
    padding: 1rem 2rem;
    font-family: inherit;
    font-size: 1.125rem;
    line-height: 1.2;
    border: 1px solid transparent;
    border-radius: 1em;
    color: var(--shiny-cta-fg);
    background: linear-gradient(var(--shiny-cta-bg), var(--shiny-cta-bg))
        padding-box,
      conic-gradient(
          from calc(var(--gradient-angle) - var(--gradient-angle-offset)),
          transparent,
          var(--shiny-cta-highlight) var(--gradient-percent),
          var(--shiny-cta-highlight) calc(var(--gradient-percent) * 3),
          transparent calc(var(--gradient-percent) * 4)
        )
        border-box;
    box-shadow: inset 0 0 0 1px var(--shiny-cta-bg-subtle);


  &::before,
  &::after,
  span::before {
    content: "";
    pointer-events: none;
    position: absolute;
    inset-inline-start: 50%;
    inset-block-start: 50%;
    translate: -50% -50%;
    z-index: -1;
  }

  &:active {
    translate: 0 1px;
  }
}

/* Dots pattern */
.shiny-cta::before {
  --size: calc(100% - var(--shadow-size) * 3);
  --position: 2px;
  --space: calc(var(--position) * 2);
  width: var(--size);
  height: var(--size);
  background: radial-gradient(
      circle at var(--position) var(--position),
      white calc(var(--position) / 4),
      transparent 0
    )
    padding-box;
  background-size: var(--space) var(--space);
  background-repeat: space;
  mask-image: conic-gradient(
    from calc(var(--gradient-angle) + 45deg),
    black,
    transparent 10% 90%,
    black
  );
  border-radius: inherit;
  opacity: 0.4;
  z-index: -1;
}

/* Inner shimmer */
.shiny-cta::after {
  --animation: shimmer linear infinite;
  width: 100%;
  aspect-ratio: 1;
  background: linear-gradient(
    -50deg,
    transparent,
    var(--shiny-cta-highlight),
    transparent
  );
  mask-image: radial-gradient(circle at bottom, transparent 40%, black);
  opacity: 0.6;
}

.shiny-cta span {
  z-index: 1;

  &::before {
    --size: calc(100% + 1rem);
    width: var(--size);
    height: var(--size);
    box-shadow: inset 0 -1ex 2rem 4px var(--shiny-cta-highlight);
    opacity: 0;
  }
}

/* Animate */
.shiny-cta {
  --transition: 800ms cubic-bezier(0.25, 1, 0.5, 1);
  transition: var(--transition);
  transition-property: --gradient-angle-offset, --gradient-percent,
    --gradient-shine;

  &,
  &::before,
  &::after {
    animation: var(--animation) var(--duration),
      var(--animation) calc(var(--duration) / 0.4) reverse paused;
    animation-composition: add;
  }

  span::before {
    transition: opacity var(--transition);
    animation: calc(var(--duration) * 1.5) breathe linear infinite;
  }
}

.shiny-cta:is(:hover, :focus-visible) {
  --gradient-percent: 20%;
  --gradient-angle-offset: 95deg;
  --gradient-shine: var(--shiny-cta-highlight-subtle);

  &,
  &::before,
  &::after {
    animation-play-state: running;
  }

  span::before {
    opacity: 1;
  }
}

@keyframes gradient-angle {
  to {
    --gradient-angle: 360deg;
  }
}

@keyframes shimmer {
  to {
    rotate: 360deg;
  }
}

@keyframes breathe {
  from,
  to {
    scale: 1;
  }
  50% {
    scale: 1.2;
  }
}
.notification {
            position: fixed;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 20px;
            background-color: #f0f8ff;
            color: #333;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: top 0.5s ease-in-out;
            z-index: 1000;
            max-width: 90%;
            text-align: center;
        }
        .notification.show {
            top: 20px;
        }

        a {
            
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color:black;
        }
    </style>
</head>
<body>
<div class="login-card">
    <?php if ($notification): ?>
    <div class="notification show" id="notification"><?php echo $notification; ?></div>
    <?php endif; ?>
    <h2 class="my-5">Kayıt Ol</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="kimlik" class="form-label">Kimlik No</label>
            <input type="text" class="form-control" name="kimlik" id="kimlik" required>
        </div>
        <div class="mb-3">
            <label for="ad" class="form-label">Ad</label>
            <input type="text" class="form-control" name="ad" id="ad" required>
        </div>
        <div class="mb-3">
            <label for="soyad" class="form-label">Soyad</label>
            <input type="text" class="form-control" name="soyad" id="soyad" required>
        </div>
        <div class="mb-3">
            <label for="parola" class="form-label">Parola</label>
            <input type="password" class="form-control" name="parola" id="parola" required>
        </div>
        <button type="submit" name="submit" class="shiny-cta">Kayıt Ol</button>
        <a href="login.php" style="text-decoration:none;color:black;">Bir hesabınız var mı? 
          <span style="color:blue; text-decoration:underline;">Giriş Yap</span></a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    <script>
    // Bildirim mesajını otomatik kapat
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.style.top = '-100px';
        }, 3000); // 3 saniye sonra kaybolur
    }
</script>
</script>
</body>
</html>
