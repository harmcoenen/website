<!DOCTYPE html>
<html lang="en">
<head>
    <title>ip-address</title>
    <meta charset="UTF-8">
    <meta name="generator" content="Notepad++" />
    <meta name="copyright" content="(c) 2019 Harm Coenen" />
    <meta name="description" content="Familie Coenen IP address" />
    <meta name="keywords" content="Coenen,familiecoenen,familie coenen,Beugen" />
    <meta name="author" content="Harm Coenen" />
    <meta name="robots" content="index,follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3-custom.css">
    <link rel="stylesheet" href="w3.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg" />
</head>

<body class="w3-pale-purple">

<?php
function get_ip_address() {
    // check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];

    // check for IPs passing through proxies
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check if multiple ips exist in var
        $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($iplist as $ip) {
            if ($this->validate_ip($ip))
                return $ip;
        }
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];

    // return unreliable ip since all else failed
    return $_SERVER['REMOTE_ADDR'];
}

function validate_ip($ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP, 
                        FILTER_FLAG_IPV4 | 
                        FILTER_FLAG_IPV6 |
                        FILTER_FLAG_NO_PRIV_RANGE | 
                        FILTER_FLAG_NO_RES_RANGE) === false)
        return false;
    self::$ip = $ip;
    return true;
}

$ip_address = get_ip_address();

print "<br><br><center>| IP address: $ip_address |</center><br><br>";

//echo ("<div class=\"w3-center w3-btn w3-text-blue w3-border-0 w3-border-pale-purple w3-round-large\">IP-ADDRESS: $ip_address</div>");

?>

</body>
</html>
