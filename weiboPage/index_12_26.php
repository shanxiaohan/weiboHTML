<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>微博情感分析</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="../browsersWeb/css/normalize.css" type="text/css"> -->
    <link rel="stylesheet" href="css/mygrid.css" type="text/css">
</head>

<body>
    

<!-- 广告轮播 -->
<div id="hello-carousel" class="carousel slide" data-ride="carousel">
    <!-- 轮播下面的小点点 -->
    <ol class="carousel-indicators">
        <li data-target="#hello-carousel" data-slide-to="0" class="active"></li>
        <li data-target="#hello-carousel" data-slide-to="1"></li>
    </ol>
    <!-- 轮播广告的内容 -->
    <div class="carousel-inner">
        <div class="item active">
            <img src="images/cloud1.jpg" alt="1 slide">
            <div class="container">
                <div class="carousel-caption">
                    <p id="weiboHead">See weibo</p>

                    <p><a class="btn btn-lg btn-primary" href="#"
                          role="button" target="_blank">Learn More</a></p>
                </div>
            </div>
        </div>

        <div class="item">
            <img src="images/head2.jpg" alt="2 slide">

            <div class="container" id="searchform" style="width:100px;">

                <div id="search">
                    <form action="javascript:void(0);" method="GET">
                        <fieldset class="clearfix">

                            <input id="topic_search" type="search" name="search" value="What are you looking for?" onBlur="if(this.value=='')this.value='What are you looking for?'" onFocus="if(this.value=='What are you looking for?')this.value='' "> <!-- JS because of IE support; better: placeholder="What are you looking for?" -->
                            <input type="submit" id="search_clk"  value="Search" class="button">

                        </fieldset>

                    </form>

                </div> <!-- end search -->

                 <!-- 下拉菜单 -->
            <div class="dropdown" style="width=300px; margin:80px 120px;">
                <button class="btn btn-primary dropdown-toggle btn-lg" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  Choose a topic please~
                <!-- 下拉菜单向下的小箭头 -->
                <span class="caret"></span> 
                </button>  
                <ul class="dropdown-menu" role="menu">
                  <li><a href="http://10.109.247.246:8848/index.html?topic_id=5">我是你的千万分之一</a></li>
                  <li><a href="http://10.109.247.246:8848/index.html?topic_id=6">亚洲新歌榜</a></li>
                  <li><a href="http://10.109.247.246:8848/index.html?topic_id=8">鹿晗勋章2.0</a></li>
                <li><a href="http://10.109.247.246:8848/index.html?topic_id=9">琅琊榜</a></li>
            <li><a href="http://10.109.247.246:8848/index.html?topic_id=10">他来了请闭眼</a></li>

                </ul>
          </div>

            </div>

           
        </div>  
    </div>

    <!-- 轮播的左右箭头 -->
    <a class="left carousel-control" href="#hello-carousel" data-slide="prev"><span
            class="glyphicon glyphicon-chevron-left"></span></a>
    <a class="right carousel-control" href="#hello-carousel" data-slide="next"><span
            class="glyphicon glyphicon-chevron-right"></span></a>
</div>

    <div class="container"  id="topics" style="width:1280px; height:300px; background-image:linear-gradient(160deg, rgba(65, 166, 180, 0.6), rgba(149, 207, 184, 0.3)); margin:-10px 0px; padding-top:-10px;">
        
      <div class="thumbnail-grid flex">
      <div class="flex-item" id="1">

      <figure class="i1">
      <figcaption><button type="button" class="btn btn-success"
          id="topic_1"><?php
          $con=mysql_connect('10.109.247.246','root','123456'); if (!mysql_select_db("weibotest1",$con)) die(mysql_error());
          mysql_query("set names utf8"); $sql1="select `topic` from
          `selected_topic` where `id`=1";  $res=mysql_query($sql1, $con);$row = mysql_fetch_array($res);
          $topic=$row; echo $topic[0];
          ?></button></figcaption>
      </figure>
      </div>
      <div class="flex-item" id="topic_2">
      <figure class="i2">
      <figcaption><button type="button" class="btn btn-default" id="topic_2"><?php
          $con=mysql_connect('10.109.247.246','root','123456'); if (!mysql_select_db("weibotest1",$con)) die(mysql_error());
                  mysql_query("set names utf8"); $sql1="select `topic` from `selected_topic` where `id`=2";  $res=mysql_query($sql1, $con);$row = mysql_fetch_array($res);
                  $topic=$row; echo $topic[0];
                     ?></button></figcaption>
      </figure>
      </div>
      <div class="flex-item" id="topic_3">
      <figure class="i3">
      <figcaption><button type="button" class="btn btn-primary" id="topic_3"><?php
      $con=mysql_connect('10.109.247.246','root','123456'); if (!mysql_select_db("weibotest1",$con)) die(mysql_error()); mysql_query("set names utf8"); $sql1="select `topic` from `selected_topic` where `id`=3";  $res=mysql_query($sql1, $con);$row = mysql_fetch_array($res);  $topic=$row; echo $topic[0];
               ?></button></figcaption>
      </figure>
      </div>
      <div class="flex-item" id="topic_4">
      <figure class="i4">
      <figcaption><button type="button" class="btn btn-primary" id="topic_4"><?php
         $con=mysql_connect('10.109.247.246','root','123456'); if (!mysql_select_db("weibotest1",$con)) die(mysql_error());      mysql_query("set names utf8"); $sql1="select `topic` from   `selected_topic` where `id`=4";  $res=mysql_query($sql1, $con);$row = mysql_fetch_array($res);
                 $topic=$row; echo $topic[0];
               ?></button></figcaption>
      </figure>
      </div>
      
      </div>

    </div>

    <!-- 显示热门话题 -->
      

<!-- 
    <footer>
        <p class="pull-right"><a href="#top">回到顶部</a></p>

        <p>&copy; ****** </p>
    </footer> -->

</div>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
   $(document).ready(function(){
       //异步获取topic和ID对应关系,heheda~
       var topic_req = $.ajax({
        type:'get',
        url:'http://10.109.247.246/server/topicinfo.php',    
        dataType:'json',  //这个不是跨域的，傻了吧＝ ＝
       // jsonp:'callback',
        async:true,
        success:function(data){
        },
        error:function(){
            alert("failed");
        }
       
       } 
   );
       topic_req.done(function(data){
           $("#search_clk").click(function(){
            var topic = $('#topic_search').val();
         //   var jsondata = JSON.parse(data);
            var alltopics = data['alltopics'];
            alert(alltopics);
            for ( var i =0; i< alltopics.length; i++){
                var item = alltopics[i];
                for (var key in item){
                    if (topic == key){
                        var topic_id = item[key];
                        window.location.href = 'http://10.109.247.246:8848/index.html?topic_id='+topic_id;
                        break;
                    }
                
                }
            
            }
           });



       });



       
 });


  $("#topic_1").click(function(){

    window.location.href='http://10.109.247.246:8848/index.html?topic_id=1';
   
  });

   $("#topic_2").click(function(){

    window.location.href='http://10.109.247.246:8848/index.html?topic_id=2';
   
  });

    $("#topic_3").click(function(){

    window.location.href='http://10.109.247.246:8848/index.html?topic_id=3';
   
  });

     $("#topic_4").click(function(){

    window.location.href='http://10.109.247.246:8848/index.html?topic_id=4';
   
  });




</script>
</body>
</html>
