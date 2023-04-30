<?php
include '../inc/functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the dump ticket id exists, for example update.php?id=1 will get the dump ticket with the id of 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $load_id = isset($_POST['load_id']) ? $_POST['load_id'] : '';
        $truck_number = isset($_POST['truck_number']) ? $_POST['truck_number'] : '';
        $gross_weight = isset($_POST['gross_weight']) ? $_POST['gross_weight'] : '';
        $tare_weight = isset($_POST['tare_weight']) ? $_POST['tare_weight'] : '';
        $net_weight_tons = isset($_POST['net_weight_tons']) ? $_POST['net_weight_tons'] : '';
        $company = isset($_POST['company']) ? $_POST['company'] : '';
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d H:i:s');
        $material = isset($_POST['material']) ? $_POST['material'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE dump_tickets SET id = ?, load_id = ?, truck_number = ?, gross_weight = ?, tare_weight = ?, net_weight_tons = ?, company = ?, date = ?, material = ? WHERE id = ?');
        $stmt->execute([$id, $load_id, $truck_number, $gross_weight, $tare_weight, $net_weight_tons, $company, $date, $material, $_GET['id']]);
        $msg = 'Updated Successfully!';
        header('Location: dashboard.php');
    }
    // Get the dump ticket from the dump tickets table
    $stmt = $pdo->prepare('SELECT * FROM dump_tickets WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $dump_ticket = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$dump_ticket) {
        exit('dump ticket doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Dump Ticket #<?=$dump_ticket['id']?></h2>
    <form action="update.php?id=<?=$dump_ticket['id']?>" method="post">

        <label for="id">ID</label>
        <label for="load_id">Load ID</label>

        <input type="text" name="id" placeholder="1" value="<?=$dump_ticket['id']?>" id="id">
        <input type="text" name="load_id" placeholder="PW-Load-#" value="<?=$dump_ticket['load_id']?>" id="load_id">

        <label for="truck_number">Truck Number</label>
        <label for="gross_weight">Gross Weight</label>

        <input type="text" name="truck_number" placeholder="PW-Truck # or WM-Truck Type-#" value="<?=$dump_ticket['truck_number']?>" id="truck_number">
        <input type="text" name="gross_weight" placeholder="Enter Truck incoming Weight" value="<?=$dump_ticket['gross_weight']?>" id="gross_weight">

        <label for="tare_weight">Tare Weight</label>
        <label for="net_weight_tons">Tons</label>

        <input type="text" name="tare_Weight" placeholder="Enter truck outgoing weight" value="<?=$dump_ticket['tare_weight']?>" id="tare_weight">
        <input type="text" name="net_weight_tons" placeholder="Enter Tons" value="<?=$dump_ticket['net_weight_tons']?>" id="net_weight_tons">

        <label for="company">Company</label>
        <label for="date">Created Date</label>

        <input type="text" name="company" placeholder="Enter company Name" value="<?=$dump_ticket['company']?>" id="company">
        <input type="datetime-local" name="date" value="<?=date('Y-m-d\TH:i', strtotime($dump_ticket['date']))?>" id="date">

        <label for="material">Material Type</label>
        
        <input type="text" name="material" placeholder="Enter material type" value="<?=$dump_ticket['material']?>" id="material">

        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>