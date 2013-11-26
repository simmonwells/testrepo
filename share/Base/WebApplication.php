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
		//var_dump($this);
	}
	
	public function getRequest()
	{
		return $this->request;	
	}
	
	public function processError(Exception $e)
	{
		$this->output = Output::getInstance($this->request->getOutputFormat())->populateOutput($e->toArray())
               ->sendHeaders($e)
               ->executeOutput();
		return $this->output;
	}

}