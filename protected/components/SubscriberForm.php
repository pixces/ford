<?php
/**
 * Created by IntelliJ IDEA.
 * User: zainulabdeen
 * Date: 14/03/14
 * Time: 12:29 AM
 * To change this template use File | Settings | File Templates.
 */ 
class SubscriberForm extends CWidget {

    private $page_name;
    private $ipAddress;
    private $env;

    public function init(){
        $query = $_SERVER['QUERY_STRING'];
        parse_str($query);

        $this->page_name = isset($view) ? $view : 'curtain-raiser';
        $this->ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $this->env = isset($_GET['env']) ? $_GET['env'] : strtolower(Yii::app()->params['ENV']);
    }

    public function getPageName(){
        if (isset($this->page_name)){
            return $this->page_name;
        }
    }

    public function getUserIp(){
        if (isset($this->ipAddress)){
            return $this->ipAddress;
        }
    }

    public function getEnv(){
        if(isset($this->env)){
            return $this->env;
        }
    }

    public function run(){
        $this->render('SubscriberForm');
    }

}
