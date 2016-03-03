<?php
	header("Content-Type:text/json; charset=utf-8");
	//ini_set('display_errors', false);       //设置服务器不显示

	//$topic_id=$_GET["topic_id"];

	$con = mysql_connect('10.109.247.246','root','123456');
	if (!mysql_select_db("statistic_result",$con)){
		die(mysql_error());
	}
	mysql_query("set names utf8");

	if ($_SERVER['REQUEST_METHOD']=="GET"){
			getContent();
			exit;
		}
	
	function getContent(){
		$jsonp=$_GET["callback"];
		global $con;
		$topic_id=$_GET['topic_id'];
		//$sql="SELECT `emotion_score` FROM `base_info` where `topic_id`=$_POST['topic']";
		$sql="SELECT `content` FROM `result_weibo` where `topic_id`=$topic_id";
		$res=mysql_query($sql, $con);
        $row = mysql_fetch_array($res);

        mysql_select_db("weibotest1",$con);
        $time_sql = "select `time` from `selected_topic` where `id`=$topic_id";
        $res_time = mysql_query($time_sql, $con);
        $row_time = mysql_fetch_array($res_time); 
    
        $cmt_sql = "select max(convert(commentNum,unsigned)), max(convert(forwardNum,unsigned)) from weibo_unique as a  where a.topic in (select b.topic from selected_topic as b where b.id=$topic_id)";
        $res_cmt = mysql_query($cmt_sql,$con);
        $row_cmt = mysql_fetch_array($res_cmt);
		$resArr=array();
		$resArr["content"]=$row['content'];
        $resArr["time"] = $row_time['time'];
        $resArr["cmt_num"] = $row_cmt[0];
        $resArr["fwd_num"] = $row_cmt[1];
		$jsonArr0=json_encode($resArr);

		$jsonArr=$jsonp.'('.$jsonArr0.')';

		echo $jsonArr;
		return;

	}

	mysql_close($con);
