<?php

/* 
Here's something to start your career as a hotel manager.

One function to connect to the database you want (it will return a PDO object which you then can use.)
    For instance: $db = connect('hotel.db');
                  $db->prepare("SELECT * FROM bookings");
                  
one function to create a guid,
and one function to control if a guid is valid.
*/

/* --------------------------------------------------- DATABASE --------------------------------------> */
$dbName = 'database.db';
$db = connect($dbName);
$bookedDatesforLuxuryroom = getBookedDatesforLuxuryroom($db);

function connect(string $dbName): object
{
    $dbPath = __DIR__ . '/' . $dbName;
    $db = "sqlite:$dbPath";

    // Open the database file and catch the exception if it fails.
    try {
        $db = new PDO($db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Failed to connect to the database";
        throw $e;
    }
    return $db;
}

function getBookedDatesforLuxuryroom(PDO $db): array
{
    $stmt = $db->query("SELECT checkin_date, checkout_date FROM bookings WHERE room_id == 1");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getBookedDatesforMediumroom(PDO $db): array
{
    $stmt = $db->query("SELECT checkin_date, checkout_date FROM bookings WHERE room_id == 2");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getBookedDatesforCasualroom(PDO $db): array
{
    $stmt = $db->query("SELECT checkin_date, checkout_date FROM bookings WHERE room_id == 3");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/* ---------------------------------------------------- GUID ----------------------------------------> */
function guidv4(string $data = null): string
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function isValidUuid(string $uuid): bool
{
    if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
        return false;
    }
    return true;
}

/* --------------------------------------------- PHP BOOKING FUNCTIONS ------------------> */

function sanitizeAndSend(?string $date): ?string
{
    if ($date === null) {
        return null;
    }

    $sanitizedDate = htmlspecialchars(trim($date));
    list($startDate, $endDate) = explode(' - ', $sanitizedDate);

    // gör om till DateTime för att kunna räkna tid mellan dagarna.
    $startDateTime = new DateTime($startDate);
    $endDateTime = new DateTime($endDate);

    // Gör om till midnatt.
    $startDateTime->setTime(0, 0, 0);
    $endDateTime->setTime(0, 0, 0);
    // Calculate the difference in days
    $dateDiff = $startDateTime->diff($endDateTime);
    $daysDifference = $dateDiff->days + 1;

    // Echoa ut skillnaden i dagar.
    //echo "Difference in Days: $daysDifference";
    // Returna värdet.
    echo $startDate;
    echo $endDate;
    return $daysDifference;
}

// Efter POST från room-sida. \/
// Function to calculate total cost
function calculateTotalCost($baseCostPerDay, $days, $additionalItems) {
    $costPerAdditionalItem = 3;
  
    // Calculate the total cost
    $totalCost = ($days * $baseCostPerDay) + ($additionalItems * $costPerAdditionalItem);
  
    return $totalCost;
}

// Retrieve and sanitize other form data
// ...

// Retrieve selected checkboxes for extra features
$extraFeature = isset($_POST['extraFeature']) ? $_POST['extraFeature'] : 0;
$extraFeature2 = isset($_POST['extraFeature2']) ? $_POST['extraFeature2'] : 0;

// Base cost per day (you may get this value from your database or set it as needed)
$baseCostPerDay = 15;

// Calculate the total number of days (you need to implement this part based on your logic)
$days = 3; // Replace with the actual number of days selected by the user

// Calculate the total cost including extra features
$totalCost = calculateTotalCost($baseCostPerDay, $days, $extraFeature + $extraFeature2);

// Use $totalCost as needed, e.g., store in the database or display to the user
/* echo "Total Cost: $" . $totalCost; */
?>
