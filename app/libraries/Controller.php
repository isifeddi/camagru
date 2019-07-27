<?php

class Controller {
   // Load Model
   public function model($model){
       // require Model
       require_once '../app/models/' . $model . '.php';
       // instanciate model
       return new $model();
   }
   // load view
   public function view($view, $data = []){
       // check for view file
       if(file_exists('../app/views/' . $view . '.php')){
           require_once '../app/views/' . $view . '.php';
       } else {
           die('View dos not exist');
       }
   }
}