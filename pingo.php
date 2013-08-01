<?php

class pingo {

    public function doAction($action, $do)
    {
        switch($action) {

            case "status":
                echo $this->ping($do['url']);
            break;

            case "ip":
                echo (string) trim(file_get_contents('http://phihag.de/ip/'));
            break;

            case "repeat":
                if(!isset($_COOKIE['repeat']))
                {
                    setcookie('repeat', '20', time()+60*60*24*30);
                }

                if(isset($do['repeat']))
                {
                    setcookie('repeat', $do['repeat'], time()+60*60*24*30);
                    header('location: /');
                }
            break;

        }
        die();
    }


    public function render($phtml)
    {
        if(is_file($phtml)) include($phtml);
    }

    public function ping($url)
    {
        if(!$url) $url = 'google.com';
        $host = $url;
        $port = 80;
        $waitTimeoutInSeconds = 1;
        if($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){
            return true;
        } else {
            return false;
        }
        fclose($fp);
    }

    public function derp_ping($url)
    {
        try {

            if(!$url) $url = 'http://google.com';
            return (bool) file_get_contents($url);

        } catch(Exception $e) {

            return false;
        }
    }
    
}