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
        echo json_encode($this->response);
        
    }
}
