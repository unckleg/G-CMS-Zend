<?php

class ThemeUrl extends Zend_View_Helper_Abstract
{
    /**
     * ThemeUrl
     *
     * @var string
     */
    protected $_themeUrl;
    
    /**
     * Returns site's theme url, or file with theme url prepended
     *`
     * $file is appended to the theme url for simplicity
     *
     * @param  string|null $file
     * @return string
     */
    public function themeUrl($file = null)
    {        
        // Get themeUrl
        $themeUrl = $this->getThemeUrl();

        // Remove trailing slashes
        if (null !== $file) {
            $file = '/' . ltrim($file, '/\\');
        }

        return $themeUrl . $file;
    }

    /**
     * Set ThemeUrl
     *
     * @param  string $file
     * @return UltimateCMS_Helpers_ThemeUrl
     */
    public function setThemeUrl($file)
    {
        $themeRegistry = Zend_Registry::get('theme');
        $this->_themeUrl = rtrim('/themes/' . $themeRegistry->theme_folder . '/' . $file, '/\\');
        return $this;
    }

    /**
     * Get ThemeUrl
     *
     * @return string
     */
    public function getThemeUrl()
    {
        if ($this->_themeUrl === null) {
            
            $themeUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

            // Remove scriptname, eg. index.php from themeUrl
            $themeUrl = $this->_removeScriptName($themeUrl);

            $this->setThemeUrl($themeUrl);
        }

        return $this->_themeUrl;
    }

    /**
     * Remove Script filename from themeurl
     *
     * @param  string $url
     * @return string
     */
    protected function _removeScriptName($url)
    {
        if (!isset($_SERVER['SCRIPT_NAME'])) {
            // We can't do much now can we? 
            // Well, we could parse out by "."
            return $url;
        }

        if (($pos = strripos($url, basename($_SERVER['SCRIPT_NAME']))) !== false) {
            $url = substr($url, 0, $pos);
        }

        return $url;
    }
}
