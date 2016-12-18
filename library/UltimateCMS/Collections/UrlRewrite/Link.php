<?php
class UltimateCMS_Collections_UrlRewrite_Link extends Zend_Controller_Plugin_Abstract {
	
    function clear ($string)
    {
        $link = $string;
        $link = str_replace('š', 's', $link);
        $link = str_replace('đ', 'dj', $link);
        $link = str_replace('č', 'c', $link);
        $link = str_replace('ć', 'c', $link);
        $link = str_replace('ž', 'z', $link);
        $link = str_replace('Š', 's', $link);
        $link = str_replace('Đ', 'dj', $link);
        $link = str_replace('Č', 'c', $link);
        $link = str_replace('Ć', 'c', $link);
        $link = str_replace('Ž', 'z', $link);

        $link = str_replace('а', 'a', $link);
        $link = str_replace('б', 'b', $link);
        $link = str_replace('в', 'v', $link);
        $link = str_replace('г', 'g', $link);
        $link = str_replace('д', 'd', $link);
        $link = str_replace('ђ', 'dj', $link);
        $link = str_replace('е', 'e', $link);
        $link = str_replace('ж', 'z', $link);
        $link = str_replace('з', 'z', $link);
        $link = str_replace('и', 'i', $link);
        $link = str_replace('ј', 'j', $link);
        $link = str_replace('к', 'k', $link);
        $link = str_replace('л', 'l', $link);
        $link = str_replace('љ', 'lj', $link);
        $link = str_replace('м', 'm', $link);
        $link = str_replace('н', 'n', $link);
        $link = str_replace('њ', 'nj', $link);
        $link = str_replace('о', 'o', $link);
        $link = str_replace('п', 'p', $link);
        $link = str_replace('р', 'r', $link);
        $link = str_replace('с', 's', $link);
        $link = str_replace('т', 't', $link);
        $link = str_replace('ћ', 'c', $link);
        $link = str_replace('у', 'u', $link);
        $link = str_replace('ф', 'f', $link);
        $link = str_replace('х', 'h', $link);
        $link = str_replace('ц', 'c', $link);
        $link = str_replace('ч', 'c', $link);
        $link = str_replace('џ', 'dz', $link);
        $link = str_replace('ш', 's', $link);

        $link = str_replace('А', 'a', $link);
        $link = str_replace('Б', 'b', $link);
        $link = str_replace('В', 'v', $link);
        $link = str_replace('Г', 'g', $link);
        $link = str_replace('Д', 'd', $link);
        $link = str_replace('Ђ', 'dj', $link);
        $link = str_replace('Е', 'e', $link);
        $link = str_replace('Ж', 'z', $link);
        $link = str_replace('З', 'z', $link);
        $link = str_replace('И', 'i', $link);
        $link = str_replace('Ј', 'j', $link);
        $link = str_replace('К', 'k', $link);
        $link = str_replace('Л', 'l', $link);
        $link = str_replace('Љ', 'lj', $link);
        $link = str_replace('М', 'm', $link);
        $link = str_replace('Н', 'n', $link);
        $link = str_replace('Њ', 'nj', $link);
        $link = str_replace('О', 'o', $link);
        $link = str_replace('П', 'p', $link);
        $link = str_replace('Р', 'r', $link);
        $link = str_replace('С', 's', $link);
        $link = str_replace('Т', 't', $link);
        $link = str_replace('Ћ', 'c', $link);
        $link = str_replace('У', 'u', $link);
        $link = str_replace('Ф', 'f', $link);
        $link = str_replace('Х', 'h', $link);
        $link = str_replace('Ц', 'c', $link);
        $link = str_replace('Ч', 'c', $link);
        $link = str_replace('Џ', 'dz', $link);
        $link = str_replace('Ш', 's', $link);

        $link = strtolower($link);

        $link = $this->cleanURL($link);
        $link = str_replace(' ', '-', $link);

        return strtolower($link);
    }
    
    public function cleanURL($string) 
    {
        $url = str_replace ( "'", '', $string );
        $url = str_replace ( '%20', ' ', $url );
        $url = preg_replace ( '~[^\\pL0-9_]+~u', '-', $url ); // substitutes anything but letters, numbers and '_' with separator
        $url = trim ( $url, "-" );
        $url = iconv ( "utf-8", "utf-8//TRANSLIT", $url ); // you may opt for your own custom character map for encoding.
        $url = strtolower ( $url );
        $url = preg_replace ( '~[^-a-z0-9_]+~', '', $url ); // keep only letters, numbers, '_' and separator
        
        return $url;
    }
 
}