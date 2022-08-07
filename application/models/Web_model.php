<?php 

class Web_model extends CI_Model {
    
    public function insertIntoSearchedWebsite($data = false){
        $this->db->insert('searched_websites', $data);
        return $this->db->insert_id();
    }
    
    public function getSingleFromSearchedWebsite($where = false){
        return $this->db->get_where('searched_websites', $where)->row_array();
    }
    
    public function updateSearchedWebsite($where = false, $data=false){
        $this->db->where($where);
		$this->db->update('searched_websites',$data);
		return $this->db->affected_rows();
    }
    public function updateResponsetime($where = false, $data=false){
        $this->db->where($where);
		$this->db->update('website_response',$data);
		return $this->db->affected_rows();
    }
    public function getAllFromSearchedWebsite(){
        $this->db->order_by('last_search_time','DESC');
        return $this->db->get('searched_websites')->result_array();
    }
    
    public function getAllofLast24hrsFromSearchedWebsite(){
        $this->db->order_by('last_search_time','DESC');
        $this->db->order_by('count','DESC');
        $this->db->limit('4');
		return $this->db->get_where("searched_websites","timediff(now(), last_search_time) < '24:00:00' AND blacklisted='1' ")->result_array();
		
    }
     public function getIpAddress(){
      
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    public function IPtoLocation(){ 
        $ip = $this->getIpAddress();
        $ch = curl_init('http://ipwho.is/'.$ip);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $apiResponse = curl_exec($ch);
        curl_close($ch);
        
        // Retrieve IP data from API response 
        $ipData = json_decode($apiResponse, true); 
        // Return geolocation data 
        return !empty($ipData)?$ipData:false; 
    }
    public function insertIntoWebsiteResponse($data = array()){
        $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data['2']));
        if(!empty($info)){
            $this->db->insert('website_response', array('domain_id'=>$info['id'], 'response_code'=>$data['0'], 'response_time'=>$data['1']));
            return $this->db->insert_id();
        }
    }
    
    public function getAllDownWebsiteResponse(){
        
        $this->db->select('searched_websites.domain');
    	$this->db->order_by('website_response.addedon','DESC');
    	$this->db->limit('8');
    	$this->db->join('searched_websites', "website_response.domain_id = searched_websites.id");
        $this->db->group_by('searched_websites.domain');
        return $this->db->get_where('website_response', "response_code != '200' AND `response_code` != '403' AND `response_code` != '405' AND `response_code` != '301' AND `response_code` != '302'")->result_array();
    }
    public function insertReview($data = array()){
        $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data['domain']));
        if(!empty($info)){
            $data['domain_id'] = $info['id'];
            unset($data['domain']);
            $this->db->insert('review', $data);
            return $this->db->insert_id();   
        }
    }
    public function insertReport($data = array()){
       
         if(!empty($data)){
        $loc =  $this->IPtoLocation();
        if(!empty($loc['city']) || !empty($loc['region'])){
            $data['location'] = $loc['city'].', '.$loc['region'];
            $data['country'] = $loc['country_code'];
        }
        else{
            $data['location'] = "-, -";
            $data['country'] = "-";
        }
        $info = $this->getSingleFromSearchedWebsite(array('id'=> $data['idd']));
        if(!empty($info)){
            $data['domain_id'] = $info['id'];
            unset($data['idd']);
            if($data['report']){
                $dd['comment'] = $data['report'];
                $dd['domain_id'] = $info['id'];
                $this->db->insert('comment', $dd);
            }
            $this->db->insert('report_issue', $data);
            return $this->db->insert_id();
            
        }else{
            return '';
        }
        }else{
            return '';
        }
    }
    public function getReviewsOfDomain($data){
        if(!empty($data)){
        $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
        $this->db->select('searched_websites.domain,review.review, review.rating, review.addedon');
    	$this->db->order_by('review.addedon','DESC');
    	$this->db->join('review', "searched_websites.id = review.domain_id");
        return $this->db->get_where('searched_websites', array('domain'=>$data))->result_array();
        }else{
            return '';
        }
    }
    
    public function getReportByLocation($data = false){
        if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info)){
                
                $date = date('Y-m-d');
               $date=date_create($date);
                //date_add($date,date_interval_create_from_date_string("-14 days"));
                $date = date_format($date,"Y-m-d");
                // return $this->db->query("SELECT domain_id, date(addedon) AS addeddate, COUNT((domain_id)) AS value FROM report_issue WHERE (domain_id =".$info['id'].") AND (addedon > '$date') GROUP BY addeddate ORDER BY addeddate ASC")->result_array();
                
                return $this->db->query("select location, report, country, count(location) as totalreport from report_issue where domain_id =".$info['id']." group by location, report order by totalreport DESC LIMIT 5 ")->result_array();
            }
            else{
                return '';
            }
        }else{
            return '';
        }
    }
    
    public function getReportByLocation1day($data = false){
        if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info)){
                
                $date = date('Y-m-d');
               $date=date_create($date);
                date_add($date,date_interval_create_from_date_string("-1 days"));
                $date = date_format($date,"Y-m-d");
                return $this->db->query("SELECT domain_id, date(addedon) AS addeddate, COUNT((domain_id)) AS value FROM report_issue WHERE (domain_id =".$info['id'].") AND (addedon > '$date') GROUP BY addeddate ORDER BY addeddate ASC")->result_array();
                
                //return $this->db->query("select location, country, count(location) as totalreport from report_issue where (domain_id =".$info['id'].") AND  (addedon > '$date' ) group by location order by totalreport DESC ")->result_array();
            }
            else{
                return '';
            }
        }else{
            return '';
        }
    }
    public function getReportByLocation7day($data = false){
        if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info)){
                
                $date = date('Y-m-d');
               $date=date_create($date);
                date_add($date,date_interval_create_from_date_string("-7 days"));
                $date = date_format($date,"Y-m-d");
                return $this->db->query("SELECT domain_id, date(addedon) AS addeddate, COUNT((domain_id)) AS value FROM report_issue WHERE (domain_id =".$info['id'].") AND (addedon > '$date') GROUP BY addeddate ORDER BY addeddate ASC")->result_array();
                
                //return $this->db->query("select location, country, count(location) as totalreport from report_issue where (domain_id =".$info['id'].") AND  (addedon > '$date' ) group by location order by totalreport DESC ")->result_array();
            }
            else{
                return '';
            }
        }else{
            return '';
        }
    }
    
    public function getReportByLocation30day($data = false){
        if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info)){
                
                $date = date('Y-m-d');
               $date=date_create($date);
                date_add($date,date_interval_create_from_date_string("-30 days"));
                $date = date_format($date,"Y-m-d");
                 return $this->db->query("SELECT domain_id, date(addedon) AS addeddate, COUNT((domain_id)) AS value FROM report_issue WHERE (domain_id =".$info['id'].") AND (addedon > '$date') GROUP BY addeddate ORDER BY addeddate ASC")->result_array();
                
               // return $this->db->query("select location, country, count(location) as totalreport from report_issue where (domain_id =".$info['id'].") AND  (addedon > '$date' ) group by location order by totalreport DESC ")->result_array();
            }
            else{
                return '';
            }
        }else{
            return '';
        }
    }
     public function getReportByLocationalltime($data = false){
        if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info)){
                
                $date = date('Y-m-d');
               $date=date_create($date);
                date_add($date,date_interval_create_from_date_string("-0 days"));
                $date = date_format($date,"Y-m-d");
                 return $this->db->query("SELECT domain_id, date(addedon) AS addeddate, COUNT((domain_id)) AS value FROM report_issue WHERE (domain_id =".$info['id'].")  GROUP BY addeddate ORDER BY addeddate ASC")->result_array();
                
               // return $this->db->query("select location, country, count(location) as totalreport from report_issue where (domain_id =".$info['id'].") AND  (addedon > '$date' ) group by location order by totalreport DESC ")->result_array();
            }
            else{
                return '';
            }
        }else{
            return '';
        }
    }
    public function insertComment($data=false){
        if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data['domain']));
            if(!empty($info['id'])){
                $data['domain_id']=$info['id'];
                unset($data['domain']);
                $this->db->insert('comment', $data);
                return $this->db->insert_id();
            }
        }else{
            return '';
        }
    }
    
    public function getComment($data=false){
         if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info['id'])){
                return $this->db->query("select * from comment where (domain_id =".$info['id']." AND isApproved = '1') order by addedon DESC")->result_array();
            }
        }else{
            return '';
        }
    }
    
    public function getReportAndReview(){
        $this->db->select('report, report_issue.addedon, domain');
        $this->db->join('searched_websites', 'report_issue.domain_id = searched_websites.id');
        $this->db->order_by('addedon', 'DESC');
        $this->db->limit(4);
        $report = $this->db->get('report_issue')->result_array();
       
      $this->db->select('review, rating, review.addedon, domain');
       $this->db->join('searched_websites', 'review.domain_id = searched_websites.id');
      $this->db->order_by('review', 'DESC');
        $this->db->limit(4);
       $review = $this->db->get('review')->result_array();
       $r = array_merge($report,$review);
       return $r;
    }
    
    public function getReportForGraph($data =false){
        if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info['id'])){
                $date = date('Y-m-d');
               $date=date_create($date);
                date_add($date,date_interval_create_from_date_string("-14 days"));
                $date = date_format($date,"Y-m-d");
                //
               return $this->db->query("SELECT domain_id, date(addedon) AS addeddate, COUNT((domain_id)) AS value FROM report_issue WHERE (domain_id =".$info['id'].") AND (addedon > '$date') GROUP BY addeddate ORDER BY addeddate ASC")->result_array();
                
                
                //return $this->db->query("SELECT domain_id, DATE_ADD('1000-01-01 00:00:00', Interval CEILING(TIMESTAMPDIFF(MINUTE, '1000-01-01 00:00:00', addedon) / 15) * 15 minute) AS date, COUNT((domain_id)) AS value FROM report_issue1 WHERE (domain_id =".$info['id'].") AND (timediff(now(), addedon) < '$date') GROUP BY date ORDER BY date ASC")->result_array();
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    
    public function getNOofReportbyType($data = false){
         if(!empty($data)){
            $info = $this->getSingleFromSearchedWebsite(array('domain'=> $data));
            if(!empty($info['id'])){
                 $date = date('Y-m-d');
               $date=date_create($date);
                date_add($date,date_interval_create_from_date_string("-14 days"));
                $date = date_format($date,"Y-m-d H:i:s"); 
                
                return $this->db->query("SELECT report as type, count(report) as noof FROM `report_issue` where (domain_id =".$info['id']." AND ( addedon) > '$date') GROUP BY report ")->result_array();
            }else{
                return '';
            }
        }else{
            return '';
        }
        
    }
	public function mostSearched(){
	    $this->db->select('*');
	    $this->db->order_by('count', 'DESC');
	  
		$this->db->limit('100');
	    return $this->db->get_where('searched_websites', array('blacklisted'=>'1'))->result_array();
	}
	public function mostDiscussed(){
	    $this->db->select('domain, searched_websites.id as websiteid, comment.id as commentwebsiteid, name, comment, domain_id, COUNT(domain_id) as nooftimediscussed');
	    $this->db->join('searched_websites', 'searched_websites.id = comment.domain_id');
        $this->db->group_by('domain_id'); 
        $this->db->order_by('nooftimediscussed', 'desc'); 
       return $this->db->get('comment', 100)->result_array();
	}
	
	public function record_count() {
        return $this->db->count_all("searched_websites");
    }

    public function fetch_countries($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->order_by('last_search_time', 'desc'); 
       // $query = $this->db->get("searched_websites");
        $query =  $this->db->get_where('searched_websites', array('blacklisted'=>'1'));

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   public function get_single_rowdata($table,$where){
    return $this->db->get_where($table,$where)->row_array();
    }
}