<?php
    session_start();
    session_destroy();
    header("Location: ../View/Login_page_Niloy/Login_Page.php?success=" . urlencode("You have been successfully logged out"));
    exit();
?>