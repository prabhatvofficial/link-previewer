<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class web extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('web_model');
        $this->load->helper("url");
    }

    public function index(){
	    $data['page'] ='home';
        $this->load->view('web/header', $data);
		$this->load->view('web/index', $data);
		$this->load->view('web/footer', $data);
    }
    private function remove_http($url) {
	   $disallowed = array('http://', 'https://', 'www.');
	   foreach($disallowed as $d) {
		  if(strpos($url, $d) === 0) {
			 $url=str_replace($d, '', $url);
		  }
	   }
	   return rtrim($url, '/');
	}
	private function addHttps( $url ) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
    public function query(){
		if($_GET['url']){
			$result = strtolower($this->remove_http($_GET['url']));
			$redirect =  base_url('response').'/'.$result;
			redirect($redirect);
		}else{
		    redirect(base_url());
		}
	}
    public function response($url = FALSE){
		if(!empty($url)){
		    $data['domain'] = $url;
		        if ( gethostbyname($url) != $url ) {
                    $domain1 = $this->addHttps(prep_url($url));
                    $data['page'] ='report';
                }
                else {
                    $data['page'] ='404';
                    $error = true;
                    $data['for'] = 'domainnotexist';
                }
		
		
		$info1 = $this->web_model->getSingleFromSearchedWebsite(array('domain'=>$url));
			$this->load->view('web/header', $data);
		
			if($info1['blacklisted'] == '0'){
			    $data['page'] ='';
			    $this->load->view('web/domainnotexist',$data);
			}else{
			    $this->load->view('web/response',$data);
			}
		
			$this->load->view('web/footer',$data);
			
		}
	}
    public function isSiteAvailible($url){
        // Check, if a valid url is provided
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            return false;
        }
        $lastchar = substr($url, -1);
        if($lastchar=='.'){
            $info['httpResponseCode'] = "na";
            $info['total_time'] = "....";
            return $info;
        }
        $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8";
        $header[] = "Pragma: no-cache";
        $user_agent_chrome = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
        $user_agent_chrome=array(
          "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4",
          "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4",
          "Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53",
          "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_2_1 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
          "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_2_1 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
          "Mozilla/5.0 (iPad; CPU OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53",
          "Mozilla/5.0 (iPad; CPU OS 4_3_5 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8L1 Safari/6533.18.5",
          "Mozilla/5.0 (Linux; U; en-us; KFAPWI Build/JDQ39) AppleWebKit/535.19 (KHTML, like Gecko) Silk/3.13 Safari/535.19 Silk-Accelerated=true",
          "Mozilla/5.0 (Linux; U; en-us; KFTHWI Build/JDQ39) AppleWebKit/535.19 (KHTML, like Gecko) Silk/3.13 Safari/535.19 Silk-Accelerated=true",
          "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; en-us; Silk/1.0.141.16-Gen4_11004310) AppleWebkit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16 Silk-Accelerated=true",
          "Mozilla/5.0 (Linux; U; Android 2.3.4; en-us; Nexus S Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
          "Mozilla/5.0 (Linux; Android 4.3; Nexus 7 Build/JSS15Q) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.72 Safari/537.36",
          "Mozilla/5.0 (Linux; Android 4.2.1; en-us; Nexus 5 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19",
          "Mozilla/5.0 (BB10; Touch) AppleWebKit/537.10+ (KHTML, like Gecko) Version/10.0.9.2372 Mobile Safari/537.10+",
          "Mozilla/5.0 (Linux; Android 4.3; Nexus 10 Build/JSS15Q) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.72 Safari/537.36",
          "Mozilla/5.0 (Linux; U; Android 2.3; en-us; SAMSUNG-SGH-I717 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
          "Mozilla/5.0 (Linux; U; Android 4.3; en-us; SM-N900T Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30",
          "Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30",
          "Mozilla/5.0 (Linux; Android 4.2.2; GT-I9505 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.59 Mobile Safari/537.36",
          "Mozilla/5.0 (Linux; U; Android 2.2; en-us; SCH-I800 Build/FROYO) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
          "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36");
      $user_agent_chrome=$user_agent_chrome[rand()%sizeof($user_agent_chrome)];
       $ch = curl_init($url);
       
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent_chrome);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,20);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch,CURLOPT_NOBODY,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPGET, true); 
        $result = curl_exec ($ch);
        $lastUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if($httpCode){
            $info['total_time'] = curl_getinfo($ch)['total_time'];
            $info['httpResponseCode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }else{
        
            $info = false;
        }
        curl_close($ch);
   
        return $info;
    }
    public function whatisWebsitemeta($url){
		$data['domain'] = $url;
		if (gethostbyname($url) != $url) {
			$domain1 = $this->addHttps(prep_url($url));
			if($data['response_time'] = $this->isSiteAvailible($domain1)){
				ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.82 Safari/537.36');
				$data2 = $this->web_model->get_single_rowdata('searched_websites', array('domain'=>$url));
			    if(!empty($data2['website_title'])){
					echo "<p>".$data2['website_title']."</p>";
				}else{
					echo "<p>--</p>";
				}
				if(!empty($data2['meta_desc'])){
					echo "<p>".$data2['meta_desc']."</p>";
				}else{
					echo "<p>--</p>";
				}
			}else{
				
			}
		}
	}
}