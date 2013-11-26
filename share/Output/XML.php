<?php

namespace Output;
use Base\Exception;
use Base\Output;
use XMLWriter;

/**
 * XML 
 */

class XML extends Output
{
    protected $mime_type = 'application/xml';
    protected $xml;
    
    public function populateOutput($data)
    {
    	$this->response = $this->_generateXML($data);
        return $this;
    }


    public function executeOutput()
    {
         
        echo $this->getDocument(); 
    
    }

 	public function getDocument()
 	{ 
        $this->xml->endElement(); 
        $this->xml->endDocument(); 
        return $this->xml->outputMemory(); 
    }
    
    protected function _generateXML($data)
    {
    	$this->xml = new XMLWriter();
    	$this->xml->openMemory(); 
        $this->xml->setIndent(true); 
        $this->xml->setIndentString(' '); 
        $this->xml->startDocument('1.0', 'UTF-8');
        if (is_array($data))
        {
        	$this->_fromArray($data);
        }
	}
	
	public function setElement($prm_elementName, $prm_ElementText)
	{ 
        $this->xml->startElement($prm_elementName); 
        $this->xml->text($prm_ElementText); 
        $this->xml->endElement(); 
    } 
    
	protected function _fromArray($prm_array)
	{
        foreach ($prm_array as $index => $element)
        { 
        	  if(is_array($element))
        	  { 
        	  		if (!is_numeric($index))
        	  		{
            			$this->xml->startElement($index);
            			$this->_fromArray($element); 
            			$this->xml->endElement(); 
        	  		}else
        	  		{
        	  			$this->_fromArray($element);
        	  		}
            		
          	  } 
			  else 
            		$this->setElement($index, $element); 
          
        } 
    } 
}

