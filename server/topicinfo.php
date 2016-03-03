<?php
	header("Content-Type:text/json; charset=utf-8");
	ini_set('display_errors', false);       //璁剧疆鏈嶅姟鍣ㄤ笉鏄剧ず

	$con = mysql_connect('10.109.247.246','root','123456');
	if (!mysql_select_db("statistic_result",$con)){
		die(mysql_error());
	}
	mysql_query("set names utf8");

	if ($_SERVER['REQUEST_METHOD']=="GET"){
			getTopics();
			exit;
		}
	
	function getTopics(){
		//$jsonp=$_GET["callback"];
		global $con;
		$sql="SELECT `topic_id`,`topic` FROM `result_weibo`";
		$res=mysql_query($sql, $con);
		$row = mysql_fetch_array($res);
        $resArr = array();
        while($row = mysql_fetch_row($res)){
            $topic = $row[1];
            $topic_id = $row[0];

            $tmp = array($topic=>$topic_id);
            array_push($resArr, $tmp);
            
        }
		$alltopics = array("alltopics"=>$resArr);
		$jsonArr0=json_encode($alltopics);
		$jsonArr=$jsonp.'('.$jsonArr0.')';

        echo $jsonArr0;
		return;

	}

   // getTopics();
	mysql_close($con);
