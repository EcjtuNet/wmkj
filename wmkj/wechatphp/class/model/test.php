<?php
$class ="<table width="79%" height="20" border="1">
   <tr>
     <td width="8%" height="20"><div align="center"><font size="2">节次</font></div></td>
     <td width="13%"><div align="center"><font size="2">星期一</font></div></td>
     <td width="13%"><div align="center"><font size="2">星期二</font></div></td>
     <td width="13%"><div align="center"><font size="2">星期三</font></div></td>
     <td width="13%"><div align="center"><font size="2">星期四</font></div></td>
     <td width="13%"><div align="center"><font size="2">星期五</font></div></td>
     <td width="13%"><div align="center"><font size="2">星期六</font></div></td>
     <td width="13%"><div align="center"><font size="2">星期日</font></div></td>
   </tr>
     <tr><td><div align="center"><font size="2">1 - 2</font></div></td><td><div align="center"><font size="2">&nbsp;毛泽东思想和中国特色社会主义理论体系概论<br>邓凌云 15-215<br>1-16 1,2</font></div></td><td><div align="center"><font size="2">&nbsp;软件工程<br>魏波 15-202<br>1-16 1,2</font></div></td><td><div align="center"><font size="2">&nbsp;财务会计学<br>刘丽波 15-402<br>1-16 1,2</font></div></td><td><div align="center"><font size="2">&nbsp;Web应用设计基础（B）<br>占东明 15-321<br>1-13[单] 1,2<br>软件工程<br>魏波 15-321<br>1-16[双] 1,2</font></div></td><td><div align="center"><font size="2">&nbsp;毛泽东思想和中国特色社会主义理论体系概论<br>邓凌云 15-215<br>1-16 1,2</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td></tr><tr><td><div align="center"><font size="2">3 - 4</font></div></td><td><div align="center"><font size="2">&nbsp;财务会计学<br>刘丽波 15-302<br>1-16 3,4</font></div></td><td><div align="center"><font size="2">&nbsp;Web应用设计基础（B）<br>占东明 15-202<br>1-13 3,4</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;大学英语Ⅳ<br> <br>1-16 3,4</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td></tr><tr><td><div align="center"><font size="2">5 - 6</font></div></td><td><div align="center"><font size="2">&nbsp;大学英语Ⅳ<br> <br>1-16 5,6</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;数据库系统原理（B）<br>李黎青 15-220<br>1-16 5,6,7</font></div></td><td><div align="center"><font size="2">&nbsp;形势政策与省情教育II<br>张静（8162） 15-206<br>14-17 5,6,7</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td></tr><tr><td><div align="center"><font size="2">7 - 8</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;体育Ⅳ<br> <br>1-16 7,8</font></div></td><td><div align="center"><font size="2">&nbsp;数据库系统原理（B）<br>李黎青 15-220<br>1-16 5,6,7</font></div></td><td><div align="center"><font size="2">&nbsp;形势政策与省情教育II<br>张静（8162） 15-206<br>14-17 5,6,7</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td></tr><tr><td><div align="center"><font size="2">9 - 10</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td><td><div align="center"><font size="2">&nbsp;</font></div></td></tr>


 </table>";
if(date("w")==0) $week = 0;
	    else $week = date("w");
	  	$pattern="/<td><div align=\"center\"><font size=\"2\">(.*?)<\/font><\/div><\/td>/";
	  	preg_match_all($pattern, $class, $matches);
	  	if(preg_last_error() == PREG_NO_ERROR) 
	  	{
	  		var_dump($matches);
			for ($i=0; $i <40 ; $i++) { 
				$matches[1][$i]=str_replace("<br>", "\n", str_replace("&nbsp;"," ",$matches[1][$i]));
			}
			$k=0;
			$course = "您今天的课表如下：\n";
			for($i=$week;$i<40;$i+=8){
				if($matches[1][$i]==" ") $matches[1][$i]="该时段没有课哦，亲～";
				$course = $course."第".$matches[1][$k]."节课:\n".$matches[1][$i]."\n##############\n";
				$k=$k+8;
			}
	    		echo $course;
	  	}
	  	else 
	  	{
	  		if (preg_last_error() == PREG_NO_ERROR) {
			    $contentStr= 'There is no error.';
			}
			else if (preg_last_error() == PREG_INTERNAL_ERROR) {
			    $contentStr= 'There is an internal error!';
			}
			else if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
			    $contentStr= 'Backtrack limit was exhausted!';
			}
			else if (preg_last_error() == PREG_RECURSION_LIMIT_ERROR) {
			    $contentStr= 'Recursion limit was exhausted!';
			}
			else if (preg_last_error() == PREG_BAD_UTF8_ERROR) {
			    $contentStr= 'Bad UTF8 error!';
			}
			else if (preg_last_error() == PREG_BAD_UTF8_ERROR) {
			    $contentStr= 'Bad UTF8 offset error!';
			}
			
			
	  	}

