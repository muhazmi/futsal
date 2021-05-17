<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function judul_seo($string)
{
 $c = array (' ');
 $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');
 $string = str_replace($d, '', $string); // Hilangkan karakter yang telah disebutkan di array $d
 $string = strtolower(str_replace($c, '-', $string)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
 return $string;
}