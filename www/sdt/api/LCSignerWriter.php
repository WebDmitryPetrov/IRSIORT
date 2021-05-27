<?php

require_once 'AbstractDomWriter.php';

class LCSignerWriter extends AbstractDomWriter
{
 use ConvertTrait;
    protected $rootElementName = 'signers';
    /**
     * @var CenterSigning[]
     */
    private $list;


    public function __construct($list)
    {
        parent::__construct();


        $this->list = $list;
    }


    public function makeXml()
    {

        $types = $this->getRootElement();
        foreach ($this->list as $key => $item) {
            $level = $this->addChild($types, 'type');

//            $level->setAttribute('id', $item['id']);


            $this->addChild($level, 'caption', $this->encode($item->getPrint()));
            $this->addChild($level, 'type', $this->convertType($item->type));


        }


        return $this->xml->saveXML();
    }

    private function convertType($type)
    {
        if ($type == 'approve') {
            return 'official';
        }

        if ($type == 'responsive') {
            return 'responsible';
        }
        return $type;
    }
}