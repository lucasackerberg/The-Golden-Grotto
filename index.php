<?php
/* php stuff */
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/hotelFunctions.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dates'])) {
        $date = $_POST['dates'];
        sanitizeAndSend($date);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Ibarra+Real+Nova&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="styles.css">
    <title>The Golden Grotto</title>
</head>
<body>
    <nav>
        <span class="goldenspan"><a href="index.php"><h1>THE GOLDEN GROTTO</h1></a></span>
        <div class="navlista">
            <ul class="nav-list">
                <li><a href="index.php">HOME</a></li>
                <li><a href="index.php">ABOUT US</a></li>
                <li><a href="index.php">ROOMS</a></li>
                <li><a href="index.php">ACTIVITIES</a></li>
            </ul>   
        </div>
    </nav>
    <div class="wrapper">
        <div class="hero">
            <div class="heroimg">
                <div class="welcome">
                    <h3>Welcome to <span class="goldenspan">The Golden Grotto</span></h3>
                    <p>This hotel is designed to make you leave all your worries blablabla.</p>
                </div>
            </div>
        </div>
        <div class="transition"></div>
        <div class="roomwrapper">
            <!-- Swiper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="assets/images/luxury-grotto.jpg" alt="">
                        <div class="info">
                            <h3>Golden Oasis Suite</h3>
                            <p>The Golden Oasis Suite is a luxurious escape from reality. <br> Starting at <span class="bigger">15$</span> per night</p>
                            <a href="roomone.php"><button><p>SEE MORE & BOOK NOW!</p></button></a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="assets/images/medium-grotto.jpg" alt="">
                        <div class="info">
                            <h3>Gilded Grotto Retreat</h3>
                            <p>The Gilded Grotto Retreat is a premier comfort experience. <br> Starting at <span class="bigger">10$</span> per night</p>
                            <a href="roomtwo.php"><button><p>SEE MORE & BOOK NOW!</p></button></a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="assets/images/simple-grotto.jpg" alt="">
                        <div class="info">
                            <h3>Sunlit Aurum Chamber</h3>
                            <p>The Sunlit Aurum Chamber is classic elegance room. <br> Starting at <span class="bigger">7$</span> per night</p>
                            <a href="roomthree.php"><button><p>SEE MORE & BOOK NOW!</p></button></a>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
        <div class="transition"></div>
        <div class="activities">

        </div>
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://kit.fontawesome.com/7ca45ddd8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>