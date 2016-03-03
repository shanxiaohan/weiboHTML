<?php
	header("Content-Type:text/json; charset=utf-8");
	ini_set('display_errors', false);       //璁剧疆鏈嶅姟鍣ㄤ笉鏄剧ず
	//header("Content-Type: application/json");

	//$topic_id=$_GET["topic_id"];

	$con = mysql_connect('10.109.247.246','root','123456');
	if (!mysql_select_db("statistic_result",$con)){
		die(mysql_error());
	}
	mysql_query("set names utf8");

	if ($_SERVER['REQUEST_METHOD']=="GET"){
			emotionTrend();
			exit;
		}
	
	function emotionTrend(){
		$jsonp=$_GET["callback"];
		global $con ;
		$topic_id=$_GET['topic_id'];
        $zoom = $_GET['zoom'];
        //$topic_id = '1';
        $sql_max = "select `time_point` from `result_tendency_info` where `topic_id`=$topic_id order by convert(`count_t`,signed) desc limit 1";
        $date_res = mysql_query($sql_max,$con);
        $date_max = mysql_fetch_row($date_res)[0];
        $max_tmp = substr($date_max,0, 5);
        //$max_tmp = substr($date_max,0 ,10);
        $d0 = date("m/d",strtotime("-5 day",strtotime($max_tmp)));
        $d1 = date("m/d",strtotime("-4 day",strtotime($max_tmp)));
        $d2 = date("m/d",strtotime("-3 day",strtotime($max_tmp)));
        $d3 = date("m/d",strtotime("-2 day",strtotime($max_tmp)));
        $d4 = date("m/d",strtotime("-1 day",strtotime($max_tmp)));
        $d5 = $max_tmp;
        $d6 = date("m/d",strtotime("+1 day",strtotime($max_tmp)));
        $d7 = date("m/d",strtotime("+2 day",strtotime($max_tmp)));
        $d8 = date("m/d",strtotime("+3 day",strtotime($max_tmp)));
        $d9 = date("m/d",strtotime("+4 day",strtotime($max_tmp)));
        $date_list = array();

        if ($zoom == 1){
            array_push($date_list, $d0,$d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9);
        
            $resArr = array();

        for ($i = 0; $i< 10; $i++){
            $date0 = $date_list[$i]."-00";
            $date1 = $date_list[$i]."-12";
            
            $resArr[$date0] = array(0,0,0);
            $resArr[$date1] = array(0,0,0);
        }

        $sql_time = "select * from `result_tendency_info` where `topic_id`=$topic_id";
        $time_res = mysql_query($sql_time, $con);
        $time_array = mysql_fetch_array($time_res);
        $time_arr = array();
        $count_neg = 0;
        $count_zero = 0;
        $count_pos = 0;
        while ( $row = mysql_fetch_row($time_res)){
            $tmp = $row[0];
            $tmp_neg = (int)$row[2];
            $tmp_zero = (int)$row[3];
            $tmp_pos = (int)$row[4];
            $mydate = substr($tmp,0,5);
            $myhour = substr($tmp,6,2);
            if (!in_array($mydate, $date_list)){
                continue;
            }

            if ( $myhour == '06') {
                $time_show = $mydate."-00";
            }elseif($myhour == '18'){
                $time_show = $mydate."-12";
            }else{
                $time_show = $tmp;
            }
            
            $resArr[$time_show][0]+=$tmp_neg; 
            $resArr[$time_show][1]+=$tmp_zero;
            $resArr[$time_show][2]+=$tmp_pos;

            }
            }
        elseif ($zoom==2){
         //时间轴扩展，default为zoom==1，间隔12小时；zoom==2，间隔24小时，即11/23-00等
                 
            $d10 = date("m/d",strtotime("-9 day",strtotime($max_tmp)));
            $d11 = date("m/d",strtotime("-8 day",strtotime($max_tmp)));
            $d12 = date("m/d",strtotime("-7 day",strtotime($max_tmp)));
            $d13 = date("m/d",strtotime("-6 day",strtotime($max_tmp)));
            $d14 = date("m/d",strtotime("+5 day",strtotime($max_tmp)));
            $d15 = date("m/d",strtotime("+6 day",strtotime($max_tmp)));
            $d16 = date("m/d",strtotime("+7 day",strtotime($max_tmp)));
            $d17 = date("m/d",strtotime("+8 day",strtotime($max_tmp)));
            $d18 = date("m/d",strtotime("+9 day",strtotime($max_tmp)));
            $d19 = date("m/d",strtotime("+10 day",strtotime($max_tmp)));

            array_push($date_list,$d10,$d11,$d12,$d13,$d0,$d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d14,$d15,$d16,$d17,$d18,$d19);
            $resArr = array();
            for ($i = 0; $i<20; $i++){
                $date0 = $date_list[$i];
                $resArr[$date0] = array(0,0,0);
            }
 
    	$sql_time = "select * from `result_tendency_info` where `topic_id`=$topic_id";
        $time_res = mysql_query($sql_time, $con);
        $time_array = mysql_fetch_array($time_res);
        $time_arr = array();
        $count_neg = 0;
        $count_zero = 0;
        $count_pos = 0;
		
	while ( $row = mysql_fetch_row($time_res)){
            $tmp = $row[0];
            $tmp_neg = (int)$row[2];
            $tmp_zero = (int)$row[3];
            $tmp_pos = (int)$row[4];
            $mydate = substr($tmp,0,5);
            if (!in_array($mydate, $date_list)){
                continue;
            }

            $time_show = $mydate;

            $resArr[$time_show][0]+=$tmp_neg;
            $resArr[$time_show][1]+=$tmp_zero;
            $resArr[$time_show][2]+=$tmp_pos;

         }
         }elseif ($zoom==0){
             //zoom==0 的时候，时间轴缩减，间隔6小时，即为原时间粒度
            array_push($date_list,$d3,$d4,$d5,$d6,$d7);
	for ($i = 0; $i< 5; $i++){
            $date0 = $date_list[$i]."-00";
            $date1 = $date_list[$i]."-06";
            $date2 = $date_list[$i]."-12";
            $date3 = $date_list[$i]."-18";
            $resArr[$date0] = array(0,0,0);
            $resArr[$date1] = array(0,0,0);
            $resArr[$date2] = array(0,0,0);
            $resArr[$date3] = array(0,0,0);
        }

        $sql_time = "select * from `result_tendency_info` where `topic_id`=$topic_id";
        $time_res = mysql_query($sql_time, $con);
        $time_array = mysql_fetch_array($time_res);
        $time_arr = array();
        $count_neg = 0;
        $count_zero = 0;
        $count_pos = 0;
        while ( $row = mysql_fetch_row($time_res)){
            $tmp = $row[0];
            $tmp_neg = (int)$row[2];
            $tmp_zero = (int)$row[3];
            $tmp_pos = (int)$row[4];
            $mydate = substr($tmp,0,5);
            $myhour = substr($tmp,6,2);
            if (!in_array($mydate, $date_list)){
                continue;
            }

            $time_show = $tmp;

            $resArr[$time_show][0]+=$tmp_neg;
            $resArr[$time_show][1]+=$tmp_zero;
            $resArr[$time_show][2]+=$tmp_pos;
        
        }
         }
            $return_array=array();
            foreach ($resArr as $key=>$val){
                $inn_tmp = array("time_point"=>$key,"negative"=>$val[0],"neutral"=>$val[1],"positive"=>$val[2]);
                array_push($return_array, $inn_tmp);
            }
            $trendArr["emotionTrend"]=$return_array;
            $jsonArr0 = json_encode($trendArr);
            $jsonArr = $jsonp.'('.$jsonArr0.')';
            echo $jsonArr;
            
		return;

	}
    //emotionTrend();
    mysql_close($con);
