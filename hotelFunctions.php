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
$bookedDatesforMediumroom = getBookedDatesforMediumroom($db);
$bookedDatesforCasualroom = getBookedDatesforCasualroom($db);

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

function isAvailable($db, $startDate, $endDate) {
    // Perform a database query to check availability
    // You'll need to customize this query based on your database schema
    $query = "SELECT COUNT(*) FROM bookings 
              WHERE (checkin_date < :end_date AND checkout_date > :start_date)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    return $count === 0; // If count is 0, dates are available
}

// hotelFunctions.php

function insertBooking($db, $startDate, $endDate, $firstname, $lastname, $poolAccess, $lavaMassage, $totalcosttot, $roomNumber) {
    var_dump($totalcosttot);
    // Perform a database query to insert the booking
    // Customize this query based on your database schema
    $query = "INSERT INTO bookings (checkin_date, checkout_date, booked_by, poolaccess, lavamassage, total_cost, room_id)
              VALUES (:start_date, :end_date, :full_name, :pool_access, :lava_massage, :total_cost, :room_id)";
    $stmt = $db->prepare($query);

    // Determine the values for pool access and lava massage based on checkbox status
    $poolAccessValue = $poolAccess ? 1 : 0;
    $lavaMassageValue = $lavaMassage ? 1 : 0;
    $fullName = "$firstname $lastname";
    var_dump($totalcosttot);
    var_dump($totalcosttot);

    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':pool_access', $poolAccessValue);
    $stmt->bindParam(':lava_massage', $lavaMassageValue);
    $stmt->bindParam(':total_cost', $totalcosttot);
    $stmt->bindParam(':room_id', $roomNumber);
    // Bind other parameters as needed
    echo "SQL Query: " . $stmt->queryString;
    $stmt->execute();
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

function sanitizeAndFormat(?string $date): ?array
{
    if ($date === null) {
        return null;
    }

    $sanitizedDate = htmlspecialchars(trim($date));
    list($startDate, $endDate) = explode(' - ', $sanitizedDate);

    // Convert to DateTime objects
    $startDateObj = DateTime::createFromFormat('m/d/Y', $startDate);
    $endDateObj = DateTime::createFromFormat('m/d/Y', $endDate);

    // Format the dates as 'YYYY-MM-DD'
    $formattedStartDate = $startDateObj->format('Y-m-d');
    $formattedEndDate = $endDateObj->format('Y-m-d');

    // Return an array of start and end dates
    return ['start' => $formattedStartDate, 'end' => $formattedEndDate];
}


function sanitizeName(string $name): string
{
    $sanitizedname = htmlspecialchars(trim($name));
    return $sanitizedname;
}

// Efter POST från room-sida. \/
// Function to calculate total cost
// !!!!!!!!!!!!!KANSKE TA BORT DENNA?!!!!!!!!!!
function calculateTotalCost($baseCostPerDay, $days, $additionalItems) {
    $costPerAdditionalItem = 3;
  
    // Calculate the total cost
    $totalCost = ($days * $baseCostPerDay) + ($additionalItems * $costPerAdditionalItem);
  
    return $totalCost;
}

?>
