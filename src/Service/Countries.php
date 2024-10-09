<?php 
namespace SGC\Service;

class Countries {
    
    static function retrieveList(){

        $items = [];

        $resp = wp_remote_get('https://restcountries.com/v3.1/all?fields=name,cca2');

        $resp_code = wp_remote_retrieve_response_code($resp);
        $resp_body = wp_remote_retrieve_body($resp);
        $resp_items = $resp_body ? json_decode($resp_body): [];

        if($resp_code == 200 && $resp_items){
            $names = [];
            foreach($resp_items as $i => $item){
                if($item?->name?->common){
                    $names[$i] = $item->name->common;
                }
            }
            asort($names);
            foreach(array_keys($names) as $i){
                $item = $resp_items[$i];
                $items[] = [
                    'name' => $item?->name?->common,
                    'code' => $item?->cca2
                ];
            }
        }

        return $items;
    }
}