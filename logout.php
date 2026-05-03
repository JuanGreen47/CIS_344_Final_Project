<?php
require_once 'config/config.php';
session_unset();
session_destroy();
header('Location: /real_estate_portal/index.php');
exit;
?>