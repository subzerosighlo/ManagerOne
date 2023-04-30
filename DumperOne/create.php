<?php
include 'inc/functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $load_id = isset($_POST['load_id']) ? $_POST['load_id'] : '';
    $truck_number = isset($_POST['truck_number']) ? $_POST['truck_number'] : '';
    $gross_weight = isset($_POST['gross_weight']) ? $_POST['gross_weight'] : '';
    $tare_weight = isset($_POST['tare_weight']) ? $_POST['tare_weight'] : '';
    $company = isset($_POST['company']) ? $_POST['company'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d H:i:s');
    $material = isset($_POST['material']) ? $_POST['material'] : '';

    $net_weight = $gross_weight - $tare_weight;
    $net_weight_tons = $net_weight / 2000;
    $net_weight_tons = round($net_weight_tons, 2);

    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO dump_tickets VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$id, $load_id, $truck_number, $gross_weight, $tare_weight, $net_weight_tons, $company, $date, $material]);
    // Output message
    $msg = 'Created Successfully!';
    header('Location: dashboard.php');
}
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
<div class="content create">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<h2>Create Dump Ticket</h2>
    <form action="create.php" method="post" class="form">
  <div class="form-group">
    <label for="truck_number" class="form-label">Truck Number</label>
    <select name="truck_number" id="truck_number" class="form-select">
      <?php foreach ($trucks as $truck) { ?>
        <option value="<?= $truck['truck_number'] ?>"><?= $truck['truck_number'] ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="form-group">
    <label for="id" class="form-label">ID</label>
    <input type="text" name="id" id="id" class="form-control" placeholder="26" value="auto">
  </div>
  <div class="form-group">
    <label for="load_id" class="form-label">Load ID</label>
    <input type="text" name="load_id" id="load_id" class="form-control" placeholder="PW-Load-123">
  </div>
  <div class="form-group">
    <label for="gross_weight" class="form-label">Gross Weight</label>
    <input type="text" name="gross_weight" id="gross_weight" class="form-control" placeholder="Enter the truck incoming weight">
  </div>
  <div class="form-group">
    <label for="tare_weight" class="form-label">Tare Weight</label>
    <input type="text" name="tare_weight" id="tare_weight" class="form-control" placeholder="Enter the truck outgoing weight">
  </div>
  <div class="form-group">
    <label for="company" class="form-label">Company</label>
    <input type="text" name="company" id="company" class="form-control" placeholder="Priority Waste or Waste Management">
  </div>
  <div class="form-group">
    <label for="date" class="form-label">Today Date</label>
    <input type="datetime-local" name="date" id="date" class="form-control" value="<?= date('Y-m-d\TH:i') ?>">
  </div>
  <div class="form-group">
    <label for="material" class="form-label">Material</label>
    <input type="text" name="material" id="material" class="form-control" placeholder="MSW or Recycle">
  </div>
  <button type="submit" class="btn btn-primary">Create</button>
</form>

    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

    </body>
</html>