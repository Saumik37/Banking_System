<?php
    session_start();
    session_destroy();
    // Fix the path to correctly point to the login page
    header("Location: ../View/Login_page_Niloy/Login_Page.php?success=" . urlencode("You have been successfully logged out"));
    exit();
?>