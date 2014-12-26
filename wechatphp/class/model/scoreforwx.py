#!/usr/bin/python
#-*-coding:utf-8-*-
import urllib2
import urllib
import cookielib
import re
import thread
import time
import json

cookie = cookielib.CookieJar()
opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cookie))


def GetPage(StudID):
    url = 'http://172.16.47.252:80/mis_o/login.php'
    mainurl = 'http://172.16.47.252:80/mis_o/query.php'
    logindata = urllib.urlencode({
        'user':'jwc',
        'pass':'jwc'
    })
    querydata = urllib.urlencode({
        'StuID':StudID,
        'Term' :2014.1
        })
    urlGet(url,logindata)
    try:
    	result = urlGet(mainurl,querydata)
    except:
    	print 'fail to connect the server'
    content = result.decode('gbk').encode('utf-8')
    return content
 
def reback(content):
	rs_obj = []
	parttern = '<td>(.*?)</td>'
	match = re.findall(parttern, content, re.S)
	del match[0]
	for i in range(0,len(match),9):
		partternNext = '<font color=red>(.*?)</font>'
		matchs = re.findall(partternNext, match[i+6], re.S)
		if len(matchs) != 0:
			match[i+6] = matchs[0]
		res = {'Course':match[i+3].decode('utf-8').encode('utf-8'),'Term':2014.1,'Score':match[i+6],'FirstScore':match[i+7],'SecondScore':match[i+8]}
		rs_obj.append(res)
	return json.dumps(rs_obj)
		
def urlGet(url,data):
	user_agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36'
	headers = { 
        'User-Agent' : user_agent,
        'Referer' : 'http://jwc.ecjtu.jx.cn/mis_o/main.php' 
    	}
	req = urllib2.Request(url, headers = headers, data = data)
	result = opener.open(req)
	upage = result.read()
	return upage

def start():
	studID = input()
	print reback(GetPage(studID))
start()
