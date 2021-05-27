<?php


class TextCDATA
{
    protected $text;

    function __construct($text = null)
    {
        if ($text) {
            $this->setText($text);
        }
    }


    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
}
