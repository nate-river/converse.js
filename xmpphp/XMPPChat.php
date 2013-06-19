<?php
/**
 * 处理Chat用户注册,添加好友,删除好友
 */
require_once dirname(__FILE__).'/XMPP.php';

Class XMPPChat {

    //用于以管理员身份登录注册新用户
    const ADMINUSERNAME = 'admin';
    const ADMINUSERPASSWORD = 'admin';

    const XMPPHOST = 'localhost';
    const PORT = '5222';
    const RESOURCE = 'xmpphp';

    public $username;
    public $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function register($email = NULL){

        $conn = new XMPPHP_XMPP (self::XMPPHOST, self::PORT, self::ADMINUSERNAME, self::ADMINUSERPASSWORD, self::RESOURCE);
        $conn->connect();
        $conn->processUntil('session_start');
        $conn->registerNewUser($this->username, $this->password, $email);
        $conn->disconnect();

    }

    public function addRosterContact($uid, $name) {

        $conn = new XMPPHP_XMPP (self::XMPPHOST, self::PORT, $this->username, $this->password, self::RESOURCE);
        $conn->autoSubscribe();

        $conn->connect();
        $conn->processUntil(array('message', 'presence', 'end_stream', 'session_start', 'vcard'));
        $conn->addRosterContact($this->uidToJid($uid), $name);
        $conn->subscribe($this->uidToJid($uid));

        $conn->disconnect();
    }

    public function deleteRosterContact($uid) {

        $conn = new XMPPHP_XMPP (self::XMPPHOST, self::PORT, $this->username, $this->password, self::RESOURCE);
        $conn->connect();
        $conn->processUntil('session_start');
        $conn->deleteRosterContact($this->uidToJid($uid));
        $conn->disconnect();
    }

    public function uidToJid($uid) {
        return $uid.'@'.self::XMPPHOST; 
    }

    public function setUserVcard(){
        $conn = new XMPPHP_XMPP (self::XMPPHOST, self::PORT, $this->username, $this->password, self::RESOURCE);
        $conn->connect();
        $conn->processUntil('session_start');
        $conn->disconnect();
    }
}
?>