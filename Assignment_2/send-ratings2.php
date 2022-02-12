<?php
// (A) DATABASE CREDENTIALS - CHANGE TO YOUR OWN!
define("DB_HOST", "localhost");
define("DB_NAME", "cuny607");
define("DB_CHARSET", "utf8");
define("DB_USER", "data607usr");
define("DB_PASSWORD", "data607password");

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
 
 // echo generateRandomString();
 // $mydate = date('Y-m-d H:i:s');
// mysql_query("INSERT INTO `table` (`dateposted`) VALUES ('$date')");
 
 
 
 $rater_code = generateRandomString(3);
 $rater_country = "US";
 $mydate = date('Y-m-d H:i:s');
  
 
// (B) CONNECT TO DATABASE
$error = NULL;
try {
  $pdo = new PDO(
    "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET . ";dbname=" . DB_NAME,
    DB_USER, DB_PASSWORD, [ 
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
} catch (Exception $ex) { $error = $ex->getMessage(); }
 
// (C) INSERT
if (is_null($error)) {
  try {
	$stmt = $pdo->prepare("INSERT INTO `raters` (`rater_code`, `rater_name`, `rater_country_code`) VALUES (?, ?, ?)");
    $stmt->execute([$rater_code, $_POST["name"], $rater_country]);

	$stmt = $pdo->prepare("INSERT INTO `ratings` (`rater_code`, `movie_code`, `rating`,`date` ) VALUES (?, ?, ?, ?)");
    $stmt->execute([$rater_code, "001", $_POST["A"], $mydate]);

	$stmt = $pdo->prepare("INSERT INTO `ratings` (`rater_code`, `movie_code`, `rating`,`date` ) VALUES (?, ?, ?, ?)");
    $stmt->execute([$rater_code, "002", $_POST["B"], $mydate]);
	
	$stmt = $pdo->prepare("INSERT INTO `ratings` (`rater_code`, `movie_code`, `rating`,`date` ) VALUES (?, ?, ?, ?)");
    $stmt->execute([$rater_code, "003", $_POST["C"], $mydate]);

	$stmt = $pdo->prepare("INSERT INTO `ratings` (`rater_code`, `movie_code`, `rating`,`date` ) VALUES (?, ?, ?, ?)");
    $stmt->execute([$rater_code, "004", $_POST["D"], $mydate]);

	$stmt = $pdo->prepare("INSERT INTO `ratings` (`rater_code`, `movie_code`, `rating`,`date` ) VALUES (?, ?, ?, ?)");
    $stmt->execute([$rater_code, "005", $_POST["E"], $mydate]);

	$stmt = $pdo->prepare("INSERT INTO `ratings` (`rater_code`, `movie_code`, `rating`,`date` ) VALUES (?, ?, ?, ?)");
    $stmt->execute([$rater_code, "006", $_POST["F"], $mydate]);


  } catch (Exception $ex) { $error = $ex->getMessage(); }
}

// (D) RESULTS
echo is_null($error) ? "Your ratings were recorded in MySQL DB<br><br>" : $error ;
echo "<a href='http://35.174.11.125/list-records2.php'>List all ratings submitted (including yours) !</a><br>";
echo "<a href='http://35.174.11.125/data607c.html'>Go back to input a rating </a><br>";




