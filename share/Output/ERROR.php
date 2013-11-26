<?php
namespace Output;
use Base\Exception;
use Base\Output;
/**
 * JSON
 */
class ERROR extends Output
{
    protected $mime_type = 'text/html';

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
