<?php
include '../inc/functions.php';
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $truck_number = isset($_POST['truck_number']) ? $_POST['truck_number'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
    $down_date = isset($_POST['down_date']) ? $_POST['down_date'] : date('Y-m-d H:i:s');


    // Insert new record into the down trucks table
    $stmt = $pdo->prepare('INSERT INTO down_trucks_board VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$id, $truck_number, $location, $reason, $down_date]);
    // Output message
    $msg = 'Created Successfully!';
    header('Location: dashboard.php');
}
?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    </head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2>Create Down Truck Board Ticket</h2>
      <form action="create_down.php" method="post">
        <div class="form-group">
          <label for="truck_number">Truck Number</label>
          <select name="truck_number" class="form-control" id="truck_number">
            <?php
              // get all trucks from truck database
              $stmt = $pdo->query('SELECT * FROM trucks');
              $trucks = $stmt->fetchAll(PDO::FETCH_ASSOC);

              // loop through trucks and create option for each one
              foreach ($trucks as $truck) {
                echo '<option value="' . $truck['truck_number'] . '">' . $truck['truck_number'] . '</option>';
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="id">ID</label>
          <input type="text" class="form-control" name="id" placeholder="26" value="auto" id="id">
        </div>
        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" class="form-control" name="location" placeholder="Which shop is working on this truck?" id="location">
        </div>
        <div class="form-group">
          <label for="reason">Reason</label>
          <input type="text" class="form-control" name="reason" placeholder="Why is this truck down?" id="reason">
        </div>
        <div class="form-group">
          <label for="down_date">Date Truck Went Down</label>
          <input type="datetime-local" class="form-control" name="down_date" value="<?=date('Y-m-d\TH:i')?>" id="down_date">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      <?php if ($msg): ?>
        <p><?=$msg?></p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
