<?php
namespace app\wx\model;
use think\Model;
use think\Db;
class City extends Model{
  public function getCityCodeByName($cityName){
    $res = Db:: name('ins_county')->where('county_name',$cityName)->value('weather_code');
    return $res;
  }
  public function getAllCityCode(){
    $res  = Db::name('ins_county')->value('weather_code');
    return $res;
  }
}
