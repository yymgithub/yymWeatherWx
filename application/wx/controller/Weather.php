<?php 
namespace app\wx\controller;
use think\Controller;
//use app\wx\controller\City;
use think\Db;
class Weather extends Controller{
  public function getWeatherByCityCode(){
    $cityCode = input('cityCode');
    $model = model('Weather');
    $data = $model -> getWeahter($cityCode);
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

