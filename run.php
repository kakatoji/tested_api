<?php
#error_reporting(0);
class Analis{
 
 public function __construct()
 {
     $this->keys='6b2a8083afb6b77a7a59e81bf3948aa402321bba';
     $this->url="https://shrtfly.com/api?api";
     $this->base="https://kakatoji-28069.firebaseio.com/kakatoji.json";
     $this->baseKey="http://kakatoji.my.id/remember";
 }
 private function socket($url,$data='',$mode='',$httpheader='') 
{
  while(True){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    if($data){
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);}
    if($mode){
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);}
    if($httpheader){
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);}
    $result = curl_exec($ch);
    curl_close ($ch);
    if ($result == null){
      continue;
    }else{
      return $result;
      break;
    }
  }
 }
 public function acak($panjang){
   $ab="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
   $str= "";
   for ($i=0; $i < $panjang ; $i++){
     $pos = rand(0,strlen($ab)-1);
     $str .= $ab[$pos];
   }
  return $str;
 }
 public function short()
 {   
     $uri=$this->url;
     $key=$this->keys;
     $alis=$this->acak(9);
     $exc=$uri.'='.$key."&url=".$this->baseKey."&alias=".$alis."&format=json";
     return json_decode($this->socket($exc,'',"GET",array("Accept: application/json")),1);
 }
 public function baseUrl()
 {
     date_default_timezone_set('UTC');
     $id=uniqid();
     $day=date("D");
     $data=json_encode(["user-".rand(1,100)=>["id"=>$id,"day"=>$day,"signature"=>md5(time())]]);
     return json_decode($this->socket($this->base,$data,"PUT",array("Accept: application/json")),1);
     
 }
 public function ceking()
 {
     return json_decode(file_get_contents($this->base),1);
 }
 
}
class short extends Analis{
    public function cons()
    {
        $day=date('D');
        foreach($this->ceking() as $uri => $zrl){
           if(!file_exists('key')){
               $ky=$this->baseUrl();
               foreach ($ky as $ct => $ft){
                   echo "~>\033[1;32mvisit_key: \033[1;36m".$this->short()['shortenedUrl']."\033[0m".PHP_EOL;
                   $vv=readline("[=>]input key: ");
                   file_put_contents('key',$vv);
               }
           }elseif(file_exists('key')){
               $fl=file('key');
               if(strlen($fl[0]) !== strlen($zrl['signature'])){
                   echo "\033[1;31mDon't change key\n";
                   unlink("key");
                   continue;
               }elseif($day !== $zrl['day']){
                   echo "\033[1;33mSession key limit\n";
                   unlink('key');
                   continue;
               }
          }
        }
    }
    public function sev($f,$d)
    {
        if(!file_exists($f)){
            file_put_contents($f,$d);
        }
    }
}
$m=new short();
$m->cons();
