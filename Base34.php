<?php
/**
 * User: kevin
 * 根据用户id生成6位唯一邀请码
 * 无须查询数据库，与id可互换，性能计算优
 */
namespace app\monitor\controller;

use app\common\exception\Http;
use cmf\controller\AdminBaseController;
use think\Loader;

class Base34
{
    private $baseStr  = "0123456789abcdefghjklmnpqrstuvwxyz";
    private $basearr = array();
    private $baseMap = array();

    public function index(){
        $this->basearr = $this->getBytes($this->baseStr);
        $this->InitBaseMap();
        $str1 = $this->Base34(24);
        $str2 = $this->Base34(200441052);
        $str3 = $this->Base34(1544804416);
        echo "result $str1 <br/>";
        echo "result $str2 <br/>";
        echo "result $str3 <br/>";
        echo "================ <br/>";
        $num = $this->Base34ToNum("00000q");
        echo "num $num<br/>";
        $num2 = $this->Base34ToNum("4dzrx2");
        echo "numa $num2<br/>";
    }

    /** 
         
     * 转换一个String字符串为byte数组 
         
     * @param $str 需要转换的字符串 
         
     * @param $bytes 目标byte数组 
         
     * @author 
         
     */
    public function getBytes($str)
    {
        $len = strlen($str);
        $bytes = array();
        for ($i = 0; $i < $len; $i++) {
            if (ord($str[$i]) >= 128) {
                $byte = ord($str[$i]) - 256;
            } else {
                $byte = ord($str[$i]);
            }
            $bytes[] = $byte;
        }
        return $bytes;
    }

    /**
     * 将字节数组转化为string类型的数据
     * @param $bytes 字节数组
     * @param $str 目标字符串
     * @return 一个string类型的数据
     */
    public  function tostr($bytes) {
        $str = '';
        foreach($bytes as $ch) {
            $str .= chr($ch);
        }
        return $str;
    }

    //初始化基础的地图
    public function InitBaseMap()
    {
        foreach ($this->basearr as $key => $value) {
           // echo "Key: $key; Value: $value<br />\n";
            $this->baseMap[$value] = $key;
        }
       /*
        echo "-------------------------<br/>";
        foreach ($this->baseMap as $key => $value) {
            echo "Key: $key; Value: $value<br />\n";
        }*/
    }

    //34位转化
    public function Base34($n)
    {
        $quotient = $n;
        $mod = 0;
        $l = array();
        while((int)$quotient!=0)
        {
            $mod = $quotient%34;
            $quotient = $quotient/34;
            echo "quotient: $quotient;mod:$mod;<br />\n";
            array_push($l,$this->basearr[(int)$mod]);
        }
        $listLen = count($l);
        echo $listLen;
        echo "<br/>";
        foreach ($l as $key => $value) {
            echo "Key: $key; Value: $value<br />\n";
        }
        $res = array();
        if($listLen >=6)
        {
            for ($x=0; $x<$listLen; $x++) {
                $res[$x] = $l[$x];
            }
            $res = array_reverse($res);
            return $this->tostr($res);
        }else{
            for ($x=0; $x<6; $x++) {
               if($x < 6-$listLen)
               {
                   $res[$x] = $this->basearr[0];
               }else
               {
                   $res[$x] =  $l[$listLen-1];
                   array_splice($l,$listLen-1,1);
				   $listLen = count($l);
               }
            }
            return $this->tostr($res);
        }
    }

    //转化为id
    public function Base34ToNum($str)
    {
        if($this->baseMap ==null)
        {
            return 0;
        }
        if($str == null || strlen($str)==0)
        {
            return 0;
        }

        echo  "start tonum1<br/>";

        $res = 0;
        $r = 0;
        for ($x=strlen($str) - 1; $x >= 0; $x--) {
            if (ord($str[$x]) >= 128) {
                $byte = ord($str[$x]) - 256;
            } else {
                $byte = ord($str[$x]);
            }

            echo  "byte $byte<br/>";
            $v = $this->baseMap[$byte];
            echo  "value $v<br/>";
            if($v==null&&$v!=0)
            {
                echo  "start tonum2<br/>";
                return 0;
            }
            $b = 1;
            for ($y=0; $y<$r; $y++)
            {
                $b *= 34;
            }
            $res += $b*(int)$v;
            $r++;
        }
        return $res;
    }


}