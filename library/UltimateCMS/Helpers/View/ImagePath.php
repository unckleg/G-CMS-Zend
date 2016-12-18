<?php

class ImagePath extends Zend_View_Helper_Abstract
{
    public function image()
    {
        return $this;
    }

    public function imagePath($path, $dimension = "l")
    {
        $baseUrl = Zend_Registry::get('config')->basesiteurl;

        $path_info = pathinfo($path);
        $extension = $path_info['extension'];
        return ($baseUrl . '/'.(substr($path, 0, -(strlen($extension)+1))."-".$dimension.".".$extension));
    }
}