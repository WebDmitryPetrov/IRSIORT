<?php

require_once 'AbstractDomWriter.php';

class DogovorWriter extends AbstractDomWriter
{

    protected $rootElementName = 'contracts';

    /**
     * @var
     */
    private $dogovors;

    public function __construct($dogovors)
    {
        parent::__construct();

        $this->dogovors = $dogovors;
    }


    public function makeXml()
    {


        foreach ($this->dogovors as $key => $item) {
            $level = $this->addChild($this->getRootElement(), 'contract');

            $level->setAttribute('id', $item['id']);


            $this->addChild($level,'number',$item['number']);
            $this->addChild($level,'date',date('d.m.Y',strtotime($item['date'])));
            $this->addChild($level,'caption',$item['caption']);
//            die(var_dump($item));
            $this->addChild($level,'test_type',$item['type_id']);


        }



        return $this->xml->saveXML();
    }
}