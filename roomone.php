<?php
/* php stuff */
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/hotelFunctions.php');
$jsvar = 0;
/* var_dump(getBookedDates($db)); */ 
// roomone.php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if one of the forms has been submitted
    if (isset($_POST['emailAdress'])) {
        $email = $_POST['emailAdress'];
        $sanitizedEmail = sanitizeEmail($email);

        if ($sanitizedEmail !== null) {
            // Use $sanitizedEmail safely
            /* echo "Sanitized Email: " . $sanitizedEmail; */

            if (emailExists($db, $sanitizedEmail)) {
                // Email already exists, handle accordingly
                echo "Email already exists!";
            } else {
                // Generate a discount code (you can modify this part)
                $discountCode = generateDiscountCode($db);

                // Insert the email
                if (insertEmail($db, $sanitizedEmail)) {
                    // Show the popup with the discount code only if email insertion was successful
                    $jsvar = 1;
                } else {
                    // Handle email insertion failure
                    echo "Failed to insert email.";
                }
            }
        } else {
            // Invalid Email
            echo "Invalid Email format";
        }
    }
    if (isset($_POST['dates'], $_POST['firstname'], $_POST['lastname'], $_POST['transfercode'], $_POST['totalCost'])) {
        $dates = sanitizeAndFormat($_POST['dates']);
        $startDate = $dates['start'];
        $endDate = $dates['end'];
        $firstname = sanitizename($_POST['firstname']);
        $lastname = sanitizename($_POST['lastname']);
        $totalcosttot = $_POST['totalCost'];
        $totalcosttot = intval($totalcosttot);
        $transfercode = $_POST['transfercode'];
        $roomNumber = 1;

        if (isValidUuid($transfercode)) {
            // Transfer code is properly structured, proceed with checking
            if (checkTransferCode($transfercode, $totalcosttot)) {
                // Transfer code is valid, proceed with the booking
                echo "Transfer code is valid!\n";
                if (depositIntoBankAccount($transfercode)) {
                // Deposition okay, money is now in the bank!
                // Proceed with booking!
                echo "Money is now in the bank";
                    if ($dates !== null) {
                        // Check if the checkboxes are checked
                        $poolAccess = isset($_POST['poolAccess']);
                        $lavaMassage = isset($_POST['lavaMassage']);
        
                        if (isAvailable($db, $startDate, $endDate)) {
                            // Dates are available, proceed with the booking
                            insertBooking($db, $startDate, $endDate, $firstname, $lastname, $poolAccess, $lavaMassage, $totalcosttot, $roomNumber);
                            echo "Booking successful!\n";
                            // createJSONResponse($startDate, $endDate, $firstname, $lastname, $poolAccess, $lavaMassage, $totalcosttot, $roomNumber);
                        } else {
                            // Dates are not available
                            echo "Selected dates are not available. Please choose different dates.\n";
                        }
                    }
                } else {
                    echo "Money could not be wired to the bank. Please try again!";
                }
            } else {
                // Transfer code is not valid
                echo "Invalid transfer code. Please provide a valid transfer code.";
            }
        }
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
                <li><a href="aboutus.php">ABOUT US</a></li>
                <li><a href="/index.php#room-section">ROOMS</a></li>
                <li><a href="activities.php">ACTIVITIES</a></li>
            </ul>   
        </div>
    </nav>
    <div class="wrapper">
        <div class="overlay" id="overlay"></div>
        <div class="popup-container" id="discountPopup">
            <h2>Your Discount Code</h2>
            <p>Here is your special discount code: <span id="discountCode"></span></p>
            <button onclick="closePopup()">Close</button>
        </div>
        <div class="picsAndInfo">
            <div class="infoColumn">
                <h3>Golden Oasis Suite</h3>
                <p class="upper">
                    Immerse yourself in opulence with our Golden Oasis Suite. 
                    This room is a true sanctuary of luxury, featuring lavish decor, golden accents, and an ambiance that exudes regality.
                    The Golden Oasis Suite is designed for those who appreciate the finer things in life. Indulge in a royal experience with personalized service and unmatched comfort.
        
                </p>
                <p class="lower">
                    Starting Price is $<span id="basePrice" class="biggertextforinfo">15</span> per night <br><br>
                    Our <span class="biggertextforinfo">Features</span> are: <br><br>
                    <span class="biggertextforinfo">King-sized</span> bed with premium linens <br><br>
                    Private <span class="biggertextforinfo">Terrace</span> overlooking the shore and the cave-island. <br><br>
                    <span class="biggertextforinfo">Jacuzzi</span> and spa-like bathroom <br><br>
                    <span class="biggertextforinfo">24/7</span> Concierge Service <br><br>
                    See our other addons at the <span class="biggertextforinfo"><a href="/activities">Activities</a></span> page!
                </p>
            </div>
            <div class="picColumn">
                <div class="roompic"></div>
                <div class="sectionTwo">
                    <div class="bathroompic"></div>
                    <div class="outsidepic"></div>
                </div>
            </div>
        </div>
        <div class="transitiondiv"></div>
        <div class="bookingWrapper">
            <div class="bookingInformation" id="bookingInformation">
                <div class="massageroom">
                    <div class="massagebox">
                        <img src="assets/images/massageroom.jpg" alt="" class="massage-image" id="massageImage">
                        <img src="assets/images/checkmark.png" class="massageCheckmarkImg hiddenCheckmark" id="massageCheckmarkImg" alt="">
                    </div>
                    <div class="poolbox">
                        <img src="assets/images/poolarea1.jpg" alt="" class="pool-image" id="poolImage">
                        <img src="assets/images/checkmark.png" alt="" class="poolCheckmarkImg hiddenCheckmark" id="poolCheckmarkImg">
                    </div>
                </div>
                <!-- lägg till genom js -->
                <!-- <p class="costperday">Cost per day is <span id="basePrice"class="bigger">15</span> $ for this room.</p> -->
            </div>
            <div class="discount">
                <p>Please apply your discount before booking!</p>
                <input type="text" placeholder="DISCOUNT CODE">
                <button>Apply</button>
            </div>
            <form class="formWrapper" action="roomone.php" method="POST">
                <article class="lavaMassage">
                    <input id="massageCheckbox" name="lavaMassage" type="checkbox" value="3" onchange="handleMassageCheckbox()">
                        <div>
                            <span>
                            Lava Massage<br/>
                            <span class="biggertextforinfo">+ $3.00</span>
                            </span>
                        </div>
                </article>
                <article class="lavaMassage">
                <input id="poolCheckbox" name="poolAccess" type="checkbox" value="3" onchange="handleMassageCheckbox()">
                        <div>
                            <span>
                            Pool Access<br/>
                            <span class="biggertextforinfo">+ $3.00</span>
                            </span>
                        </div>
                </article>
                <input type="hidden" id="totalCostInput" name="totalCost" value="">
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
        <div class="transitiondiv"></div>
        <footer class="footer">
            <video class="footer_video" muted="" loop="" autoplay src="assets/videos/footerbackground2.mp4" type="video/mp4">
            </video>
            <div class="container">
                <div class="footer_inner">
                <div class="c-footer">
                    <div class="layout">
                    <div class="layout_item w-50">
                        <div class="newsletter">
                        <h3 class="newsletter_title">Get a 20% discount when registering your email to our newsletter!</h3>
                        <form action="roomone.php" method="POST">
                            <input type="text" placeholder="Email Address" name=emailAdress>
                            <button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path fill="none" d="M0 0h24v24H0z" />
                                <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" />
                            </svg>
                            </button>
                        </form>
                        </div>
                    </div>

                    <div class="layout_item w-25">
                        <nav class="c-nav-tool">
                        <h4 class="c-nav-tool_title">Menu</h4>
                        <ul class="c-nav-tool_list">
                            <li>
                                <a href="/index.php#room-section" class="c-link">Our Rooms</a>
                            </li>
                            <li>
                                <a href="/about-us" class="c-link">About Us</a>
                            </li>
                            <li>
                                <a href="/blogs/community" class="c-link">Activities</a>
                            </li>
                        </ul>
                        </nav>
                    </div>
                    <div class="layout_item w-25">
                        <nav class="c-nav-tool">
                            <h4 class="c-nav-tool_title">Support</h4>
                                <ul class="c-nav-tool_list">
                                    <li class="c-nav-tool_item">
                                        <a href="/pages/help" class="c-link">Help &amp; FAQ</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="layout c-2">
                    <div class="layout_item w-50">
                        <ul class="flex">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                <path fill="none" d="M0 0h24v24H0z" />
                                <path d="M12 6.654a6.786 6.786 0 0 1 2.596 5.344A6.786 6.786 0 0 1 12 17.34a6.786 6.786 0 0 1-2.596-5.343A6.786 6.786 0 0 1 12 6.654zm-.87-.582A7.783 7.783 0 0 0 8.4 12a7.783 7.783 0 0 0 2.728 5.926 6.798 6.798 0 1 1 .003-11.854zm1.742 11.854A7.783 7.783 0 0 0 15.6 12a7.783 7.783 0 0 0-2.73-5.928 6.798 6.798 0 1 1 .003 11.854z" />
                            </svg>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                <path fill="none" d="M0 0h24v24H0z" />
                                <path d="M1 4h22v2H1V4zm0 14h22v2H1v-2zm18.622-3.086l-.174-.87h-1.949l-.31.863-1.562.003c1.005-2.406 1.75-4.19 2.236-5.348.127-.303.353-.457.685-.455.254.002.669.002 1.245 0L21 14.912l-1.378.003zm-1.684-2.062h1.256l-.47-2.18-.786 2.18zM7.872 9.106l1.57.002-2.427 5.806-1.59-.001c-.537-2.07-.932-3.606-1.184-4.605-.077-.307-.23-.521-.526-.622-.263-.09-.701-.23-1.315-.419v-.16h2.509c.434 0 .687.21.769.64l.62 3.289 1.574-3.93zm3.727.002l-1.24 5.805-1.495-.002 1.24-5.805 1.495.002zM14.631 9c.446 0 1.01.138 1.334.267l-.262 1.204c-.293-.118-.775-.277-1.18-.27-.59.009-.954.256-.954.493 0 .384.632.578 1.284.999.743.48.84.91.831 1.378-.01.971-.831 1.929-2.564 1.929-.791-.012-1.076-.078-1.72-.306l.272-1.256c.656.274.935.361 1.495.361.515 0 .956-.207.96-.568.002-.257-.155-.384-.732-.702-.577-.317-1.385-.756-1.375-1.64C12.033 9.759 13.107 9 14.63 9z" />
                            </svg>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                <path fill="none" d="M0 0h24v24H0z" />
                                <path d="M15 17a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15zM2 2h4v20H2V2z" />
                            </svg>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                <path fill="none" d="M0 0h24v24H0z" />
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-6v2h2v-2h1a2.5 2.5 0 0 0 2-4 2.5 2.5 0 0 0-2-4h-1V6h-2v2H8v8h3zm-1-3h4a.5.5 0 1 1 0 1h-4v-1zm0-3h4a.5.5 0 1 1 0 1h-4v-1z" />
                            </svg>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                <path fill="none" d="M0 0h24v24H0z" />
                                <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 0 0-.794.68l-.04.22-.63 3.993-.032.17a.804.804 0 0 1-.794.679H7.72a.483.483 0 0 1-.477-.558L7.418 21h1.518l.95-6.02h1.385c4.678 0 7.75-2.203 8.796-6.502zm-2.96-5.09c.762.868.983 1.81.752 3.285-.019.123-.04.24-.062.36-.735 3.773-3.089 5.446-6.956 5.446H8.957c-.63 0-1.174.414-1.354 1.002l-.014-.002-.93 5.894H3.121a.051.051 0 0 1-.05-.06l2.598-16.51A.95.95 0 0 1 6.607 2h5.976c2.183 0 3.716.469 4.523 1.388z" />
                            </svg>
                        </li>
                        </ul>
                    </div>
                    <div class="layout_item w-25">
                        <ul class="flex">
                        <li>
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm6.5-.25a1.25 1.25 0 0 0-2.5 0 1.25 1.25 0 0 0 2.5 0zM12 9a3 3 0 1 1 0 6 3 3 0 0 1 0-6z" />
                                </svg>
                            </a>
                        </li>
                        </ul>
                    </div>
                    <div class="layout_item w-25" style="display:flex;justify-content: end;align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm1 10h3l-4-4-4 4h3v4h2v-4z" />
                        </svg>
                    </div>
                    </div>
                </div>
                </div>
                <div class="footer_copyright">
                    <p>&copy; 2022 The Afterlogo Company Inc.</p>
                </div>
            </div>
        </footer>
    </div>
    <?php if ($jsvar == 1): ?>
        <script> 
            const jsvar = 1; 
            const discountCode = '<?php echo $discountCode; ?>'; 
        </script>
    <?php endif; ?>
    <script> const bookedDates = <?php echo json_encode($bookedDatesforLuxuryroom); ?>; </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://kit.fontawesome.com/7ca45ddd8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>