<?php
include 'main.php';

$accounts_total = $link->query('SELECT COUNT(*) AS total FROM user_accounts')->fetch_object()->total;
$dump_ticket_total = $link->query('SELECT COUNT(*) AS total FROM dump_tickets')->fetch_object()->total;
$down_trucks_total = $link->query('SELECT COUNT(*) AS total FROM down_trucks_board')->fetch_object()->total;

?>
<?=template_admin_header('Dashboard', 'dashboard')?>

<h2>Dashboard</h2>

<div class="dashboard">
    

    <div class="content-block stat">
        <div>
            <h3>Total Accounts</h3>
            <p><?=number_format($accounts_total)?></p>
        </div>
        <i class="fas fa-users"></i>
    </div>

    
    <div class="content-block stat">
        <div>
            <h3>Total Dump Tickets</h3>
            <p><?=number_format($dump_ticket_total)?></p>
        </div>
        <i class="fas fa-users"></i>
    </div>

    <div class="content-block stat">
        <div>
            <h3>Total Down Trucks</h3>
            <p><?=number_format($down_trucks_total)?></p>
        </div>
        <i class="fas fa-users"></i>
    </div>
    

</div>





<?=template_admin_footer()?>