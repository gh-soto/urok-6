<?php
ob_start();
session_start();
header("X-XSS-Protection: 0"); 
header("Content-Type: text/html; charset=utf-8");

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<script src="js/jquery-1.11.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<link href='https://fonts.googleapis.com/css?family=Play:400,700' rel='stylesheet' type='text/css'>
	<title>Завдання 1</title>
</head>
<body>
	<nav class="navbar navbar-default">
		<a class="btn btn-primary btn-block" href="index.php">головна</a>
		<a class="btn btn-default btn-block" href="task1.php">завдання 1</a>
	</nav>

	

		<?php

			if (!isset($_POST['db_check'])) {
				print '<div class="container container2">
						<form  role="form" action="task1.php" method="POST">
							<div class="form-group">

								<label for="dbname">Database name:</label>
								<input class="form-control" type="text" name="db_name" id="dbname" required>

								<label for="dbusername">Username:</label>
								<input class="form-control" type="text" name="db_username" id="dbusername" required>

								<label for="dbpassword">Password:</label>
								<input class="form-control" type="password" name="db_password" id="dbpassword">
								
								<input class="btn btn-info" type="submit" name="db_check" value="connect">

							<div>
						</form>';

			}

			elseif (isset($_POST['db_check'])) {

				//можна і функцією це все поскорочувати, але я не хочу
				$db_name = $_POST['db_name'];
				$db_name = strip_tags($db_name);
				$db_name = htmlspecialchars($db_name, ENT_QUOTES);

				$db_username = $_POST['db_username'];
				$db_username = strip_tags($db_username);
				$db_username = htmlspecialchars($db_username, ENT_QUOTES);

				$db_password = $_POST['db_password'];
				$db_password = strip_tags($db_password);
				$db_password = htmlspecialchars($db_password, ENT_QUOTES);

				try {
					$conn = new PDO("mysql:host=localhost; dbname=$db_name", $db_username, $db_password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}catch(PDOException $e) {
					print ('<u><b>Помилка:</b></u> ' . $e->getMessage());
				}

				if ($conn) {
					print "<h5>вдалося підключитись до бази даних</h5>";
					$_SESSION['db_name'] = $db_name;
					$_SESSION['db_username'] = $db_username;
					$_SESSION['db_password'] = $db_password;

					//перевірка на існування таблиці
					try {
					$querycheck = 'SELECT 1 FROM USERS';
					$query_result=$conn->query($querycheck);
					print 'в базі даних ' . $db_name . ' є таблиця USERS';

					header("Refresh: 5; URL = http://" . $_SERVER['HTTP_HOST'] . "/task2.php");

					} catch (PDOException $e) {
						print 'в базі даних таблиці USERS не було<br/>';
						$query = $conn->prepare ("CREATE TABLE `USERS` (
						                          `uid` int(11) AUTO_INCREMENT,
						                          `name` varchar(255) NOT NULL,
						                          `password` varchar(255) NOT NULL,
						                          PRIMARY KEY  (uid)
						                          )");
						$query->execute();
						print 'тому створено нову таблицю USERS';
						header("Refresh: 5; URL = http://" . $_SERVER['HTTP_HOST'] . "/task2.php");
					}
				}
				
			}
			
			
						
		?>
	</div>
	
</body>
</html>