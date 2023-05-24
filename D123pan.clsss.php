<?php
class d123pan{
    private $UserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
    protected $cachepath='cache/';//缓存目录
    public $cache_time= 60;//缓存时间 0 为不缓存
    public function getUrl($url,$pwd=''){
        $return =array('status'=>0,'info'=>'');
        if(empty($url)){$return['info']= '请输入URL';return $return;}
        if($this->str_exists($url,'http')){
            $urlarr = explode('/',str_replace('.html','',$url));
            $shareKey = $urlarr[count($urlarr)-1];
        }else{
            $shareKey=$url;
        }
        if($this->cache_time>0){
            $cachekey = $shareKey.$pwd;
            $cacheresult = $this->cache($cachekey);
            if($cacheresult && $cacheresult['expires_time']>time())return $cacheresult['data'];
        }
        if(empty($pwd)){
            $url = 'https://www.123pan.com/s/'.$shareKey.'.html';
            $softInfo = $this->curlget($url);
            preg_match("~window.g_initialProps(.*?)};~", $softInfo, $segment);
            $jsonstr =  trim(trim($segment[1]),'=')."}";
            $jsonarr = json_decode($jsonstr,1);
            if(empty($jsonarr)){$return['info']= '解析错误';return $return;}
            if($jsonarr['res']['data']['HasPwd']=='false'){$return['info']= '请输入提取码';return $return;}            
            $softInfo = $jsonarr['reslist'];
        }else{
            $url = 'https://www.123pan.com/b/api/share/get?limit=100&next=1&orderBy=share_id&orderDirection=desc&shareKey='.$shareKey.'&SharePwd='.$pwd.'&ParentFileId=0&Page=1';
            $softInfo = json_decode($this->curlget($url),true);
            if($softInfo['code']>0){
                $return['info']= $softInfo['message'];return $return;
            }
        }
        $url = 'https://www.123pan.com/b/api/share/download/info';
        $info = $softInfo['data']['InfoList'][0];
        $param=array(
            'Etag'=> $info['Etag'],
            'FileID'=>  $info['FileId'],
            'S3keyFlag'=> $info['S3KeyFlag'],
            'ShareKey'=> $shareKey,
            'Size'=> $info['Size'],
        );
        $softInfo = json_decode($this->curlget($url,$param,'POST'),true);
        if($softInfo['code']>0){
            $return['info']= $softInfo['message'];return $return;
        }
        $downUrl = $softInfo['data']['DownloadURL'];
        if(empty($downUrl)){$return['info']= '获取下载地址失败';return $return;}
        $return['status']=1;
        $return['info']=$downUrl;
        if($this->cache_time>0){
            $cacheresult=array();
            $cacheresult['data']=$return;
            $cacheresult['expires_time']=time()+$this->cache_time;
            $this->cache($cachekey,$cacheresult);
        }
        return $return;
    }
    public function cache($key,$value='',$time=''){
        if(is_array($key))$key=md5(implode('',$key));
        $filename=$this->cachepath.$key.'.cache';
        if(empty($value)){
            $data= @file_get_contents($filename);$this->clearcache();
            return json_decode($data,1);
        }else{
            if(!is_array($value))$value=array($value);
            file_put_contents($filename,json_encode($value));
        }
    }
    //清空所有缓存
    public function clearcache(){
       $cachepath=$this->cachepath;
       $date=date('Y-m-d');$cachename='cachetime'.$date.'.c';
       if(file_exists($cachepath.$cachename))return false;
       foreach(scandir($cachepath) as $fn) {
    	if(strpos($cachename,'.c')>0)unlink($cachepath.$fn);
       }file_put_contents($cachepath.$cachename,'1');
       return true;
    }
    /**
     * CURL发送HTTP请求
     * @param  string $url    请求URL
     * @param  array  $params 请求参数
     * @param  string $method 请求方法GET/POST
     * @param  $header 头信息
     * @param  $multi  是否支付附件
     * @param  $debug  是否输出错误
     * @param  $optsother 附件项
     * @return array  $data   响应数据
     */
    private function curlget($url, $params='', $method = 'GET', $header = array(), $UserAgent = false,$debug=false,$optsother='') {
        if(empty($UserAgent))$UserAgent=$this->UserAgent;
    	$opts = array(CURLOPT_TIMEOUT => 10,CURLOPT_RETURNTRANSFER=> 1,CURLOPT_SSL_VERIFYPEER=> false,CURLOPT_SSL_VERIFYHOST=> false,CURLOPT_HTTPHEADER => $header,CURLOPT_USERAGENT=>$UserAgent);		
    	switch (strtoupper($method)) {/* 根据请求类型设置特定参数 */
    		case 'GET':$opts[CURLOPT_URL] = $params?$url.'?'.http_build_query($params):$url;break;
    		case 'POST':$params = http_build_query($params);//判断是否传输文件
        	$opts[CURLOPT_URL] = $url;$opts[CURLOPT_POST] = 1;$opts[CURLOPT_POSTFIELDS] = $params;break;			
    		default:if($debug)echo ('不支持的请求方式！');break;
    	}$ch = curl_init();if($optsother && is_array($optsother))$opts=$opts+$optsother;curl_setopt_array($ch, $opts);$data = curl_exec($ch);$error = curl_error($ch);curl_close($ch);/* 初始化并执行curl请求 */
    	if($error && $debug){echo ('请求发生错误:'.$error);}
    	return $data;
    }//检测字符串中是否存在
    private function str_exists($haystack, $needle){
    	return !(strpos(''.$haystack, ''.$needle) === FALSE);
    }
}
?>