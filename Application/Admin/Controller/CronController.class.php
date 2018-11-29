<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Net\XML2Array;

ignore_user_abort(true); // 忽略客户端断开 
set_time_limit(0);    // 设置执行不超时

class CronController extends Controller {

    public function _initialize(){
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =  config_lists();
            S('DB_CONFIG_DATA',$config);
        }
        C($config);
    }

    public function Collect($keys,$p=1){
        if(!S('cron_token')){
            return false;
        }
        $listUrl=C('COLLECT_URL');
        $Collect = new \Org\Net\Http;
        $list=$Collect->doGet($listUrl);
        $sever = F('Collect_sever');
        if(!$list){
            $clist=$sever;
        }else{
            if($sever){
                $clist=array_merge($sever,json_decode($list,true));
            }else{
                $clist=json_decode($list,true);
            }
        }
        $arrfid = array_column($clist, 'fid');
        $key = array_search($keys,$arrfid);
        if(!isset($key)){
            $crons = F('CRON_CONFIG');
            unset($crons[$keys]);
            F('CRON_CONFIG',$crons);
            return false;
        }
        $_GET['fid']=$clist[$key]['fid'];
        $_GET['ac']='list';
        $_GET['h']='24';
        $_GET['type']=$clist[$key]['type'];
        $_GET['p']=$p;
        $patterns =array("@","|","~","{ac}","{p}","{id}","{h}","{t}", "{wd}","list");
        $replacements = array("=","/","&",I("get.ac"),I("get.p"),I("get.id"),I("get.h"),I("get.t"),urlencode(I("get.wd","")),"videolist");
        $url=str_replace($patterns, $replacements, $clist[$key]['url']);
        $list=$Collect->doGet($url);
        if($list){
            switch ($_GET['type']){
                case "xml":
                    $list_array=$this->xml_to_array($list,1);
                    break;
                case "json":
                    $list_array=$this->json2array($list,1);
                    break;
                default:
            }
            if($p<$list_array["list"]["pagecount"]){
                $p++;
                $this->Collect($keys,$p);
            }else{
                S('cron_token',null);
            }
        }
    }

    public function xml_to_array($string,$collect=NULL){
        $i=0;
        $date=array();
        $fid=I('get.fid');
        $string=preg_replace("/<script[\s\S]*?<\/script>/i","",$string);
        $list_array=$this->analysis_xml($string);
        $date["list"]["pagecount"]=$list_array["list"]["@attributes"]["pagecount"];
        $date["list"]["pagesize"]=$list_array["list"]["@attributes"]["pagesize"];
        $date["list"]["recordcount"]=$list_array["list"]["@attributes"]["recordcount"];
        foreach($list_array["type"] as $key=>$value){
            $date["class"][$key]["id"]=$value["id"];
            $date["class"][$key]["title"]=$value["title"];
        }
        foreach($list_array["list"]["video"] as $key=>$value){
            $title=$this->add_also_known_as($value["name"]);
            $date["video"][$key]["mid"]=$value["id"];
            $date["video"][$key]["tid"]=$value["tid"];
            $date["video"][$key]["type"]=$value["type"];
            $date["video"][$key]["title"]=$title["title"];
            $date["video"][$key]["last"]=strtotime($value["last"]);
            $date["video"][$key]["serialize"]=$value["note"];
            if($collect){
                $date["video"][$key]["category"]=bind_id($fid."_".$value["tid"]);
                $date["video"][$key]["actors"]=$value["actor"];
                $date["video"][$key]["area"]=$value["area"];
                $date["video"][$key]["language"]=$value["lang"];
                $date["video"][$key]["year"]=$value["year"];
                $date["video"][$key]["pic"]=str_replace(array('pic.php?pic=','/img.php?pic='),'',$value["pic"]);
                $date["video"][$key]["content"]=preg_replace("/<script[\s\S]*?<\/script>/i","",$value["des"]);
                $date["video"][$key]["also_known_as"]=$title["aka"];
                $date["video"][$key]["directors"]=$value["director"];
                $date["video"][$key]["reurl"]=$value["reurl"]?$value["reurl"]:$fid."/".$value["id"];
                $date["video"][$key]["play"]=$this->url_replace($value["dl"]);
                $date["return"]["video"][]=D('Admin/Collect')->insert($date["video"][$key]);
                $i++;
                $date["return"]["num"]["i"]=$i;
            }
        }
        return $date;
    }
    
    public function json2array($string){
        $fid=I('fid');
        $i=0;
        $list_array=json_decode($string, true);
        foreach($list_array["video"] as $key=>$value){
            $list_array["video"][$key]["category"]=bind_id($fid."_".$value["tid"]);
            $list_array["return"]["video"][]=D('Admin/Collect')->insert($list_array["video"][$key]);
            $i++;
            $list_array["return"]["num"]["i"]=$i;
        }
        return $list_array;
    }

    private function analysis_xml($string){
        $xml_parser = new XML2Array();
        $data = $xml_parser->xml_to_array($string);
        return $data;
    }

    private function add_also_known_as($title){
        $title=explode('/',$title);
        return array('title' =>$title[0], 'aka'=>implode(',',array_slice($title,1)));
    }
    
    private function url_replace($playurl){
        if (strpos($playurl['dd'], '$$$') !== false && !is_array($playurl['dd'])) {
            $playerurl = $this->player_to_url($playurl['dd']);
        }else{
            $playerurl = $this->player_to_url_flv($playurl);
        }
        return $playerurl;
    }

    private function player_to_url($url){
        $playx=explode('#',$url);
        $playname=explode('$',$playx[0]);
        $playurl=$playname[2]."$$".$url;
        $playerurl=array();
        $array_url = array();
        $array_play=explode('$$$',$playurl);
        foreach($array_play as $k=>$v){
            $arr_url = explode('$$',$v);
            $arr_ji = explode('#',$arr_url[1]);
            foreach($arr_ji as $key=>$value){
                $urlji = explode('$',$value);
                $array_url[$key] = $urlji[0].'$'.trim($urlji[1]);
            }
            $url=implode(chr(13),$array_url);
            $playerurl[$k]=array("name"=>$arr_url[0],"url"=>$url);
        }
        return $playerurl;
    }

    private function player_to_url_flv($url){
        if(is_array($url['dd'])){
            foreach ($url['dd'] as $key=>$value) {
                $array_url=array();
                $playx=explode('#',$value);
                foreach ($playx as $k => $v){
                    $playurl=explode('$',$v);
                    $array_url[] = $playurl[0].'$'.trim($playurl[1]);
                }
                $play_url[]=array("name"=>$url['name'][$key],"url"=>implode(chr(13),$array_url));
            }
        }else{
            $playx=explode('#',$url['dd']);
            foreach ($playx as $k => $v){
                $playurl=explode('$',$v);
                $array_url[] = $playurl[0].'$'.trim($playurl[1]);
            }
            $play_url[]=array("name"=>$url['name'],"url"=>implode(chr(13),$array_url));
        }
        return $play_url;
    }
}
