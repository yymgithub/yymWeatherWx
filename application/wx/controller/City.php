<?php
namespace app\wx\controller;
use think\Controller;
class City extends Controller{
  public function getCityCode(){
    $cityName = input('cityName');
    $model = model('City');
    $data = $model -> getCityCodeByName($cityName);
    if ($data){
      $code = 200;
    }else{
      $code =404;
    }
   $data = [
     'code' => $code,
     'data' => $data
     ];
    return json($data);
  }
}