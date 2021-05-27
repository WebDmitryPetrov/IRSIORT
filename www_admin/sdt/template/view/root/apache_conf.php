<?
/*
$echo=array();

$echo[]='<VirtualHost *:443>';
$echo[]='SetEnv head_center '.$item->id;
$echo[]='ServerName '.$item->href;
$echo[]='';
$echo[]='';
$echo[]='DocumentRoot "c:/www"';
$echo[]='ServerAdmin admin@staff.rudn.ru';
$echo[]='ErrorLog "C:/Apache24/logs/error.log"';
$echo[]='TransferLog "C:/Apache2.2/logs/access.log"';
$echo[]='SSLEngine on';
$echo[]='SSLProtocol all -SSLv2';
$echo[]='SSLCipherSuite ALL:!ADH:!EXPORT:!SSLv2:RC4+RSA:+HIGH:+MEDIUM';
$echo[]='';
$echo[]='SSLCertificateFile C:/Apache2.2/conf/startssl/gctrki.crt';
$echo[]='SSLCertificateKeyFile C:/Apache2.2/conf/startssl/private.key.unsecure';
$echo[]='SSLCertificateChainFile C:/Apache2.2/conf/startssl/sub.class1.server.ca.pem';
$echo[]='';
$echo[]='';
$echo[]='<FilesMatch "\.(cgi|shtml|phtml|php)$">';
$echo[]='SSLOptions +StdEnvVars';
$echo[]='</FilesMatch>';
$echo[]='<Directory "C:/Apache2.2/cgi-bin">';
    $echo[]='SSLOptions +StdEnvVars';
$echo[]='</Directory>';
    $echo[]='BrowserMatch ".*MSIE.*" \ ';
$echo[]='nokeepalive ssl-unclean-shutdown \ ';
$echo[]='downgrade-1.0 force-response-1.0';
$echo[]='CustomLog "C:/Apache2.2/logs/ssl_request.log" \ ';
$echo[]='"%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"';
$echo[]='RewriteEngine on';
$echo[]='</VirtualHost>';


foreach ($echo as $out)
{
    echo htmlspecialchars($out);
    echo '<br>';

}*/

$a= <<<EVB

<VirtualHost *:443>
SetEnv head_center {$item->id}
ServerName {$item->href}


DocumentRoot "c:/www"
ServerAdmin ovitmail@pfur.ru
ErrorLog "C:/Apache24/logs/error.log"
TransferLog "C:/Apache24/logs/access.log"
SSLEngine on
SSLProtocol all -SSLv2
SSLCipherSuite HIGH:MEDIUM:!aNULL:!MD5


   SSLCertificateFile conf\\current\\rudn_ru_multi.crt
   SSLCertificateKeyFile conf\\current\\private.rudn.ru.key
   SSLCertificateChainFile conf\\current\\chain.crt
   


<FilesMatch "\.(cgi|shtml|phtml|php)$">
    SSLOptions +StdEnvVars
</FilesMatch>
<Directory "C:/Apache24/cgi-bin">
    SSLOptions +StdEnvVars
</Directory>
BrowserMatch ".*MSIE.*" \
         nokeepalive ssl-unclean-shutdown \
         downgrade-1.0 force-response-1.0
CustomLog "C:/Apache24/logs/ssl_request.log" \
          "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"
RewriteEngine on
</VirtualHost>


EVB;


?>
<h3>Õ‡ÒÚÓÈÍË apache ‰Îˇ "<?=$item->name;?>"</h3>

<pre>
    <?= strtr(htmlspecialchars($a), array('%KEY_FILE%'=>'<strong style="color: red; text-transform: uppercase;font-size: 16pt">œ”“‹ ƒŒ —≈–“»‘» ¿“¿</strong>'));?>
</pre>