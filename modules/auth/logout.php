<?php
session_start();
session_unset();
session_destroy();
session_start();
session_unset();
session_destroy();
echo json_encode(['success' => 'Logged out successfully']);
?>
<?php


header('Location: ../login.html');
exit;
?>
