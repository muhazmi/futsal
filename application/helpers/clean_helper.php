<?php
function clean($string)
{
 $c       = array (',','.');
 $string  = str_replace($c, '', $string); // Hilangkan karakter yang telah disebutkan di array $c
 return $string;
}
//
// function clean($string)
// {
//  $c = array ('');
//  $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');
//  $string = str_replace($d, '', $string); // Hilangkan karakter yang telah disebutkan di array $d
//  $string = str_replace($c, '', $string); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
//  return $string;
// }

function clean2($string)
{
 $c       = array (',','-');
 $string  = str_replace($c, ' ', $string); // Hilangkan karakter yang telah disebutkan di array $c
 return $string;
}
?>
