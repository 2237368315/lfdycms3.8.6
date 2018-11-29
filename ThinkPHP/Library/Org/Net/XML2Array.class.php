<?php
namespace Org\Net;
 
class XML2Array {

    public function xml_to_array($xmlstr,$analysis_type=''){
        if($analysis_type){
            return $this->$analysis_type($xmlstr);
        }
        $return_array=$this->simple_analysis($xmlstr);
        if($return_array==false){
            $return_array=$this->regular_analysis($xmlstr);
        }
        return $return_array;
    }

    protected function simple_analysis($xmlstr){
        $i=0;
        $xmlstr=str_replace('&','&amp;',$xmlstr);
        $xml=simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($xml === false){
            return false;
        }
        if(strpos($xmlstr,'<class>') !== false){
            foreach($xml->class->ty as $v){
                $type=$xml->addChild("type");
                $type->addChild("id",$v->attributes());
                $type->addChild("title",$v);
            }
        }else{
            foreach ($xml->list->video as $value) {
                foreach ($value->dl->dd as $v) {
                    $xml->list->video[$i]->dl->addChild('name',$v->attributes());
                }
                $i++;
            }
        }
        $data=json_decode(json_encode($xml), true);
        if(!$data["list"]["video"][0]){
            $data["list"]["video"]=array($data["list"]["video"]);
        }
        return $data;
    }

    protected function regular_analysis($xmlstr){
        $xml=array();
        preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$xmlstr,$page_array);
        if($page_array){
            $xml['list']['@attributes']['page'] = $page_array[1];
            $xml['list']['@attributes']['pagecount'] = $page_array[2];
            $xml['list']['@attributes']['pagesize'] = $page_array[3];
            $xml['list']['@attributes']['recordcount'] = $page_array[4];
            if(strpos($xmlstr,'<class>') !== false){
                preg_match_all('/<ty id="([0-9]+)">([\s\S]*?)<\/ty>/',$xmlstr,$list_array);
                foreach($list_array[1] as $key=>$value){
                    $xml['type'][$key]['id'] = $value;
                    $xml['type'][$key]['title'] = $list_array[2][$key];
                }
                preg_match_all('/<video><last>([\s\S]*?)<\/last><id>([0-9]+)<\/id><tid>([0-9]+)<\/tid><name><\!\[CDATA\[([\s\S]*?)\]\]><\/name><type>([\s\S]*?)<\/type><dt>([\s\S]*?)<\/dt><note><\!\[CDATA\[([\s\S]*?)\]\]><\/note>([\s\S]*?)<\/video>/',$xmlstr,$vod_array);
            }else{
                preg_match_all('/<video><last>([\s\S]*?)<\/last><id>([0-9]+)<\/id><tid>([0-9]+)<\/tid><name><\!\[CDATA\[([\s\S]*?)\]\]><\/name><type>([\s\S]*?)<\/type><pic>([\s\S]*?)<\/pic><lang>([\s\S]*?)<\/lang><area>([\s\S]*?)<\/area><year>([\s\S]*?)<\/year><state>([\s\S]*?)<\/state><note><\!\[CDATA\[([\s\S]*?)\]\]><\/note><actor><\!\[CDATA\[([\s\S]*?)\]\]><\/actor><director><\!\[CDATA\[([\s\S]*?)\]\]><\/director><dl>([\s\S]*?)<\/dl><des><\!\[CDATA\[([\s\S]*?)\]\]><\/des>([\s\S]*?)<\/video>/',$xmlstr,$vod_array);
            }
            foreach($vod_array[1] as $key=>$value){
                $xml['list']['video'][$key]['last'] = $vod_array[1][$key];
                $xml['list']['video'][$key]['id'] = $vod_array[2][$key];
                $xml['list']['video'][$key]['tid'] = $vod_array[3][$key];
                $xml['list']['video'][$key]['name'] = $vod_array[4][$key];
                $xml['list']['video'][$key]['type'] = $vod_array[5][$key];
                if(strpos($xmlstr,'<class>') !== false){
                    $xml['list']['video'][$key]['dt'] = $vod_array[6][$key];
                    $xml['list']['video'][$key]['note'] = $vod_array[7][$key];
                }else{
                    $xml['list']['video'][$key]['pic'] = $vod_array[6][$key];
                    $xml['list']['video'][$key]['lang'] = $vod_array[7][$key];
                    $xml['list']['video'][$key]['area'] = $vod_array[8][$key];
                    $xml['list']['video'][$key]['year'] = $vod_array[9][$key];
                    $xml['list']['video'][$key]['state'] = $vod_array[10][$key];
                    $xml['list']['video'][$key]['note'] = $vod_array[11][$key];
                    $xml['list']['video'][$key]['actor'] = $vod_array[12][$key];
                    $xml['list']['video'][$key]['director'] = $vod_array[13][$key];
                    preg_match_all('/<dd flag="([\s\S]*?)"><\!\[CDATA\[([\s\S]*?)\]\]><\/dd>/',$vod_array[14][$key],$url_arr);
                    $xml['list']['video'][$key]['dl']['dd']=$url_arr[2];
                    $xml['list']['video'][$key]['dl']['name']=$url_arr[1];
                    $xml['list']['video'][$key]['des'] = $vod_array[15][$key];
                }
            }
            return $xml;
        }else{
            return false;
        }
    }
}