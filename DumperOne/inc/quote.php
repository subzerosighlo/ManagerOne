<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of inputs
    $weight = intval($_POST['weight']);
    $R_P_T = intval($_POST['R_P_T']);
    

    if (empty($weight)) {
        echo '<div class="alert alert-warning" role="alert">Weight must be entered</div>';
    } else {
        $weight = $weight;
    }
    
    
    if (empty($R_P_T)) {
        echo '<div class="alert alert-warning" role="alert">Rate per Ton needs to be entered</div>';
    } else {
        $R_P_T = $R_P_T;
    }
    
    $qoute = $weight * $R_P_T;
    if (!$qoute) {
        return;
    } else {
    echo '<div class="alert alert-success" role="alert"><h1>Your quote is: $' . $qoute . '</h1></div>';
}
}
?>