<?php
/**
 * 处理Chat用户注册,添加好友,删除好友
 */
require_once dirname(__FILE__).'/XMPP.php';

Class XMPPChat {

    //用于以管理员身份登录注册新用户
    const ADMINUSERNAME = 'admin';
    const ADMINUSERPASSWORD = 'admin';

    //const XMPPHOST = '192.168.1.120';
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
        $conn->processUntil('session_start');
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
        //$userinfo['jid'] = 'test@192.168.1.120';
        $conn = new XMPPHP_XMPP (self::XMPPHOST, self::PORT, $this->username, $this->password, self::RESOURCE);
        $conn->connect();
        $conn->processUntil('session_start');
        //$conn->setVcard($userinfo);
        echo '1';
        $conn->setVcard();
        $conn->disconnect();
    }

    public function makeTwoUserFriend($user1, $user2){
        $conn = new XMPPHP_XMPP (self::XMPPHOST, self::PORT, $user1, $user1, self::RESOURCE);
        $conn->connect();
        $conn->processUntil('session_start');
        $conn->addRosterContact($this->uidToJid($user2), $user2);
        $conn->subscribe($this->uidToJid($user2));
        $conn->disconnect();

        $conn = new XMPPHP_XMPP (self::XMPPHOST, self::PORT, $user2, $user2, self::RESOURCE);
        $conn->connect();
        $conn->processUntil('session_start');
        $conn->addRosterContact($this->uidToJid($user1), $user1);
        $conn->subscribe($this->uidToJid($user1));
        $conn->disconnect();
    }
}
$test  = new XMPPChat('mayl','mayl');
$test->setUserVcard();

?>