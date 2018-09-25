<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
		echo "Initial: ".memory_get_usage()." bytes \n";
		/* 输出
		Initial: 361400 bytes
		*/
		// 使用内存
		for ($i = 0; $i < 100000; $i++) {
		$array []= md5($i);
		}
		// 删除一半的内存
		for ($i = 0; $i < 100000; $i++) {
		unset($array[$i]);
		}
		echo "Final: ".memory_get_usage()." bytes \n";
		/* prints
		Final: 885912 bytes
		*/
		echo "Peak: ".memory_get_peak_usage()." bytes \n";
		/* 输出峰值
		Peak: 13687072 bytes
		*/
	}
}
