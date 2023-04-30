<?php
include '../inc/functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM dump_tickets WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $dump_ticket = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$dump_ticket) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM dump_tickets WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the dump ticket!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ./dashboard.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Dump Ticket #<?=$dump_ticket['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete dump ticket #<?=$dump_ticket['id']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$dump_ticket['id']?>&confirm=yes">Yes</a>
        <a href="delete.php?id=<?=$dump_ticket['id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>