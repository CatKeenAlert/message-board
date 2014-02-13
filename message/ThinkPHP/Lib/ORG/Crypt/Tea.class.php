<?php
/**
 * tea 加解密
 * Enter description here ...
 * @author gllin
 *
 */
class Tea
{
    public function encrypt($strPlain, $strKey)
    {
    	// 密文转换成十六进制
    	$byarrPlain = Array();
    	$byarrPlainHex = Array();
    	preg_match_all('/(.)/s',$strPlain,$bytes);  //注：本文的盗版已经有了。不过，提示一下读者，这里的正则改了。  
    	$byarrPlainHex=array_map('ord',$bytes[1]) ; 
      	
	    // 密钥转换成十六进制
	    $byarrKey = Array();
	    $byarrKeyHex = Array();
	    $byarrKey = str_split($strKey, 1);
	    $key_len = count($byarrKey);
    	for($i = 0; $i < $key_len / 2; $i++)
	    {
	    	$byarrKeyHex[] = hexdec($byarrKey[$i * 2]) * 16 + hexdec($byarrKey[$i * 2 + 1]);
	    }
	    
	    // xor运算解密
	    $iPlainCount = count($byarrPlainHex);
    	$iKeyCount = count($byarrKeyHex);
    	$byarrCipher = Array();
	    for ($i = 0; $i < $iPlainCount; $i++)
    	{
    		$byarrCipher[] = ($byarrPlainHex[$i] ^ $byarrKeyHex[$i % $iKeyCount]);
    	}
    	
    	$str = $this->byteArray2StringHex($byarrCipher); 
        
        return $str;
    }
	
	public  function byteArray2StringHex($byteArray) 
    {
	$str = "";
	$count = count($byteArray);
        for ($i = 0; $i < $count; $i++) 
        {
            if (($byteArray[$i] & 0xff) < 0x10) 
            {
                // 如果bytes[i]补码的低8位小于 16 buf添加0
                
                $str .= "0";
            }
            // bytes[i] 的低8位，换算成16进制数，添加到buf
            $str .= dechex($byteArray[$i] & 0xff);
        }
        return $str;
    }
    
	public function decrypt($strCipher, $strKey)
    {
    	// 密文转换成十六进制
    	$byarrCipher = Array();
      	$byarrCipherHex = Array();
      	$byarrCipher = str_split($strCipher, 1);
     	$cipher_len = count($byarrCipher);
	    for($i = 0; $i < $cipher_len / 2; $i++)
	    {
	    	$byarrCipherHex[] = hexdec($byarrCipher[$i * 2]) * 16 + hexdec($byarrCipher[$i * 2 + 1]);
	    }
        
	    // 密钥转换成十六进制
	    $byarrKey = Array();
	    $byarrKeyHex = Array();
	    $byarrKey = str_split($strKey, 1);
	    $key_len = count($byarrKey);
    	for($i = 0; $i < $key_len / 2; $i++)
	    {
	    	$byarrKeyHex[] = hexdec($byarrKey[$i * 2]) * 16 + hexdec($byarrKey[$i * 2 + 1]);
	    }
	    
	    // xor运算解密
	    $iCipherCount = count($byarrCipherHex);
    	$iKeyCount = count($byarrKeyHex);
    	$byarrPlain = Array();
	    for ($i = 0; $i < $iCipherCount; $i++)
    	{
    		$byarrPlain[] = ($byarrCipherHex[$i] ^ $byarrKeyHex[$i % $iKeyCount]);
    	}
    	
        foreach($byarrPlain as $ch) 
        {
            $str .= chr($ch);
        }
        
        return $str;
    }
}
?>