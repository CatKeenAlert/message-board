<?php
/** 
 *     类名:   编码类
 *     描述:   关于编码
 */
class   Activation
{
	/**
	 *       函数名称:       genID
	 *       函数功能:       设置种子
	 *       输入参数:       
	 *       $size   --------------   要生成的数量
	 *       $length   -----------   长度
	 *       $mode   -------------   模式
	 *       函数返回值:   返回值说明
	 *       其它说明:       说明
	 */
	function   genID($size,$length,$mode,$prefix)
	{
		$code_array   =   array();
		$offset   =   1.5;//   为避免递归，采用取子集的办法
		$offsize   =   $size*$offset;
		for($count   =   0;   $count   <   $offsize;   $count++)
		{
			$code_array[]   =   $this-> _seed(   $length,   $mode  ,$prefix);
		}
		$unique_array   =   array_unique(   $code_array   );
		return   array_slice   ($unique_array,   0,$size);
	}

	/**
	 *       函数名称:       seed
	 *       函数功能:       设置种子
	 *       输入参数:       
	 *       $length   -----------   长度
	 *       $mode   -----------   模式
	 *       函数返回值:   返回值说明
	 *       其它说明:       说明
	 */
	function   _seed($length=10,$mode,$prefix)
	{
		switch(   $mode   )
		{
			case   '1 ':
				$str   =   '1234567890';
				break;
			case   '2 ':
				$str   =   'abcdefghijklmnopqrstuvwxyz';
				break;
			case   '3 ':
				$str   =   'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			case   '4 ':
				$str   =   'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				break;
			case   '5 ':
				$str   =   'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
				break;
			case   '6 ':
				$str   =   'abcdefghijklmnopqrstuvwxyz1234567890';
				break;
			default:
				$str   =   'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
				break;
		}

		$result   =   '';
		$seedlength   =   strlen($str)-1;
		for(   $i   =   0;   $i   <=   $length-1;   $i++   )
		{
			$num   =   mt_rand(   0,   $seedlength   );
			$result   .=   $str[$num];
		}
		return   $this->uuid($prefix,$result);
	}
	/**
	 *       函数名称:       uuid
	 *       函数功能:       设置种子
	 *       输入参数:       
	 *       $prefix   -----------   前缀
	 *       $chars   -----------   字符串
	 *       函数返回值:   返回值说明
	 *       其它说明:       说明
	 */
	function uuid($prefix = '',$chars)
	{
		//$chars = md5(uniqid(mt_rand(), true));
		$uuid  = substr($chars,0,5) . '-';
		$uuid .= substr($chars,5,5) . '-';
		$uuid .= substr($chars,10,5) . '-';
		$uuid .= substr($chars,15,5);
		//$uuid .= substr($chars,20,12);
		return $prefix . $uuid;  
	}  
	function uuid2($prefix = '',$chars)
	{
		//$chars = md5(uniqid(mt_rand(), true));
		$uuid  = substr($chars,0,8) . '-';
		$uuid .= substr($chars,8,4) . '-';
		$uuid .= substr($chars,12,4) . '-';
		$uuid .= substr($chars,16,4) . '-';
		$uuid .= substr($chars,20,12);
		return $prefix . $uuid;  
	} 	          
} 
?> 