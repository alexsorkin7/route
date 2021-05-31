<?php
namespace Also;

function mount($path,$variables = []) {
    global $env;
    $token = $_SESSION['token'];
    $csrf = '<input type="hidden" name="token" value="'.$token.'">';
    $mount = new Mount($env->mountPath);
    if(isset($_SESSION['user_id'])) {
        $variables['user_id'] = $_SESSION['user_id'];
        $variables['username'] = $_SESSION['username'];
        $variables['is_admin'] = $_SESSION['is_admin'];
    } else $variables['user_id'] = false;
    $variables['csrf'] = $csrf;
    $variables['token'] = $token;
    $variables['appName'] = $env->appName;
    $variables['baseUrl'] = getBaseUrl();
    return $mount->mount($path,$variables);
}

function user() {
    if(isset($_SESSION['user_id'])) return $_SESSION['user_id'];
    else return '';
}

function getBaseUrl($array=false) {
    $protocol = "";
    $host = "";
    $port = "";
    $dir = "";  

    // Get protocol
    if(array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] != "") {
        if($_SERVER["HTTPS"] == "on") { $protocol = "https"; }
        else { $protocol = "http"; }
    } elseif(array_key_exists("REQUEST_SCHEME", $_SERVER) && $_SERVER["REQUEST_SCHEME"] != "") { $protocol = $_SERVER["REQUEST_SCHEME"]; }

    // Get host
    if(array_key_exists("HTTP_X_FORWARDED_HOST", $_SERVER) && $_SERVER["HTTP_X_FORWARDED_HOST"] != "") { $host = trim(end(explode(',', $_SERVER["HTTP_X_FORWARDED_HOST"]))); }
    elseif(array_key_exists("SERVER_NAME", $_SERVER) && $_SERVER["SERVER_NAME"] != "") { $host = $_SERVER["SERVER_NAME"]; }
    elseif(array_key_exists("HTTP_HOST", $_SERVER) && $_SERVER["HTTP_HOST"] != "") { $host = $_SERVER["HTTP_HOST"]; }
    elseif(array_key_exists("SERVER_ADDR", $_SERVER) && $_SERVER["SERVER_ADDR"] != "") { $host = $_SERVER["SERVER_ADDR"]; }
    //elseif(array_key_exists("SSL_TLS_SNI", $_SERVER) && $_SERVER["SSL_TLS_SNI"] != "") { $host = $_SERVER["SSL_TLS_SNI"]; }

    // Get port
    if(array_key_exists("SERVER_PORT", $_SERVER) && $_SERVER["SERVER_PORT"] != "") { $port = $_SERVER["SERVER_PORT"]; }
    elseif(stripos($host, ":") !== false) { $port = substr($host, (stripos($host, ":")+1)); }
    // Remove port from host
    $host = preg_replace("/:\d+$/", "", $host);

    // Get dir
    if(array_key_exists("SCRIPT_NAME", $_SERVER) && $_SERVER["SCRIPT_NAME"] != "") { $dir = $_SERVER["SCRIPT_NAME"]; }
    elseif(array_key_exists("PHP_SELF", $_SERVER) && $_SERVER["PHP_SELF"] != "") { $dir = $_SERVER["PHP_SELF"]; }
    elseif(array_key_exists("REQUEST_URI", $_SERVER) && $_SERVER["REQUEST_URI"] != "") { $dir = $_SERVER["REQUEST_URI"]; }
    // Shorten to main dir
    if(stripos($dir, "/") !== false) { $dir = substr($dir, 0, (strripos($dir, "/")+1)); }

    // Create return value
    if(!$array) {
        if($port == "80" || $port == "443" || $port == "") { $port = ""; }
        else { $port = ":".$port; } 
        return htmlspecialchars($protocol."://".$host.$port, ENT_QUOTES); 
        // return htmlspecialchars($protocol."://".$host.$port.$dir, ENT_QUOTES); 
    } else { return ["protocol" => $protocol, "host" => $host, "port" => $port, "dir" => $dir]; }
}

function isAjax() {
    if(isset($_SERVER['HTTP_AJAX'])) return true;
    else return false;
}

?>