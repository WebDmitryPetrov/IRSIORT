<?php

namespace SDT\api;

require_once 'AbstractDomWriter.php';

class PfurXmlResult extends \AbstractDomWriter
{
    use \ConvertTrait;

    protected $rootElementName = 'act';
    /**
     * @var
     */
    private $items;
    private $id;

    public function __construct($levels, $id)
    {
        parent::__construct();
        $this->items = $levels;
        $this->id = $id;
    }


    public function makeXml()
    {
        $this->getRootElement()->setAttribute('id', $this->id);
        foreach ($this->items as $key => $item) {
            $im = $this->addChild($this->getRootElement(), 'man');
            if ($item['ext_id'])
                $im->setAttribute('ext_id', $this->utf_encode($item['ext_id']));
            $this->addChild($im, 'type', $item['document']);

            if ($item['document'] === \ActMan::DOCUMENT_CERTIFICATE) {
                $this->addChild($im, 'blank_number', $item['blank_number']);
                $this->addChild($im, 'issue_date', $item['blank_date']);
                $this->addChild($im, 'reg_number', $item['document_nomer']);
            }

            if ($item['document'] === \ActMan::DOCUMENT_NOTE) {
                $this->addChild($im, 'number', $this->utf_encode($item['blank_number']));

            }
        }

        return $this->xml->saveXML();
    }
}