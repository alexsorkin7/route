<?php
namespace Also;

class Route {
    static public $routes = ['GET'=>[],'POST'=>[]];
    static public $publics = [];
    static public $p404 = '404 | wrong url';
    public $data = [];
    public $csrf = true;
    public $test;

    public function __construct() { // ->getRoute(url,routes,method)
        if(isset($_SERVER['PATH_INFO'])) $url = $_SERVER['PATH_INFO'];
        else $url = $_SERVER['REQUEST_URI'];
        if($url !== '/' && substr($url,-1) == '/') $url = substr($url,0,-1);
        $method = $_SERVER['REQUEST_METHOD'];
        $this->getRoute($url,self::$routes[$method],$method);
    }

    public static function get($route,$fn) {self::$routes['GET'][$route] = $fn;}
    public static function post($route,$fn) {self::$routes['POST'][$route] = $fn;}
    public static function public($route,$file) {self::$publics[$route]= $file;}

// from: __construct
// to: urlMatch,getData,newCsrf,request,controller,getPublic
    private function getRoute($url,$routes,$method) { 
        include_once ROOT.'mw/after.php';
        $action = $this->urlMatch($url,$method);
        session_start();
        $this->getData($method);
        $this->newCsrf();
        if($action !== null) {
            if(gettype($action) == 'object') {
                header("HTTP/1.1 200 OK");
                echo $action($this->data); // if function
            } else if(gettype($action) == 'string') $this->controller($action); // if controller
        } else if($method == 'GET'){
            $file = $this->getPublic($url,self::$publics);
            if($file !== null) echo $file;
            else {
                header("HTTP/1.0 404 Not Found;");
                echo self::$p404;
            }
        }
    }

    private function getData($method) {
        if($method == 'GET') $data = $_GET;
        else if($method == 'POST') $data = $_POST;
        if(!$this->csrf) {
            $this->data = array_merge($data,$this->data);
        } else if(isset($data) && isset($data['token'])) {
            if($_SESSION['token'] == $data['token']) {
                unset($data['token']);
                $this->data = array_merge($data,$this->data);
            }
        }
        // unset($_GET);
        // unset($_POST);
    }


    private function newCsrf() {
        global $env;
        $token = bin2hex($env->secret);
        $_SESSION['token'] = $token;
        $this->req['token'] = $token;
    }

    private function controller($action) {
        $action = explode('.',$action);
        if(count($action) == 2) {
            $controller = $action[0];
            $method = 'Also\\'.$action[1];
            $ifAuth = false;
            include_once ROOT."controllers/$controller.php";
            if($ifAuth) {
                if(!isset($_SESSION['user_id'])) echo redirect('/login');
                else echo $method($this->data);
            } else echo $method($this->data);
        }
    }

    private function urlMatch($url,$method) {
        $urlArray = explode('/',$url);
        $routes = self::$routes[$method];
        foreach ($routes as $storedUrl => $route) {
            $csrf = false;
            if(substr( $storedUrl, 0, 1) === "!") {
                $l = strlen($storedUrl);
                $storedUrl = substr($storedUrl,$l - ($l-1) );
                $csrf = true;
            }
            if($storedUrl == $url) {
                if($csrf) $this->csrf = false;
                return $route;
            }
            else if(strpos($storedUrl,'{') !== false && count($urlArray) == count(explode('/',$storedUrl))) {
                $urlPattern = preg_replace('/\{\\w*\}/','\\w*',$storedUrl);
                $urlPattern = str_replace('/','\\/',$urlPattern);
                preg_match('/'.$urlPattern.'/',$url,$matches);
                if(count($matches)) { // Grab the variables
                    $storedUrlArray = explode('/',$storedUrl);
                    foreach ($urlArray as $key => $value) {
                        if(strpos($storedUrlArray[$key],'{') !== false) {
                            $varName = $storedUrlArray[$key];
                            $varName = str_replace('{','',$varName);
                            $varName = str_replace('}','',$varName);
                            $this->data[$varName] = $value;
                        }
                    }
                    if($csrf) $this->csrf = false;
                    return $route;
                }
            }
        }
        return null;
    }

    private function getPublic($url,$routes) {
        if(isset($routes[$url]) || isset($routes['('.$url.')'])) {
            $array = explode('.',$url);
            $ext = $array[count($array)-1];
            $this->getHead($ext);
            if(isset($routes['('.$url.')'])) {
                return '(function(){'.file_get_contents(ROOT.$routes['('.$url.')']).'})();';
            } else return file_get_contents(ROOT.$routes[$url]);
        } else return null;
    }

    private function getHead($ext) {
        $heads = [
            'html'=>'text/html; charset=UTF-8',
            'js'=>'application/javascript',
            'ogg'=>'application/ogg',
            'pdf'=>'application/pdf',
            'json'=>'application/json',
            'zip'=>'application/zip',
            'gif'=>'image/gif',
            'jpeg'=>'image/jpeg',
            'png'=>'image/png',
            'tiff'=>'image/tiff',
            'djvu'=>'image/vnd.djvu',
            'svg'=>'image/svg+xml',
            'css'=>'text/css',
            'csv'=>'text/csv',
            'html'=>'text/html',
            'plain'=>'text/plain',
            'xml'=>'text/xml',
            'mpeg'=>'video/mpeg',
            'mp4'=>'video/mp4',
            'webm'=>'video/webm',
        ];
       if(isset($heads[$ext])) {
           header("Content-Type:$heads[$ext];");
       }
    }
}
?>