<?php
/* php stuff */
require(__DIR__ . '/vendor/autoload.php');
if(isset($_POST['dates'])) :
    $dateValue = $_POST['dates'];
    $dateValue = htmlspecialchars(trim($dateValue));
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="styles.css">
    <title>Smelling Dettergent Hotel</title>
</head>
<body>
    <div class="wrapper">
        <div class="formWrapper">
            <div class="hero">
            </div>
            <form class="formWrapper" action="index.php">
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://kit.fontawesome.com/7ca45ddd8f.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>