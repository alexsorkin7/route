<?php
namespace Also;

class Route {
    static public $routes = ['GET'=>[],'POST'=>[]];
    static public $publics = [];
    public $req = ['data' => [], 'server' => []];
    // public $req = new stdClass();

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
        $action = $this->urlMatch($url,$method);
        $this->getData($method);
        $this->newCsrf();
        $req = $this->request();
        if($action !== null) {
            if(gettype($action) == 'object') {
                header("HTTP/1.1 200 OK");
                echo $action($req); // if function
            } else if(gettype($action) == 'string') $this->controller($action,$req); // if controller
        } else if($method == 'GET'){
            $file = $this->getPublic($url,self::$publics);
            if($file !== null) echo $file;
            else {
                header("HTTP/1.0 404 Not Found");
                echo '404 | wrong url';
            }
        }
    }

    private function getData($method) {
        if($method == 'GET') $data = $_GET;
        else if($method == 'POST') $data = $_POST;
        if(isset($data) && isset($data['token'])) {
            $token = $this->csrf($data['token']);
            if($token) {
                unset($data['token']);
                $this->req['data'] = $data;
            }
        }
        unset($_GET);
    }

    private function csrf($csrf) {
        session_start();
        $oldToken = $_SESSION['token'];
        if($csrf == $oldToken) return true;
        else return false;
    }

    private function newCsrf() {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        $csrf = '<input type="hidden" name="token" value="'.$_SESSION['token'].'">';
        $this->req['token'] = $_SESSION['token'];
        $this->req['csrf'] = $csrf;
    }

    private function request() {
        $port = $_SERVER['SERVER_PORT'];
        $host = $_SERVER['HTTP_HOST'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $this->req['server']['port'] = $port;
        $this->req['server']['agent'] = $agent;
        $this->req['server']['host'] = $host;
        // $req['cookies'] = $_COOKIE;
        return $this->req;
    }

    private function controller($action,$req) {
        $action = explode('.',$action);
        if(count($action) == 2) {
            $controller = $action[0];
            $method = $action[1];
            include_once ROOT."controllers/${$controller}.php";
            echo $method($req);
        }
    }

    private function urlMatch($url,$method) {
        $urlArray = explode('/',$url);
        $routes = self::$routes[$method];
        foreach ($routes as $storedUrl => $route) {
            if($storedUrl == $url) return $route;
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
                            $this->req['data'][$varName] = $value;
                        }
                    }
                    return $route;
                }
            }
        }
        return null;
    }

    private function getPublic($url,$routes) {
        if(isset($routes[$url])) {
            $array = explode('/',$url);
            $ext = $array[count($array)-1];
            $this->getHead($ext);
            return file_get_contents(ROOT.$routes[$url]);
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