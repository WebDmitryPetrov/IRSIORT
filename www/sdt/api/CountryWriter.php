<?php

require_once 'AbstractDomWriter.php';

class CountryWriter extends AbstractDomWriter
{

    protected $rootElementName = 'countries';
    /**
     * @var
     */
    private $items;

    public function __construct($levels)
    {
        parent::__construct();
        $this->items = $levels;
    }


    public function makeXml()
    {
        foreach ($this->items as $key => $item) {
            $im = $this->addChild($this->getRootElement(), 'country', $item['name']);
            $im->setAttribute('id', $item['id']);
        }

        return $this->xml->saveXML();
    }
}