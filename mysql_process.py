# -*- coding: utf8 -*-
import MySQLdb
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

conn1=MySQLdb.connect(host='10.109.247.246',user='root',passwd='123456',db='weibotest1',charset='utf8')
cur1=conn1.cursor()
conn2=MySQLdb.connect(host='10.109.247.246',user='root',passwd='123456',db='statistic_result',charset='utf8')
cur2=conn2.cursor()
# topic_id=unicode(326)

def topic_process(topic_id):

    # topic_id=unicode(topic_id)
    sql1="select `topic` from `selected_topic` where `id`='%s'" %topic_id
    cur1.execute(sql1)
    topic=cur1.fetchone()[0]

    print topic

    sql3="select `mid`,`uid`,`source`,`time` from `analyse_result` where `topic`='%s'" %topic
    res3=cur1.execute(sql3)
    weibos=cur1.fetchmany(res3)

    #选取微博内容要选最热门的那个,CONVERT()可以将varchar变成int类型再排序
    #weibo_content="select `content` from `weibo_unique` where `topic`='%s' order by CONVERT(`forwardNum`+`likeNum`+`commentNum`, SIGNED) desc limit 1" %topic
    weibo_content = "select `introduction` from `selected_topic` where `topic`='%s'" %topic
    res=cur1.execute(weibo_content)
    if res!=0:
        weibo_content=cur1.fetchone()[0]
        #print weibo_content
        weibo_content=weibo_content.split(u'：')[1]
        #print weibo_content
    else:
        return



    #统计性别，年龄，用户认证信息(会员,high_level>15，普通用户)，情感值
    #计数为int，后面insert要转化成str
    count_male_num=0
    count_female_num=0
    count_null_gender=0

    count_before_70=0
    count_70=0
    count_80=0
    count_90=0
    count_null_age=0

    count_vip=0
    count_highlev=0
    count_ordinary=0
    count_lev_vip=0

    count_negative=0
    count_positive=0
    count_neutral=0

    province_info={'上海':0,'云南':0,"内蒙古":0,"北京":0,"天津":0,"宁夏":0,"安徽":0,"山东":0,"山西":0,"广东":0,"广西":0,"新疆":0,"吉林":0, \
    "四川":0,"台湾":0,"江苏":0,"江西":0,"河北":0,"河南":0,"浙江":0,"海南":0,"湖北":0,"澳门":0,"甘肃":0,"福建":0,"西藏":0,"贵州":0,"辽宁":0,"重庆":0,"陕西":0,"青海":0,"香港":0,"黑龙江":0,"湖南":0,"未知":0}

    score_total=0
    sum_emotion=0
    time_count_t=0
    time_trend={}
    user_terminal={'微博网页版':0,'iphone客户端':0,'Android客户端':0,'其他':0}
    #fetch的结果是元组，要取其下标

    for i in range(len(weibos)):
        mid=weibos[i][0]
        uid=weibos[i][1]
        source=weibos[i][2]
        time_point=weibos[i][3]

        #统计用户终端比例
        source=unicode(source)
        #字符串查找str.find(),找不到返回-1
        if source.find('weibo.com')!=-1 or source.find(u'浏览器')!=-1:
            user_terminal['微博网页版']+=1
        elif source.find('iphon')!=-1:
            user_terminal['iphone客户端']+=1
        elif source.find('Android')!=-1 or source.find(u'三星')!=-1 or source.find(u'米')!=-1 or source.find('OPPO')!=-1:
            user_terminal['Android客户端']+=1
        else:
            user_terminal['其他']+=1

        #情感值统计
        sql5="select `score` from `analyse_result` where `mid`=%s" %(mid)

        res5=cur1.execute(sql5)
        if res5!=0:
            emotion=cur1.fetchone()[0]
            emotion=(float)(unicode(emotion))
            if emotion>0:
                count_positive+=1
            elif emotion<0:
                count_negative+=1
            else:
                count_neutral+=1

            #print "emotion is "+str(emotion)
            score_total+=1
            sum_emotion+=emotion*50
            ave_emotion=sum_emotion/score_total
            #print "The ave score is"+str(ave_emotion)

        #情感值时间趋势
        #print "The time of the weibo is "+unicode(time_point)
        if time_point!=u'NULLS':
            time_count_t+=1
            #time_point=unicode(time_point)[0:10]
            time_point=time_point.strip().split(":")[0]
            time_point=time_point.replace('-','/')
            time_point=time_point.replace(' ','-')
            if time_trend.has_key(time_point):
                time_trend[time_point][0]+=1
            else:
                time_trend[time_point]=[1,0,0,0]
            if emotion<0:
                time_trend[time_point][1]+=1
            elif emotion>0:
                time_trend[time_point][3]+=1
            else:
                time_trend[time_point][2]+=1


            #print time_trend[time_point]


        #找到mid对应的uid,统计user表的男女比例，省份，认证信息，该条微博的情感值去analyse_result中取
        sql4="select * from `userinfo` where `uid`='%s'" %uid
        res=cur1.execute(sql4)
        userinfo=cur1.fetchone()
        if userinfo==None:
            continue
        gender=userinfo[5]
        province=userinfo[3]

        if gender==u'男':
            count_male_num+=1
        elif gender==u'女':
            count_female_num+=1
        else:
            count_null_gender+=1


        #print "The province is "+province

        for (k,v) in province_info.items():

            if k.decode("utf8")==unicode(province):
                province_info[k]+=1
                break

        #年龄统计
        birthday=userinfo[8]
        if birthday!=u'NULLS':
            #print birthday
            birthday=unicode(birthday)
            if birthday.find(u'座')==-1 and birthday.find(u'年')!=-1:
                if (int)(birthday[0:4])<=1980:
                    count_before_70+=1
                elif (int)(birthday[0:4])<=1990:
                    count_80+=1
                else:
                    count_90+=1
            else:
                count_null_age+=1

        else:
            count_null_age+=1

        level=userinfo[29]
        vip=userinfo[30]

        if level!=u'NULLS':

            level=str(level)[3:]
            #print level
            if level>15:
                count_highlev+=1
        if vip!=u'NULLS':
            count_vip+=1
        if level!=u'NULLS' and vip!=u'NULLS':
            count_lev_vip+=1

        if level==u'NULLS' and vip==u'NULLS':
            count_ordinary+=1
        #取userinfo表的levelinfo和memberinfo(会员，默认NULLS)
        #print gender
        #print province,level,vip



    #写入结果数据库
    #用户终端信息
    terminal1=u"微博网页版"
    terminal_result1="insert into `result_terminal` values('%s','%s','%s','%s')" %(terminal1,topic,topic_id,unicode(user_terminal['微博网页版']))
    terminal2=u"iphone客户端"
    terminal_result2="insert into `result_terminal` values('%s','%s','%s','%s')" %(terminal2,topic,topic_id,unicode(user_terminal['iphone客户端']))
    terminal3=u"Android客户端"
    terminal_result3="insert into `result_terminal` values('%s','%s','%s','%s')" %(terminal3,topic,topic_id,unicode(user_terminal['Android客户端']))
    terminal4=u"其他"
    terminal_result4="insert into `result_terminal` values('%s','%s','%s','%s')" %(terminal4,topic,topic_id,unicode(user_terminal['其他']))

    #用户基本信息

    base_result="insert into `base_info`(`emotion_score`,`count_0`,`count_1`,`count_2`,`count_g_female`,`count_g_male`,`count_g_x`,\
        `count_a_before_70`,`count_a_70`,`count_a_80`,`count_a_90`,`count_a_x`,`count_u_v`,`count_u_c`,`count_u_o`,`count_u_c_v`,`topic_id`)\
         values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')" %(unicode(ave_emotion),unicode(count_negative),\
        unicode(count_neutral),unicode(count_positive),unicode(count_female_num),unicode(count_male_num),unicode(count_null_gender),\
        unicode(count_before_70),unicode(count_70),unicode(count_80),unicode(count_90),unicode(count_null_age),unicode(count_vip),unicode(count_highlev),\
            unicode(count_ordinary),unicode(count_lev_vip),topic_id)

    #用户地域分布
    # topic_id=unicode(topic_id)
    for (k,v) in province_info.items():
        province_result="insert into `result_province_info`(`province_name`,`count`,`topic_id`) values('%s','%s','%s')" %(k.decode('utf8'),unicode(v),topic_id)
        cur2.execute(province_result)

    #情感值时间趋势
    for (k,v) in time_trend.items():
        trend_result="insert into `result_tendency_info` values('%s','%s','%s','%s','%s','%s')" %(k.decode('utf8'),unicode(v[0]),unicode(v[1]),unicode(v[2])\
                                                                    ,unicode(v[3]),topic_id)
        cur2.execute(trend_result)


    #微博内容
    content_result="insert into `result_weibo` values('%s','%s','%s')" %(weibo_content,topic_id,topic)
    cur2.execute(content_result)


    cur2.execute(base_result)

    cur2.execute(terminal_result1)
    cur2.execute(terminal_result2)
    cur2.execute(terminal_result3)
    cur2.execute(terminal_result4)

    conn2.commit()



if __name__=="__main__":
    topic_ids=[2,3,4,5,6,7,8,9,10]
    for i in topic_ids:
        topic_process(i)
    #topic_process(2)
    cur1.close()
    conn1.close()
    cur2.close()
    conn2.close()
