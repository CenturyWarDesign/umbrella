<?php
/**
* Curl wrapper for Yii
* @author hackerone
*/
class Curl extends CComponent{

    private $_ch;

    // config from config.php
    public $options;

    public $is_testing = false;

    // default config
    private $_config = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_AUTOREFERER    => true,         
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_VERBOSE => true,
        CURLOPT_USERAGENT => 'Sevenga',
        );


    private function _exec($url){

        $this->setOption(CURLOPT_URL, $url);
        $c = curl_exec($this->_ch);
        if(!curl_errno($this->_ch))
            return $c;
        else
            if($this->is_testing)
                throw new CException(curl_error($this->_ch));

        return false;

    }

    public function setTestingMode($testing)
    {
        $this->is_testing = $testing;
    }

    public function get($url, $params = array()){
        $this->setOption(CURLOPT_HTTPGET, true);
        //$this->setOption(CURLOPT_VERBOSE, true);
        return $this->_exec($this->buildUrl($url, $params));
    }

    public function post($url, $data = array(), $type='form')  
    {  
        if ('form' == $type)  
        {  
            return $this->postForm($url,$data);  
        } elseif ('json' == $type)  
        {  
            return $this->postJson($url,json_encode($data));  
        } elseif ('file' == $type)  
        {  
            return $this->postFile($url, $data);  
        }  
        else
        {
            return $this->postData($url, json_encode($data));
        }
    }  

     /* 
     * This function will send a requset by curl 
     * Request method post 
     * $url request url 
     */  
    private function postJson($url,$data)  
    {  
        $this->setOption(CURLOPT_POST, true);  
        $this->setOption(CURLOPT_POSTFIELDS, $data);  
        $this->setHeaders(array('Content-Type: application/json'));  
        return $this->_exec($url);
    }  
  
    /* 
     * This function will send a requset by curl 
     * Request method post 
     * $url request url 
     */  
    private function postFile($url,$data)  
    {  
        $this->setOption(CURLOPT_POST, true);  
        $this->setOption(CURLOPT_POSTFIELDS, $data);  
        $this->setHeaders(array('enctype: multipart/form-data'));  
        return $this->_exec($url);
    }  
  
    private function postForm($url, $data)  
    {  
        $paramArr = array();  
        foreach($data as $key=>$value)  
        {  
            array_push($paramArr, $key . '=' . $value);  
        }  
        $post_data = implode('&',$paramArr);  
        $this->setOption(CURLOPT_POST, 1);  
        $this->setOption(CURLOPT_POSTFIELDS, $post_data);  
        $this->setHeaders(array('Content-Type: application/x-www-form-urlencoded'));  
        return $this->_exec($url);
    }  

    public function postData($url, $data = ''){
        $this->setHeaders(array('Content-Type: application/x-www-form-urlencoded'));  
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $data);

        return $this->_exec($url);
    }

    public function put($url, $data, $params = array()){        

        // write to memory/temp
        $f = fopen('php://temp', 'rw+');
        fwrite($f, $data);
        rewind($f);

        $this->setOption(CURLOPT_PUT, true);
        $this->setOption(CURLOPT_INFILE, $f);
        $this->setOption(CURLOPT_INFILESIZE, strlen($data));
        
        return $this->_exec($this->buildUrl($url, $params));
    }

    public function delete($url, $params = array()) {

        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->_exec($this->buildUrl($url, $params));
    }

    public function buildUrl($url, $data = array()){
        $parsed = parse_url($url);
       // print_r($parsed);
        isset($parsed['query'])?parse_str($parsed['query'],$parsed['query']):$parsed['query']=array();
        $params = isset($parsed['query'])?array_merge($parsed['query'], $data):$data;
        $parsed['query'] = ($params)?'?'.http_build_query($params):'';
        if(!isset($parsed['path']))
            $parsed['path']='/';
        if(isset($parsed['port']))
            return $parsed['scheme'].'://'.$parsed['host']. ':' .$parsed['port'].$parsed['path'].$parsed['query'];
        else
            return $parsed['scheme'].'://'.$parsed['host'].$parsed['path'].$parsed['query'];
    }
    
    public function setOptions($options = array()){
        curl_setopt_array( $this->_ch , $options);

        return $this;
    }

    public function setOption($option, $value){
        curl_setopt($this->_ch, $option, $value);

        return $this;
    }

    public function setHeaders($header = array())
    {
        if($this->_isAssoc($header)){
            $out = array();
            foreach($header as $k => $v){
                $out[] = $k .': '.$v;
            }
            $header = $out;
        }

        $this->setOption(CURLOPT_HTTPHEADER, $header);
        
        return $this;
    }


    private function _isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function getError()
    {
        return curl_error($this->_ch);
    }

    public function getInfo()
    {
        return curl_getinfo($this->_ch);
    }

    // initialize curl
    public function init(){
        try{
            $this->_ch = curl_init();
            $options = is_array($this->options)? ($this->options + $this->_config):$this->_config;
            $this->setOptions($options);

            $ch = $this->_ch;
            
            // close curl on exit
            Yii::app()->onEndRequest = function() use(&$ch){
                curl_close($ch);
            };
        }catch(Exception $e){
            throw new CException('Curl not installed');
        }
    }
}