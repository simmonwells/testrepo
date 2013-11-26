<?php

  namespace Base;
    
  class WebApplication
  {
      	protected $request;
	
	protected $config;
	
	protected $output ="";
	
	public function __construct()
	{
        	$this->request = new Request();
	}
	//get config
	public function getConfig()
	{
		return $this->config;
	}
	
	public function processOutput()
	{
		$this->getRequest()->checkRequest();
		
	}
	
	public function getRequest()
	{
		return $this->request;	
	}
	
	public function processError(Exception $e)
	{
		$this->output = Output::getInstance("error")->populateOutput($e->toArray())
               ->sendHeaders($e)
               ->executeOutput();
		return $this->output;
	}

}