<?php
	header("Content-Type:text/json; charset=utf-8");
	ini_set('display_errors', false);       //设置服务器不显示
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

            $return_array=array();
            foreach ($resArr as $key=>$val){
                $inn_tmp = array("time_point"=>$key,"negative"=>$val[0],"neutral"=>$val[1],"positive"=>$val[2]);
                array_push($return_array, $inn_tmp);
            }
            $trendArr["emotionTrend"]=$return_array;
            $jsonArr0 = json_encode($trendArr);
            $jsonArr = $jsonp.'('.$jsonArr0.')';
            echo $jsonArr;
            

            /*
		$trendArr["emotionTrend"]=$resArr;
		
		$jsonArr0=json_encode($trendArr);

		$jsonArr=$jsonp.'('.$jsonArr0.')';

        echo $jsonArr;
         */
		return;

	}
    //emotionTrend();
    mysql_close($con);
