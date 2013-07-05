<?php
namespace Neilime\BrowscapTest\Console;
class ConsoleAdapter extends \Zend\Console\Adapter\AbstractAdapter{
	public $stream;

    public $autoRewind = true;

    /**
     * Read a single line from the console input
     * @param int $maxLength : maximum response length
     * @return string
     */
    public function readLine($maxLength = 2048){
        if($this->autoRewind)rewind($this->stream);
        return rtrim(stream_get_line($this->stream, $maxLength, PHP_EOL),"\n\r");
    }

    /**
     * Read a single character from the console input
     *
     * @param string|null   $mask   A list of allowed chars
     * @return string
     */
    public function readChar($mask = null){
        if($this->autoRewind)rewind($this->stream);
        do{
            $char = fread($this->stream, 1);
        } while ("" === $char || ($mask !== null && false === strstr($mask, $char)));
        return $char;
    }
}