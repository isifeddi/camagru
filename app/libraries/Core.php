<?php 

    class Core{

        protected $currentController = 'Posts' ;
        protected $currentMethod = 'index';
        protected $params = [];


        public function __construct(){
            $url = $this->getUrl();
            $c = count($url);

            if(file_exists('../app/controllers/' . ucwords($url[0]) .'.php')){
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
            }
            
            require_once '../app/controllers/' . $this->currentController . '.php';
         
            $this->currentController = new  $this->currentController;
            
            if(isset($url[1])){
                if(method_exists($this->currentController, $url[1])){
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }
            }

            if(isset($url[2])){
                redirect('posts/home');
                unset($url[2]);
            }

            if ($c >= 1)
            {
                if(preg_match('{/$}',$_SERVER['REQUEST_URI'])) {
                   header ('Location: '.preg_replace('{/$}', '', $_SERVER['REQUEST_URI']));
                   exit();
                   }
            }

            $this->params = $url ? array_values($url) : [];

            call_user_func_array([$this->currentController, $this->currentMethod],$this->params);
        }

        public function getUrl(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/',$url);
                return $url;
            }
        }
    }