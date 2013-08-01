<?php

class pingo {

    public $cache = array();

    public function __construct() {
        $this->getCache();

        if(!isset($this->cache['repeat']))
        {
            $this->cache['repeat'] = 20;
            $this->saveCache();
        }
    }

    public function doAction($action, $do)
    {

        switch($action) {

            case "status":
                echo $this->ping($do['url']);
            break;

            case "ip":
                echo (string) trim(file_get_contents('http://phihag.de/ip/'));
            break;

            case "get":
                if(isset($this->cache[$do['option']])) echo $this->cache[$do['option']];
            break;
            case "repeat":

                if(isset($do['repeat']))
                {
                    $this->cache['repeat'] = $do['repeat'];
                    $this->saveCache();
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


    public function getRepeat()
    {
        return (int) $this->cache['repeat'];
    }

    public function ping($url)
    {

        $run = false;
        if(!$url) $url = 'google.com';

        $cache = $this->getCache();
        if(isset($cache[$url])) {
            $lastran = (int) $cache[$url] + $this->getRepeat();
            if(time() >= $lastran) {
                $run = true;
            } else {
                $run = false;
                return $cache[$url.'_status'];
            }

        } else {


            $run = true;

        }

        if($run) {

            $host = $url;
            $port = 80;
            $waitTimeoutInSeconds = 1;
            if($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){
                $cache[$url] = time();
                $cache[$url.'_status'] = true;
                $this->cache = $cache;
                $this->saveCache();
                return true;
            } else {
                $cache[$url] = time();
                $cache[$url.'_status'] = false;
                $this->cache = $cache;
                $this->saveCache();
                return false;
            }
            fclose($fp);
        } else {
            return $cache[$url.'_status'];
        }



    }

    public function getCache()
    {

        if(!is_file('lastran.cache')) {
           file_put_contents('lastran.cache', serialize(array()));
        }

        $this->cache = unserialize(file_get_contents('lastran.cache'));
        return $this->cache;
    }

    public function saveCache($cache = false)
    {
        if(!$cache) $cache = $this->cache;
        file_put_contents('lastran.cache', serialize($cache));
    }

    public function __deconstruct()
    {
        $this->saveCache();
    }
}