#!/usr/bin/php
<?php
$fritzbox_host = "192.168.1.1";
$mqtt_server = "192.168.1.10";
$mqtt_topic = "fritzbox/traffic";

/* nothing to change below this line */

$data_stats = GetAddonInfos();
//$value = "Down: ". round($data_stats['NewByteReceiveRate']/1024,2) . " kB/s | Up: ". round($data_stats['NewByteSendRate']/1024,2) . " kB/s";
$value = '{"rate": {"down":'. round($data_stats['NewByteReceiveRate']/1024,2) .',"up": '. round($data_stats['NewByteSendRate']/1024,2) .'},';
$value .= '"gesamt":{"down":'. round($data_stats['NewTotalBytesReceived']/1024/1024,2) .',"up": '. round($data_stats['NewTotalBytesSendSent']/1024/1024,2) .'}';
//echo $value;
shell_exec("mosquitto_pub -h '". $mqtt_server ."' -t '". $mqtt_topic ."' -m '". $value ."'");

function GetAddonInfos() {
	$data_url = '/igdupnp/control/WANCommonIFC1';
	$data_soap = '"urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1#GetAddonInfos"';
	$data_xml = '<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetAddonInfos xmlns:u=urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1 /></s:Body></s:Envelope>';
	$tmp = DoCurl($data_url,$data_soap,$data_xml);
	$data_result = Array();
        $data_result['NewByteReceiveRate'] = FindKey($tmp, "NewByteReceiveRate");
        $data_result['NewByteSendRate'] = FindKey($tmp, "NewByteSendRate");
	$data_result['NewTotalBytesSent'] = FindKey($tmp,"NewTotalBytesSent");
	$data_result['NewTotalBytesReceived'] = FindKey($tmp,"NewTotalBytesReceived");
	return $data_result;
}

function DoCurl($url,$soap,$xml) {
	$ch = curl_init("http://" . $GLOBALS["fritzbox_host"] . ":49000" . $url);
	$headers = array();
	$headers[] = 'Content-Type: text/xml; charset="utf-8"';
	$headers[] = 'HOST: ' . $GLOBALS["fritzbox_host"] . ':49000';
	$headers[] = 'Content-Length: ' . strlen($xml);
	$headers[] = 'SOAPACTION: ' . $soap;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_IPRESOLVE, 1); // Force IPv4
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); // Add XML in POST-Header
	$tmp = curl_exec($ch);
	curl_close($ch);
	return $tmp;
}

function FindKey($text,$key) {
	$p1 = strpos($text,$key);
	if($p1 === false) {
		$tmp = "";
	} else {
		$p1 = strpos($text,'>',$p1) +1;
		$p2 = strpos($text,'<',$p1);
		if($p2 === false) {
			$tmp = "";
		} else {
			$tmp = substr($text,$p1,$p2-$p1);
		}
	}
	return $tmp;
}
?>
