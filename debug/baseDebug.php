<?php
$basepath = dirname ( __FILE__ ) . "/../";
require_once $basepath . '/protected/components/Curl.php';
require_once $basepath . '/protected/components/CryptAES.php';

define ( "TOKEN", "dLneoDa897Dn2Ac" );
defined ( 'DEBUG_URL_PATH' ) or define ( 'DEBUG_URL_PATH', '127.0.0.1' );
class baseDebug {
	protected $baseurl = "http://127.0.0.1/";
	protected $actionurl = '';
	protected $curl = NULL;
	public function __construct() {
		if (defined ( DEBUG_URL_PATH )) {
			$this->baseurl = DEBUG_URL_PATH;
		}
		echo $this->baseurl . "<br/>";
	}
	public function debugDo() {
		$url = $this->baseurl . $this->actionurl;
		// $url="https://www.googleapis.com/auth/plus.login";
		// $url="https://www.googleapis.com/plus/v1/people/me";
		
		$xmlstring = "<?xml version='1.0' encoding='UTF-8'?><group><name>张三</name><age>22</age></group>";
		
		$a = new WinXinPost ( "text", $url, 11111 );
		echo $a->result ();
	}
	public function vaild() {
		$url = $this->baseurl . $this->actionurl;
		$timestamp = time ();
		$token = TOKEN;
		$nonce = rand ( 1, 100000000 );
		$echostr = 'wanbinddddd';
		
		$pararr = $this->getFunctionParams ();
		$pararr = array_merge ( $pararr, $_GET );
		$tmpArr = array (
				TOKEN,
				$timestamp,
				$nonce 
		);
		sort ( $tmpArr, SORT_STRING );
		$tmpStr = implode ( $tmpArr );
		$signature = sha1 ( $tmpStr );
		$params = array (
				'timestamp' => $timestamp,
				'nonce' => $nonce,
				'echostr' => "wanbin" . rand ( 1, 9999999 ),
				'signature' => $signature 
		);
		
		$ret = $this->curl->get ( $url, $params );
		
		print_r ( $ret );
		echo "<br/>===========params==========<br/>";
		foreach ( $pararr as $key => $value ) {
			echo "$key=>$value<br/>";
		}
	}
	protected function getBaseParams() {
		return array ();
	}
	protected function getFunctionParams() {
		return $this->getBaseParams ();
	}
	protected function getSec($ret) {
		$temstr = urlencode ( json_encode ( $ret ) );
		$aes = new CryptAES ();
		$sha1_res = sha1 ( $temstr . "bf2eee982aa6e50c1d98823ba6fc134b" );
		$hex_res = self::hexSha1 ( $sha1_res );
		
		$temret = array (
				'data' => $temstr,
				'sign' => $hex_res 
		);
		$request = base64_encode ( $aes->encrypt ( json_encode ( $temret ) ) );
		return $request;
	}
	public function getGooglePlus() {
		return array (
				'access_token' => 'ya29.cAEhbsho80cANV1N_UXPiBU59K5j5t965Bk-GHiMRrotsM-VS2N5RaaQloG1gTij7xXRvdf0iBBL0w' 
		);
	}
	
	/**
	 * 对sha1结果进行加密，生成新的签名
	 * 
	 * @param
	 *        	sha1 string
	 * @return hex result
	 */
	private static $hexDigits = array (
			'0',
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9',
			'a',
			'b',
			'c',
			'd',
			'e',
			'f' 
	);
	private static $mixStr = '99ed0f252347ee7aa130736b0e95b0da87942f68';
	public static function hexSha1($sha1Res) {
		$res = '';
		
		for($i = 0; $i < strlen ( $sha1Res ); $i ++) {
			$res = $res . self::$hexDigits [hexdec ( $sha1Res [$i] ) ^ hexdec ( self::$mixStr [$i] )];
		}
		
		return $res;
	}
}
class WinXinPost {
	private $event = "";
	private $content = "";
	private $time;
	
	/*
	 * 使用严格遵守微信公众平台参数配置http://mp.weixin.qq.com/wiki/index.php?title=消息接口指南
	 * 如果是text或者image类型就直接输入$content 其他的就输入array 譬如地理位置输入
	 * <Location_X>23.134521</Location_X> <Location_Y>113.358803</Location_Y>
	 * <Scale>20</Scale> <Label><![CDATA[位置信息]]></Label>
	 * array('1.29290','12.0998','20','位置信息');
	 */
	public function __construct($event, $url, $content) {
		$this->event = $event;
		$this->url = $url;
		$this->content = $content;
		$this->time = time ();
	}
	
	// 返回接收的消息
	public function result() {
		$postObj = simplexml_load_string ( $this->post (), 'SimpleXMLElement', LIBXML_NOCDATA );
		$str='';
		foreach ( ( array ) $postObj as $key => $value ) {
			$str .= $key . '=>' . $value . "<br>";
		}
		return $str;
	}
	
	// 处理成xml数据
	private function xml_data() {
		$str = "
		<xml>
		<ToUserName>100012</ToUserName>
		<FromUserName>100012</FromUserName>
		<CreateTime>{$this->time}</CreateTime>
		<MsgType>{$this->event}</MsgType>
		{$this->judgment()}
		<MsgId>1234567890123456</MsgId>
		</xml>
		";
		return $str;
	}
	
	// 模拟post提交
	private function post() {
		$header [] = "Content-type: text/xml"; // 定义content-type为xml
		$ch = curl_init (); // 初始化curl
		curl_setopt ( $ch, CURLOPT_URL, $this->url ); // 设置链接
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // 设置是否返回信息
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header ); // 设置HTTP头
		curl_setopt ( $ch, CURLOPT_POST, 1 ); // 设置为POST方式
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->xml_data () ); // POST数据
		$response = curl_exec ( $ch ); // 接收返回信息
		if (curl_errno ( $ch )) { // 出错则显示错误信息
			print curl_error ( $ch );
		}
		curl_close ( $ch ); // 关闭curl链接
		return $response;
	}
	
	// 文本消息
	private function text() {
		return "<Content>{$this->content}</Content>";
	}
	
	// 图形消息
	private function image() {
		return "<PicUrl>{$this->content}</PicUrl>";
	}
	
	// 链接消息
	private function link() {
		$data = $this->content;
		$str = "
		<Title>{$data[0]}</Title>
		<Description>{$data[1]}</Description>
		<Url>{$data[2]}</Url>
		";
		return $str;
	}
	
	// 地理位置消息
	private function location() {
		$data = $this->content;
		$str = "
		<Location_X>{$data[0]}</Location_X>
		<Location_Y>{$data[1]}</Location_Y>
		<Scale>20</Scale>
		<Label>{$data[3]}</Label>";
		return $str;
	}
	
	// 根据消息类型加载相应的东西
	private function judgment() {
		$type = $this->event;
		return $this->$type ();
	}
}

