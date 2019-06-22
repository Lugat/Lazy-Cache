<?php

  /**
   * Helper
   * 
   * @package Jinx\LazyCache
   * @copyright Copyright (c) 2019 SquareFlower Websolutions
   * @license GPL2+
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.1.0
   * @since 0.2.0
   */

  namespace Jinx\LazyCache;
  
  abstract class Helper
  {
    
    /**
     * Explode content and remove empty elements
     * 
     * @param string $string
     * @param string $delimiter
     * @return array
     */
    public static function explode(string $string, string $delimiter) : array
    {
      
      $array = explode($delimiter, $string);
      $array = array_map('trim', $array);
      
      return array_filter($array);
      
    }
    
    /**
     * Capture the output
     * 
     * @param type $callback
     * @return type
     */
    public static function captureOutput(callable $callback) : string
    {

      $params = array_slice(func_get_args(), 1);

      ob_start();
      ob_implicit_flush();

      call_user_func_array($callback, $params);

      return ob_get_clean();
      
    }
    
    /**
     * Minify the HTML
     * 
     * @param string $html
     * @return string
     */
    public static function minifyHtml(string $html) : string
    {
      
      $search = [
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
      ];

      $replace = [
        '>',
        '<',
        '\\1',
        ''
      ];

      return trim(preg_replace($search, $replace, $html));

    }
    
  }