define(function () {
	var $ = require('jquery');
    require('highcharts');
    
	/**
	* 平均情感值
	*/
	function renderEmotionAve () {
        var topic_id=location.search;
            topic_id=topic_id.slice(10);
        var request = $.ajax({

            type: 'GET',
            url:'http://10.109.247.246/server/emotionAve.php?topic_id='+ topic_id,
            dataType: 'jsonp',
			jsonp:'callback',
			async: true, //异步执行
        });
        
        request.done(function (data) {
            var gaugeOptions = {

                chart: {
                    type: 'gauge'
                },

                title: null,

                

                pane: {
                    center: ['50%', '85%'],
                    size: '140%',
                    startAngle: -90,
                    endAngle: 90,
                    background: {
                        // backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                        // backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                        innerRadius: '100%',
                        outerRadius: '100%',
                        shape: 'arc'
                    }
                },

                tooltip: {
                    enabled: false
                },

                // the value axis
                yAxis: {
                    

                    plotBands: [{
                from: 0,
                to: 3,
                color: '#E86D6D',// green
                thickness: '20%'
            }, {
                from: 3,
                to: 7,
                color: '#53B5FF',// yellow
                thickness: '20%'
            }, {
                from: 7,
                to: 10,
                color: '#D6DF2B', // red
                thickness: '20%'
            }],       

            //设置仪表盘刻度线
            minorTickInterval: 10,
            minorTickWidth: 0,
            minorTickLength: 0,
            minorTickPosition: 'inside',
            minorTickColor: '#fff',
            
            //彩条内部的
            tickPixelInterval: 50,
            tickWidth: 0,
            tickPosition: 'inside',
            tickLength: 20,
            tickColor: '#fff', 
            // labels: {
            //     step: 4,
            //     rotation: 'auto'
            // },

                    // stops: [
                    //     [0.1, '#55BF3B'], // green
                    //     [0.5, '#DDDF0D'], // yellow
                    //     [0.9, '#DF5353'] // red
                    // ],
                    // lineWidth: 0,
                    // minorTickInterval: null,
                    // tickPixelInterval: 400,
                    // tickWidth: 0,
                    title: {

                        y: -70
                    },
                    labels: {
                        y: 16
                    }
                },

                plotOptions: {
                    guage:{

                        

                    solidgauge: {

                        dataLabels: {
                            y: 5,
                            borderWidth: 0,
                            useHTML: true
                        }
                    }

                    }
                    
                }
            };

            $('#emotionAve').highcharts(Highcharts.merge(gaugeOptions, {
                yAxis: {
                    min: 0,
                    max: 10,
                    title: {
                        text: '情感平均值'
                    }
                },

                credits: {
                    enabled: false
                },


                series: [{
                    name: '情感平均值',
                    data: [parseInt(data.emo_ave)],
                    tooltip: {
                            valueSuffix: '情感值'
                            },

                    dataLabels: {
                        format: '<br/>'+'<div style="text-align:center"><span style="font-size:25px;color:' +
                            ((Highcharts.theme && Highcharts.theme.contrastTextColor) || '#721d83') + '">{y}</span>' 
                            // +
                            //    '<span style="font-size:12px;color:purple">平均值</span></div>'
                    },

                    //改变仪表盘指针的样式
                    dial: {//仪表盘指针

                    radius: '80%',
                    rearLength: '0%',
                    backgroundColor: 'silver',
                    borderColor: 'silver',
                    borderWidth: 1,
                    baseWidth: 10,
                    topWidth: 1,
                    baseLength: '30%'
                    }


                }]    
            }));
        });
    }

    return {
    	renderEmotionAve: renderEmotionAve
    }
});
