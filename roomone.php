<?php
/* php stuff */
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/hotelFunctions.php');
/* var_dump(getBookedDates($db)); */ 
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
        <div class="picsAndInfo">
            <div class="infoColumn">
                <h3>Hotel room</h3>
                <p>blabkabkan</p>
            </div>
            <div class="picColumn">
                <div class="roompic"></div>
                <div class="sectionTwo">
                    <div class="bathroompic"></div>
                    <div class="outsidepic"></div>
                </div>
            </div>
        </div>
        <div class="bookingWrapper">
            <div class="bookingInformation" id="bookingInformation">
                <p>Cost per day is <span class="goldenspan">15$</span> for this room.</p>
                <!-- lägg till genom js -->
            </div>
            <form class="formWrapper" action="roomone.php" method="POST">
                <input id="massageCheckbox" name="extraFeature" type="checkbox" value="3"> <p>Lava massage 3$ USD</p>
                <input id="poolCheckbox" name="extraFeature2" type="checkbox" value="3"> <p>Pool access: 3$ USD</p>
                <div class="datepickerWrapper">
                    <i class="fa-solid fa-calendar"></i>
                    <input class="datepicker" type="text" id="demo" name="dates">
                    <i class="fa-solid fa-caret-down"></i>
                </div>
                <div class="transfercode">
                    <input type="text" class="transfercodeinput" placeholder="Please enter your first name!" name="firstname">
                    <input type="text" class="transfercodeinput" placeholder="Please enter your last name!" name="lastname">
                    <input class="transfercodeinput" type="text" name="transfercode" placeholder="Write your transfercode here!">
                    <button>Book</button>
                </div>
            </form>
        </div>
        <footer>
            <div class="footer-content">
                <div class="footer-logo">The Golden Grotto</div>
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="#">About Us</a>
                    <a href="#">Rooms</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </footer>
    </div>
    <script> const bookedDates = <?php echo json_encode($bookedDatesforLuxuryroom); ?>; </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://kit.fontawesome.com/7ca45ddd8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>