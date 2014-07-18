<?php
class JsonFormatter {
    public static function format($value) {
        $data = json_decode($value, true);
        if(!$data)
            return NULL;
            
        return substr(self::output($data), 0, -2); //去掉末尾的",\n"
    }
    
    private static function output($data, $counter = 0) {
        if(!is_array($data)) {
            if(is_string($data)) {
                return "\"$data\",\n";
            } else if(is_bool($data)) {
                if($data)
                    return "true,\n";
                else
                    return "false,\n";
            }else if (is_int($data)) {
            	return "$data,\n";
            } else if (is_float($data)) {
            	return "$data,\n";
            } 
            else {
                return "\"$data\",\n";
            }
        }

        $counter += 4; //空格数
        $result = "{\n";  //输出结果
        $flag = self::isNormalArray($data);
        if($flag) {
        	$result = "[\n";  //输出结果
        }
        if(empty($data)) {
            $result = $result . ",";
        } else if(array_diff_key($data, array_values($data))) { //如果是关联数组，需要输出键和值
            foreach($data as $k => $v) {
                $result = $result . self::space($counter) . "\"$k\":" . self::output($v, $counter);
            }
            
        } else { //如果是简单数组，直接输出值
            foreach($data as $v) {
                $result = $result . self::space($counter) . self::output($v, $counter);
            }
        }
        
        //为了去掉最后一个元素末尾的逗号
        if($flag) {
        	return substr($result, 0, -2) . "\n" . self::space($counter - 4). "],\n";
        }
        
        return substr($result, 0, -2) . "\n" . self::space($counter - 4). "},\n";
    }
    
    private static function space($counter) {
        $result = "";
        for($i = 0; $i < $counter; $i++) {
            $result .= " ";
        }
        return $result;
    }
    
	private static function isNormalArray($value) {
		return $value == array_values($value) ? true : false;
	}
}
?>
