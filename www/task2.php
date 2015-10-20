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
	<title>Завдання 2</title>
</head>
<body>
	<nav class="navbar navbar-default">
		<a class="btn btn-primary btn-block" href="index.php">головна</a>
		<a class="btn btn-default btn-block" href="task2.php">завдання 2</a>
	</nav>

	<div class="container">

		<?php
			if (isset($_SESSION['db_name']) && isset($_SESSION['db_username']) && isset($_SESSION['db_password'])) {
				$db_name = $_SESSION['db_name'] ;
				$db_username = $_SESSION['db_username'];
				$db_password = $_SESSION['db_password'];

				try {
					$conn = new PDO("mysql:host=localhost; dbname=$db_name", $db_username, $db_password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}catch(PDOException $e) {
					print ('<u><b>Помилка:</b></u> ' . $e->getMessage());
				}
				if (!empty($_POST['name']) and !empty($_POST['password'])) {

					if (isset($_POST['name']) && isset($_POST['password'])) {

						$name = $_POST['name'];
						$name = strip_tags($name);
						$name = htmlspecialchars($name, ENT_QUOTES);

						$password = $_POST['password'];
						$password = strip_tags($password);
						$password = htmlspecialchars($password, ENT_QUOTES);
						$password = md5($password);


						$query = $conn->query("SELECT * FROM `USERS` WHERE name='$name'"); 
						$user_data = $query->fetch (PDO::FETCH_ASSOC);

						if ($user_data['name'] == NULL) {

							$insert = array('name'=>$name, 'password'=>$password);
							$STH = $conn->prepare("INSERT INTO `USERS` (name, password) values(:name, :password)");
							$STH->execute($insert);
							print 'зроблено запис до таблиці (новий користувач)';
						}

						elseif ($name == $user_data['name'] && $password == $user_data['password']) {
							$_SESSION['name'] = $name;
							

						}
						else {
							print 'Wrong username or password';
						}
					}
				}

				if (!isset($_SESSION['name'])) {
					print '<form  class="form-inline " role="form" action="task2.php" method="POST">
							<div class="form-group">
								<input class="form-control" type="text" name="name" placeholder="name" required>
								<input class="form-control" type="password" name="password" placeholder="password" required>
								<input class="btn btn-danger" type="submit" name="submit" value="log in">
							<div>
							</form>' ;
				}

				else {
					print '<h3>Welcome!</h3><p><a href="session-destroy.php">log out</a>';
				}
				

			}

			else {
				print 'you have to connect to database';
			}

		?>

	</div>
	
</body>
</html>