<?php
include 'main.php';
// Prepare roles query
$roles = $link->query('SELECT role, COUNT(*) as total FROM user_accounts GROUP BY role')->fetch_all(MYSQLI_ASSOC);
$roles = array_column($roles, 'total', 'role');
foreach ($roles_list as $r) {
    if (!isset($roles[$r])) $roles[$r] = 0;
}

?>
<?=template_admin_header('Roles', 'roles')?>

<h2>Roles</h2>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>Role</td>
                    <td>Total Accounts</td>
                    <td>Active Accounts</td>
                    <td>Inactive Accounts</td>
                </tr>
            </thead>
            <tbody>
                <?php if (!$roles): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no roles</td>
                </tr>
                <?php endif; ?>
                <?php foreach ($roles as $k => $v): ?>
                <tr>
                    <td><?=$k?></td>
                    <td><a href="accounts.php?role=<?=$k?>"><?=number_format($v)?></a></td>
                    <td><a href="accounts.php?role=<?=$k?>&status=active"><?=number_format(isset($roles_active[$k]) ? $roles_active[$k] : 0)?></a></td>
                    <td><a href="accounts.php?role=<?=$k?>&status=inactive"><?=number_format(isset($roles_inactive[$k]) ? $roles_inactive[$k] : 0)?></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>