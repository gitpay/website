<?php

$opensslconf = '/etc/ssl/openssl.cnf';
$spakc = $_REQUEST['key'];
$days = 36500;

// processing code goes here

?>

<!DOCTYPE html>
<html>
<body>

Your public key is.  Please use this value to generate ssh / x.509 certificates and upload to github.

<?php echo $spakc; ?>

</body>
</html>
