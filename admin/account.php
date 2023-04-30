<?php
include 'main.php';
// Default input product values
$account = [
    'username' => '',
    'password' => '',
    'role' => 'Member'
];
// If editing an account
if (isset($_GET['id'])) {
    // Get the account from the database
    $stmt = $link->prepare('SELECT username, password, role FROM user_accounts WHERE id = ?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->bind_result($account['username'], $account['password'],  $account['role']);
    $stmt->fetch();
    $stmt->close();
    // ID param exists, edit an existing account
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the account
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $account['password'];
        $stmt = $link->prepare('UPDATE user_accounts SET username = ?, password = ?, role = ? WHERE id = ?');
        $stmt->bind_param('ssi', $_POST['username'], $password,  $_POST['role'], $_GET['id']);
        $stmt->execute();
        header('Location: accounts.php?success_msg=2');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Redirect and delete the account
        header('Location: accounts.php?delete=' . $_GET['id']);
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $link->prepare('INSERT IGNORE INTO user_accounts (username,password,role) VALUES (?,?,?)');
        $stmt->bind_param('sss', $_POST['username'], $password,  $_POST['role']);
        $stmt->execute();
        header('Location: accounts.php?success_msg=1');
        exit;
    }
}
?>
<?=template_admin_header($page . ' Account', 'accounts', 'manage')?>

<h2><?=$page?> Account</h2>

<div class="content-block">

    <form action="" method="post" class="form responsive-width-100">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" value="<?=$account['username']?>" required>

        <label for="password"><?=$page == 'Edit' ? 'New ' : ''?>Password</label>
        <input type="text" id="password" name="password" placeholder="<?=$page == 'Edit' ? 'New ' : ''?>Password" value=""<?=$page == 'Edit' ? '' : ' required'?>>

        

        <label for="role">Role</label>
        <select id="role" name="role" style="margin-bottom: 30px;">
            <?php foreach ($roles_list as $role): ?>
            <option value="<?=$role?>"<?=$role==$account['role']?' selected':''?>><?=$role?></option>
            <?php endforeach; ?>
        </select>

        
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete" onclick="return confirm('Are you sure you want to delete this account?')">
            <?php endif; ?>
        </div>

    </form>

</div>

<?=template_admin_footer()?>