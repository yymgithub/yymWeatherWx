<?php
namespace app\wx\model;
use think\Model;
use think\Db;
class Weather extends Model{
  public function getWeahter($cityCode){
    $res = Db::name('weather_info')->where('city_code',$cityCode)->select();
    return $res;
  }
}