<?php
// Include the root "config.php" and "main.php" files
include_once '../config.php';

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

// Fetch account details associated with the logged-in user
$stmt = $link->prepare('SELECT password, role, username FROM user_accounts WHERE id = ?');
// Get the account info using the logged-in session ID
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $role, $username);
$stmt->fetch();
$stmt->close();
// Check if the user is an admin...
if ($role != 'Admin') {
    exit('You do not have permission to access this page!');
}
// Add/remove roles from the list
$roles_list = ['Admin', 'Member'];
// Template admin header
function template_admin_header($title, $selected = 'dashboard', $selected_child = '') {
    // Admin HTML links
    $admin_links = '
        <a href="index.php"' . ($selected == 'dashboard' ? ' class="selected"' : '') . '><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="accounts.php"' . ($selected == 'accounts' ? ' class="selected"' : '') . '><i class="fas fa-users"></i>Accounts</a>
        <div class="sub">
            <a href="accounts.php"' . ($selected == 'accounts' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Accounts</a>
            <a href="account.php"' . ($selected == 'accounts' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Account</a>
        </div>
        <a href="roles.php"' . ($selected == 'roles' ? ' class="selected"' : '') . '><i class="fas fa-list"></i>Roles</a>
        
        <a href="settings.php"' . ($selected == 'settings' ? ' class="selected"' : '') . '><i class="fas fa-tools"></i>Settings</a>
    ';
    // Indenting the below code may cause an error
echo <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <title>$title</title>
        <link href="admin.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body class="admin">
        <aside class="responsive-width-100 responsive-hidden">
            <h1>Admin Panel</h1>
            $admin_links
        </aside>
        <main class="responsive-width-100">
            <header>
                <a class="responsive-toggle" href="#">
                    <i class="fas fa-bars"></i>
                </a>
                <div class="space-between"></div>
                <a href="about.php" class="right"><i class="fas fa-question-circle"></i></a>
                <a href="account.php?id={$_SESSION['id']}" class="right"><i class="fas fa-user-circle"></i></a>
                <a href="../logout.php" class="right"><i class="fas fa-sign-out-alt"></i></a>
            </header>
EOT;
}
// Template admin footer
function template_admin_footer() {
    // Indenting the below code may cause an error
echo <<<EOT
        </main>
        <script>
        let aside = document.querySelector("aside"), main = document.querySelector("main"), header = document.querySelector("header");
        let asideStyle = window.getComputedStyle(aside);
        if (localStorage.getItem("admin_menu") == "closed") {
            aside.classList.add("closed", "responsive-hidden");
            main.classList.add("full");
            header.classList.add("full");
        }
        document.querySelector(".responsive-toggle").onclick = event => {
            event.preventDefault();
            if (asideStyle.display == "none") {
                aside.classList.remove("closed", "responsive-hidden");
                main.classList.remove("full");
                header.classList.remove("full");
                localStorage.setItem("admin_menu", "");
            } else {
                aside.classList.add("closed", "responsive-hidden");
                main.classList.add("full");
                header.classList.add("full");
                localStorage.setItem("admin_menu", "closed");
            }
        };
        document.querySelectorAll(".tabs a").forEach((element, index) => {
            element.onclick = event => {
                event.preventDefault();
                document.querySelectorAll(".tabs a").forEach((element, index) => element.classList.remove("active"));
                document.querySelectorAll(".tab-content").forEach((element2, index2) => {
                    if (index == index2) {
                        element.classList.add("active");
                        element2.style.display = "block";
                    } else {
                        element2.style.display = "none";
                    }
                });
            };
        });
        if (document.querySelector(".filters a")) {
            let filtersList = document.querySelector(".filters .list");
            let filtersListStyle = window.getComputedStyle(filtersList);
            document.querySelector(".filters a").onclick = event => {
                event.preventDefault();
                if (filtersListStyle.display == "none") {
                    filtersList.style.display = "flex";
                } else {
                    filtersList.style.display = "none";
                }
            };
            document.onclick = event => {
                if (!event.target.closest(".filters")) {
                    filtersList.style.display = "none";
                }
            };
        }
        document.querySelectorAll(".msg").forEach(element => {
            element.querySelector(".fa-times").onclick = () => {
                element.remove();
                history.replaceState && history.replaceState(null, '', location.pathname + location.search.replace(/[\?&]success_msg=[^&]+/, '').replace(/^&/, '?') + location.hash);
                history.replaceState && history.replaceState(null, '', location.pathname + location.search.replace(/[\?&]error_msg=[^&]+/, '').replace(/^&/, '?') + location.hash);
            };
        });
        history.replaceState && history.replaceState(null, '', location.pathname + location.search.replace(/[\?&]success_msg=[^&]+/, '').replace(/^&/, '?') + location.hash);
        history.replaceState && history.replaceState(null, '', location.pathname + location.search.replace(/[\?&]error_msg=[^&]+/, '').replace(/^&/, '?') + location.hash);
        </script>
    </body>
</html>
EOT;
}
// Convert date to elapsed string function
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = ['y' => 'year','m' => 'month','w' => 'week','d' => 'day','h' => 'hour','i' => 'minute','s' => 'second'];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>