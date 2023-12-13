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
        <span class="goldenspan"><h1>THE GOLDEN GROTTO</h1></span>
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