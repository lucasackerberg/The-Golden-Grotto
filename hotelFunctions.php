<?php
require __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

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

function isAvailable($db, $startDate, $endDate, $roomNumber) {
    // Perform a database query to check availability
    $query = "SELECT COUNT(*) FROM bookings 
              WHERE room_id = :room_number
              AND (checkin_date < :end_date AND checkout_date > :start_date)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':room_number', $roomNumber, PDO::PARAM_INT);
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    return $count === 0; // If count is 0, dates are available
}


function insertBooking($db, $startDate, $endDate, $firstname, $lastname, $poolAccess, $lavaMassage, $totalcosttot, $roomNumber) {
    var_dump($totalcosttot);
    // Perform a database query to insert the booking
    $query = "INSERT INTO bookings (checkin_date, checkout_date, booked_by, poolaccess, lavamassage, total_cost, room_id)
              VALUES (:start_date, :end_date, :full_name, :pool_access, :lava_massage, :total_cost, :room_id)";
    $stmt = $db->prepare($query);

    // Determine the values for pool access and lava massage based on checkbox status
    $poolAccessValue = $poolAccess ? 1 : 0;
    $lavaMassageValue = $lavaMassage ? 1 : 0;
    $fullName = "$firstname $lastname";

    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':pool_access', $poolAccessValue);
    $stmt->bindParam(':lava_massage', $lavaMassageValue);
    $stmt->bindParam(':total_cost', $totalcosttot);
    $stmt->bindParam(':room_id', $roomNumber);
    // Kolla varför totalcost inte hänger med.
    //echo "SQL Query: " . $stmt->queryString;
    $stmt->execute();
}

function insertEmail($db, $email) {
    // Perform a database query to insert the email
    $query = "INSERT INTO emails (email) VALUES (:email)";
    $stmt = $db->prepare($query);

    // Bind the email parameter
    $stmt->bindParam(':email', $email);

    // Execute the query
    $stmt->execute();
    return true;
}

function emailExists($db, $email) {
    // Check if the email already exists in the emails table
    $query = "SELECT COUNT(*) FROM emails WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the result
    $count = $stmt->fetchColumn();
    return $count > 0;
}

function generateDiscountCode($db) {
    // Select a random discount code from the discounts table
    $query = "SELECT code FROM discounts ORDER BY RANDOM() LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a discount code is retrieved
    if ($result && isset($result['code'])) {
        return $result['code'];
    } else {
        // Default discount code if none is found
        return "DEFAULTCODE";
    }
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

/* ----------------------------------------- GUZZLE ENDPOINT FUNCTIONS ------------------------- */

function checkTransferCode($transfercode, $totalcosttot)
{
    // Create a Guzzle client instance
    $client = new Client();

    try {
        // Guzzle request
        $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/transferCode', [
            'form_params' => [
                'transferCode' => $transfercode,
                'totalcost' => $totalcosttot
            ],
        ]);

        // Handle the response if it was successful
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        // Log API response
        $responseData = json_decode($body, true);

        // Access the transfer code, fromAccount, and amount
        $transferCode = $responseData['transferCode'];
        $fromAccount = $responseData['fromAccount'];
        $amount = $responseData['amount'];

        // Output the values
        /* echo $transferCode . "\n";
        echo $fromAccount . "\n";
        echo $amount . "\n"; */
        return true;

    } catch (\Exception $e) {
        // Error logga om något gick fel.
        error_log('Error: ' . $e->getMessage());
    }
}

function depositIntoBankAccount($transfercode)
{
    // Create a Guzzle client instance
    $client = new Client();

    try {
        // Guzzle request
        $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/deposit', [
            'form_params' => [
                'user' => 'Lucas',
                'transferCode' => $transfercode
            ],
        ]);

        // Handle the response if it was successful
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        // Log API response
        $responseData = json_decode($body, true);
        $successMessageFromBank = $responseData['message'];
        echo $successMessageFromBank . "\n";
        return true;

    } catch (\Exception $e) {
        // Error logga om något gick fel.
        error_log('Error: ' . $e->getMessage());
    }
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

function sanitizeEmail(string $email): string {
    if ($email === null) {
        return null;
    }

    // Trim leading and trailing whitespaces
    $trimmedEmail = trim($email);

    // Validate the email format
    if (filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
        // Return the sanitized email
        return $trimmedEmail;
    } else {
        // Invalid email format
        return null;
    }
}

// Booking JSON Response
function createJSONResponse($startDate, $endDate, $firstname, $lastname, $poolAccess, $lavaMassage, $totalcosttot, $roomNumber)
{
    global $features;
    $features = [];

    // Check if features are checked in the booking.
    if ($poolAccess) {
        $features[] = [
            "name" => "Pool Access",
            "cost" => 3
        ];
    }

    if ($lavaMassage) {
        $features[] = [
            "name" => "Massage Session",
            "cost" => 3
        ];
    }

    global $bookingResponse;
    $bookingResponse = [
        "island" => "Glimmering Bay",
        "hotel" => "The Golden Grotto",
        "arrival_date" => $startDate,
        "departure_date" => $endDate,
        "total_cost" => $totalcosttot,
        "stars" => "3",
        "features" => $features,
        "additional_info" => [
            "greeting" => "Thank you for choosing The Golden Grotto",
            "imageUrl" => "https://th.bing.com/th/id/OIG.XiKMT5gx3QIt13Wc4Bqi?w=1024&h=1024&rs=1&pid=ImgDetMain"
        ]
    ];

    // Convert the array to JSON format
    $bookingResponseJson = json_encode($bookingResponse);

    // Send the JSON response to the client
    return $bookingResponseJson;
}


?>
