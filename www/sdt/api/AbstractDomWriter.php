<?php
require_once 'TextCDATA.php';

abstract class AbstractDomWriter
{
    /** @var \DomDocument */
    protected $xml;
    protected $rootElement;
    protected $rootElementName;

    protected $docType;

    public function __construct()
    {
        if ($this->docType) {
            $this->xml = $this->getDocTypeDocument();
        } else {
            $this->xml = new DomDocument('1.0', 'utf-8');
        }

        $this->xml->formatOutput = true;
        $this->rootElement = $this->xml->appendChild($this->xml->createElement($this->rootElementName));

    }

    /**
     * @return \DOMDocument
     */
    protected function getDocTypeDocument()
    {
        $implementation = new DOMImplementation();
        $this->docType['name']= !empty($this->docType['name'])? $this->docType['name']:null;
        $this->docType['public']= !empty($this->docType['public'])? $this->docType['public']:null;
        $this->docType['system']= !empty($this->docType['system'])? $this->docType['system']:null;


        $dtd = $implementation->createDocumentType(
            $this->docType['name'],
            $this->docType['public'],
            $this->docType['system']
        );

        return $implementation->createDocument('', '', $dtd);
    }

    abstract public function makeXml();

    /**
     * @return    \DOMNode|\DOMElement
     */
    protected function getRootElement()
    {
        return $this->rootElement;
    }


    /**
     * @param \DOMNode $root
     * @param $childName
     * @param null $childValue
     *
     * @return \DOMNode|\DOMElement
     */
    protected function  addChild(DOMNode $root, $childName, $childValue = null)
    {

        $result = $root->appendChild($this->xml->createElement($childName));
        if ($childValue) {
            if (!is_object($childValue)) {
                $result->appendChild($this->xml->createTextNode($childValue));
            } elseif ($childValue instanceof TextCDATA) {
                $result->appendChild($this->xml->createCDATASection($childValue->getText()));
            }


        }

        return $result;

    }


}
