<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="css/styleauth.css" rel="stylesheet" type="text/css">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/quotestyle.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Project ManagerOne V1.0</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                <?php if ($_SESSION['role'] == 'Admin'): ?>
				<a href="admin/index.php" target="_blank"><i class="fas fa-user-cog"></i>Admin</a>
				<?php endif; ?>
			</div>
		</nav>
		<div class="content">
			<h2>Dashboard</h2>
			<?php
include 'inc/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Check if a user ID and dump ticket ID were passed in the URL parameters
if (isset($_GET['user_id']) && isset($_GET['dump_ticket_id'])) {
    $user_id = $_GET['user_id'];
    $dump_ticket_id = $_GET['dump_ticket_id'];

    // Prepare and execute a SQL statement to assign the dump ticket to the user
    $stmt = $pdo->prepare('UPDATE user_dump_tickets SET dump_ticket_id = :dump_ticket_id WHERE user_id = :user_id');
    $stmt->bindParam(':dump_ticket_id', $dump_ticket_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM dump_tickets ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$dump_ticket = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_dump_tickets = $pdo->query('SELECT COUNT(*) FROM dump_tickets')->fetchColumn();
?>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>

<div class="content read">
	<h2>Dump Tickets</h2>
	<a href="./DumperOne/create.php" class="create-contact">Create Dump Ticket</a>
	<table>
    <thead>
            <tr>
                <td>#</td>
                <td>Load ID</td>
                <td>Truck Number</td>
                <td>Gross Weight</td>
                <td>Tare Weight</td>
                <td>Tons</td>
                <td>Company</td>
                <td>Date</td>
                <td>Material</td>
                <td>Assign Ticket</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dump_ticket as $dump_ticket): ?>
            <tr>
                <td><?=$dump_ticket['id']?></td>
                <td><?=$dump_ticket['load_id']?></td>
                <td><?=$dump_ticket['truck_number']?></td>
                <td><?=$dump_ticket['gross_weight']?></td>
                <td><?=$dump_ticket['tare_weight']?></td>
                <td><?=$dump_ticket['net_weight_tons']?></td>
                <td><?=$dump_ticket['company']?></td>
                <td><?=$dump_ticket['date']?></td>
                <td><?=$dump_ticket['material']?></td>
                <td>
					<form method="get">
						<input type="hidden" name="user_id" value="<?=$user_id?>">
						<input type="hidden" name="dump_ticket_id" value="<?=$dump_ticket['id']?>">
						<button type="submit">Assign</button>
					</form>
				</td>
               
                <td class="actions">
                    <a href="DumperOne/update.php?id=<?=$dump_ticket['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$dump_ticket['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                    <a href="pdf.php?id=<?=$dump_ticket['id']?>" class="pdf"><i class="fa-solid fa-file-pdf fa-sm" style="color: #28549f;"></i></a>
                </td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="dashboard.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_dump_tickets): ?>
		<a href="dashboard.php?page=<?=htmlspecialchars($page+1)?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>




</div>

<div class="content">


<?php
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM down_trucks_board ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$down_truck_board = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<div class="content read">
	<h2>Current Down Truck List</h2>
	<a href="down/create_down.php" class="create-contact">Add Truck to Down List</a>
	<table>
    <thead>
            <tr>
                <td>#</td>
                <td>Truck Number</td>
                <td>Location</td>
                <td>Reason</td>
                <td>Date Down</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($down_truck_board as $down_truck_board): ?>
            <tr>
                <td><?=$down_truck_board['id']?></td>
                <td><?=$down_truck_board['truck_number']?></td>
                <td><?=$down_truck_board['location']?></td>
                <td><?=$down_truck_board['reason']?></td>
                <td><?=$down_truck_board['down_date']?></td>
               
                <td class="actions">
                    <a href="update.php?id=<?=$down_truck_board['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$down_truck_board['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                    <a href="pdf.php?id=<?=$down_truck_board['id']?>" class="pdf"><i class="fa-solid fa-file-pdf fa-sm" style="color: #28549f;"></i></a>
                </td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	
</div>


</div>
	</body>
</html>