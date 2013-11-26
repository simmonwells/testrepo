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
        return file_get_contents("../templates/404.html");
    }
}
