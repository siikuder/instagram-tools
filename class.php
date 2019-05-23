<?php
class InstagramWeb {
    
    public $cookies = "";
    public $useragent = "";

    private $profileData = null;
    private $isLogged = false;

    /**
     * Start login to your instagram
     * @return boolean
     */

    public function doLogin() {
        if(false === $this->checkCookies()) {
            throw new Exception("ERROR : Cookies tidak valid atau cookies kosong.");
        }
        if(false === $this->checkUserAgent()) {
            throw new Exception("ERROR : UserAgent harus diisi.");
        }
        $a = $this->proccess($this->useragent, "https://www.instagram.com/", $this->cookies);
        if(strpos($a[1], '"viewer":{"biography":"')) {
            preg_match('#<script type="text/javascript">window._sharedData = (.*?);</script>#', $a[1], $output);
            $this->profileData = json_decode($output[1]);
            $this->isLogged = true;
            return true;
        } else {
            throw new Exception("ERROR : Cookies tidak valid.");
        }
    }

    /**
     * Get profile data of Instagram Account
     * @return JSON
     */
    public function getProfile() {
        if(false === $this->isLogged) {
            throw new Exception("ERROR : Anda belum melakukan login.");
        }
        return $this->profileData;
    }

    /**
     * Check cookies variable null or not null
     * @return boolean
     */
    private function checkCookies() {
        if($this->cookies != "" || !empty($this->cookies)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check useragent variable null or not null
     * @return boolean
     */
    private function checkUserAgent() {
        if($this->useragent != "" || !empty($this->useragent)) {
            return true;
        } else {
            return false;
        }

    }
    
    /**
     * cURL Function
     * @return array
     */
    private function proccess($useragent, $url, $cookie = 0, $data = 0, $httpheader = array()){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if($httpheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        if ($data):
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        endif;
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch);
        if(!$httpcode) return false; else{
            $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            curl_close($ch);
            return array($header, $body);
        }
    }
}
?>