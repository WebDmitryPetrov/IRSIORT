<?php

require_once 'AbstractDomWriter.php';

class TestLevelWriter extends AbstractDomWriter
{

    protected $rootElementName = 'types';
    /**
     * @var
     */
    private $levels;
    /**
     * @var
     */
    private $types;

    public function __construct($levels, $types)
    {
        parent::__construct();
        $this->levels = $levels;
        $this->types = $types;
    }


    public function makeXml()
    {

        $types = $this->getRootElement();
        foreach ($this->types as $key => $item) {
            $level = $this->addChild($types, 'type');

            $level->setAttribute('id', $item['id']);


            $this->addChild($level, 'caption', $item['caption']);

            $levels = $this->addChild($level, 'levels');
            foreach ($this->levels as $kl => $levelItem) {
                if ($levelItem['type_id'] != $item['id']) {
                    continue;
                }
                $level = $this->addChild($levels, 'level');

                $level->setAttribute('id', $levelItem['id']);
//                $level->setAttribute('type', $levelItem['type_id']);

                $this->addChild($level, 'caption', $levelItem['caption']);
                $this->addChild($level, 'price', $levelItem['price']);
                $this->addChild($level, 'price_retry', $levelItem['sub_test_price']);
                $subtests = $this->addChild($level, 'sub_tests');
                for ($i = 1; $i <= $levelItem['subtest_count']; $i++) {
                    $sub = $this->addChild($subtests, 'sub_test');
                    $sub->setAttribute('num', $i);
                    $this->addChild($sub, 'caption', $levelItem['test_' . $i . '_caption']);
                    $this->addChild($sub, 'maxball', $levelItem['test_' . $i . '_maxball']);
                }

            }

        }


        return $this->xml->saveXML();
    }
}