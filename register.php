
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <link href="css/styleauth.css" rel="stylesheet" type="text/css">
        <link href="css/regstyle.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
	<body>
		<div class="register">
			<h1>Register</h1>
			<form action="inc/regauth.php" method="post" autocomplete="off">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				
				
				<input type="submit" value="Register">
			</form>
		</div>
	</body>
</html>