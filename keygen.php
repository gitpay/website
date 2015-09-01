<?php

$opensslconf = '/etc/ssl/openssl.cnf';
$spakc = $_REQUEST['key'];
$days = 36500;

// processing code goes here

?>

<!DOCTYPE html>
<html>
<body>

Thank you for submitting a public key.

<?php echo $spakc; ?>

</body>
</html>
