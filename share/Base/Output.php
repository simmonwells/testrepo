<?php
namespace Base;
/**
 * Output class
 */
class Output
{
	
    protected $mime_type;
    
    public function sendHeaders($response)
    {
        header('HTTP/1.1 '.intval($response->getStatusCode()).' '.$response->getMessage());

        if ($response instanceof Response) {
            $content_type = $response->getContentType();
            if ($content_type) {
                $this->mimeType = $content_type;
            }
        }
        header('Content-type: '.$this->mime_type.'; charset=utf-8');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE,HEAD');
        
        return $this;
    }
	
    public function setMimeType($type)
    {
        $this->mime_type = $type;
    }
	
    public static function getInstance($type)
    {
    	$class = 'Output\\' . strtoupper($type);
    	$obj = new $class;
    	return $obj; 
    }
}
