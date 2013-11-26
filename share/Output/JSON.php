<?php
namespace Output;
use Base\Exception;
use Base\Output;
/**
 * JSON
 */
class JSON extends Output
{
    
    protected $mime_type = 'application/json';

    public function populateOutput($response)
    {
        $this->response = $response;
        return $this;
    }

    public function executeOutput()
    {
        $returnedData = json_encode($this->response);
        return $returnedData;
    }
}
