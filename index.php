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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="styles.css">
    <title>The Golden Grotto</title>
</head>
<body>
    <nav>
        <img src="assets/images/logo.jpg" alt="">
        <div class="navlista">
            <ul class="nav-list">
                <li>HOME</li>
                <li>ABOUT US</li>
                <li>BOOKING</li>
                <li>SPA & POOL AREA</li>
            </ul>   
        </div>
    </nav>
    <div class="wrapper">
        <div class="hero">
            <img src="assets/images/logo.jpg" alt="">
        </div>
        <div class="formWrapper">
            <form class="formWrapper" action="index.php" method="POST"> 
                <input name="extraFeature" type="checkbox">
                <input name="extraFeature2" type="checkbox">
                <div class="datepickerWrapper" style="border: 1px solid #ccc; background: #fff; cursor: pointer; padding: 5px 10px;">
                    <i class="fa-solid fa-calendar"></i>
                    <input class="datepicker" type="text" id="demo" name="dates">
                    <i class="fa-solid fa-caret-down"></i>
                </div>
                <button>Book</button>
            </form>
        </div>
        <div class="roomwrapper">
            <div class="roomOne"></div>
            <div class="roomTwo"></div>
            <div class="roomThree"></div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://kit.fontawesome.com/7ca45ddd8f.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>