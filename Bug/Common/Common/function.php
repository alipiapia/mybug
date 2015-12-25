<?php

function p($arr){
	if(is_array($arr)){
		echo "<pre>";
		var_dump($arr);
		echo "</pre>";
	}else{
		echo $arr;
	}
	exit;
}