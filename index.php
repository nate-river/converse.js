<?php
if( !(isset($_COOKIE['jid'])
      &&
      isset($_COOKIE['sid'])
      &&
      isset($_COOKIE['rid'])
      )){

    global $_jabber_rid_;
    $_jabber_rid_ = rand() * 10000;
    global $_jabber_sid_;

    function jabber_get_next_rid() {
        global $_jabber_rid_;
        $_jabber_rid_ = $_jabber_rid_ + 1;
        return $_jabber_rid_;
    }

    function jabber_send_xml ($xmlposts) {
        global $_jabber_rid_;
        global $_jabber_sid_;

        $bosh_url = 'http://192.168.1.120/http-bind';
        $xml_repsonse = array();
        $count = 0;
        foreach ($xmlposts as $xmlpost) {
            var_dump('out');
            var_dump($xmlpost);
            $count = $count + 1;
            $_jabber_rid_ = $_jabber_rid_ + 1;
            $ch = curl_init($bosh_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlpost);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $header = array('Accept-Encoding: gzip, deflate','Content-Type: text/xml; charset=utf-8');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            // Stops the dump to the screen and lets you capture it in a variable.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlpost);
            $response = curl_exec($ch);
            var_dump('come');
            var_dump($response);

            $xml_response[] = simplexml_load_string($response);
        }
        curl_close($ch);
        return $xml_response;
    }

    function jabber_get_rid_sid() {
        global $_jabber_rid_;
        $_jabber_rid_ = rand() * 10000;
        global $_jabber_sid_;

        $xmlposts = array();
        $xmlposts[] = "<body rid='$_jabber_rid_' xmlns='http://jabber.org/protocol/httpbind' to='192.168.1.120' xml:lang='en' wait='60' hold='1' window='5' content='text/xml; charset=utf-8' ver='1.6' xmpp:version='1.0' xmlns:xmpp='urn:xmpp:xbosh'/>";
        $xml_response = jabber_send_xml($xmlposts);
        $_jabber_sid_ = $xml_response[0]['sid'];

        $xmlposts = array();
        $jid = 'lix@192.168.1.120';
        $username = 'lix';
        $domain = '192.168.1.120';
        $password = 'lix';

        $thepw = base64_encode(chr(0) . $username . chr(0) . $password);
        $xmlposts[] = '<body rid="'.$_jabber_rid_.'" xmlns="http://jabber.org/protocol/httpbind" sid="'.$_jabber_sid_.'"><auth xmlns="urn:ietf:params:xml:ns:xmpp-sasl" mechanism="PLAIN">'.$thepw.'</auth></body>';
        $xmlposts[] = "<body rid='" . jabber_get_next_rid() . "' xmlns='http://jabber.org/protocol/httpbind' sid='$_jabber_sid_' to='192.168.1.120' xml:lang='en' xmpp:restart='true' xmlns:xmpp='urn:xmpp:xbosh'/>";

        $xmlposts[] = "<body rid='" . jabber_get_next_rid() . "' xmlns='http://jabber.org/protocol/httpbind' sid='$_jabber_sid_'><iq type='set' id='_bind_auth_2' xmlns='jabber:client'><bind xmlns='urn:ietf:params:xml:ns:xmpp-bind'></bind></iq></body>";

        $xmlposts[] = "<body rid='" . jabber_get_next_rid() . "' xmlns='http://jabber.org/protocol/httpbind' sid='$_jabber_sid_'><iq type='set' id='_session_auth_2' xmlns='jabber:client'><session xmlns='urn:ietf:params:xml:ns:xmpp-session'/></iq></body>";

        $xml_response = jabber_send_xml($xmlposts);

    }

    jabber_get_rid_sid();
    $test['rid'] = $_jabber_rid_;
    $test['sid'] = $_jabber_sid_;
    $test['jid'] = 'lix@192.168.1.120';

    setcookie('jid', $test['jid'], 0, '/');
    setcookie('sid', $test['sid'], 0, '/');
    setcookie('rid', $test['rid'], 0, '/');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8' />
        <meta http-equiv="X-UA-Compatible" content="chrome=1" />
        <meta name="description" content="Converse.js: Open Source Browser-Based Instant Messaging" />
        <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/stylesheet.css">
        <link rel="stylesheet" type="text/css" media="screen" href="converse.css">
        <!-- <script src="converse.min.js"></script> -->
        <script data-main="main" src="Libraries/require-jquery.js"></script>
        <title>Converse.js</title>
    </head>

    <body>
        <div id="chatpanel">
            <div id="collective-xmpp-chat-data"></div>
            <div id="toggle-controlbox">
                <a href="#" class="chat toggle-online-users">
                    <strong class="conn-feedback">online-count</strong> <strong style="display: none" id="online-count">(0)</strong>
                </a>
            </div>
        </div>

    </body>
</html>
