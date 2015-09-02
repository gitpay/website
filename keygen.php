<?php

$opensslconf = '/etc/ssl/openssl.cnf';
$spkac = $_REQUEST['key'];
$days = 36500;

// processing code goes here

?>

<!DOCTYPE html>
<html>
<body>

<pre>
Your signed public key and challenge (spkac) is:

<?php echo $spkac; ?>


Please use this value to locally generate ssh / x.509 certificates and upload to github and your browser.

<a href="http://phpmylogin.sourceforge.net/wiki/doku.php?id=keygen_attribute">seeAlso</a>

</body>
</html>
