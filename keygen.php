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


Example SPKAC
=============

SPKAC=<?php echo $spkac; ?>
CN=Your Name
emailAddress=your.name@mail.com
0.OU=Company client certificate
organizationName=Company
countryName=NO
stateOrProvinceName=Oslo
localityName=Oslo

Save to /tmp/tmp.spkac


Example openssl sig
====================

openssl ca -config /etc/CA/openssl.conf -days 36500 -notext -batch -spkac /tmp/tmp.spkac -out /tmp/tmp


Example install script
======================

$file = '/tmp/tmp';
$length = filesize($file);
header('Last-Modified: '.date('r+b'));
header('Accept-Ranges: bytes');
header('Content-Length: '.$length);
header('Content-Type: application/x-x509-user-cert');
readfile($file);
exit;

</body>
</html>
