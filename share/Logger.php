<?php


/**
 * Logger class.
 * Usefull to log notices, warnings, errors or fatal errors into a logfile.
 * @author gehaxelt
 * @version 1.1
 */
class Logger {

    private $logfilehandle=NULL;

    const NOTICE='NOTICE';
    const WARNING='WARNING';
    const ERROR='ERROR';
    const FATAL='FATAL';
    const INFO='INFO';
    const SERVICE='SERVICE';

    private $logLevel = array(
        'INFO' => 4 ,
        'NOTICE' => 3 ,
        'WARNING' => 2,
        'ERROR' => 1,
        'FATAL' => 0,
        'SERVICE'=>-1,
    );
    private $LogLevelColor = array(
        'INFO' => "\e[0;36m" ,
        'NOTICE' => "\e[0;34m" ,
        'WARNING' => "\e[0;31m",
        'ERROR' => "\e[1;31m",
        'FATAL' => "\e[0;33m",
        'SERVICE'=>"\e[1;37m",
    );
    private $LogLevelSet=4;
    private $debugMode= false;
    /**
     * Contructor of Logger.
     * Opens the new logfile
     * @param string $logfile is the path to a logfile
     */
    public function __construct($logfile,$logLevel='INFO',$debugMode=false) {
        if($this->logfilehandle==NULL)
            $this->openLogFile($logfile);
        if(!in_array($logLevel,array_keys($this->logLevel)))
        {
            $this->LogLevelSet = $this->logLevel['INFO'];
        }
        else
        {
            $this->LogLevelSet = $this->logLevel[$logLevel];
        }
        $this->debugMode = $debugMode;
    }
    /**
     * Destructor of Logger
     */
    public function __destruct() {
        $this->closeLogFile();
    }
    /**
     * Logs the message into the logfile.
     * @param string $message message to write into the logfile
     * @param int $messageType (optional) urgency of the messagee. Possible constants are: notice, warning, error, fatal. Default value: warning
     * @throws LogFileNotOpenException
     * @throws NotAStringException
     * @throws NotAIntegerException
     * @throws InvalidMessageTypeException
     */
    public function log($message,$messageType=Logger::INFO) {
        if($this->logfilehandle==NULL)
            throw new Exceptions\Logger\LogFileNotOpen('Logfile is not opened.');

        if(!is_string($message))
            throw new Exceptions\Logger\NotAString('$message is not a string');

        if(!in_array($messageType,array_keys($this->logLevel)))
            throw new Exceptions\Logger\InvalidMessageType('Wrong $messagetype given');
        if ($this->LogLevelSet >= $this->logLevel[$messageType]){
            $this->writeToLogFile("[$messageType][".$this->getTime()."]".$message);
            if ($this->debugMode)
            {
                echo $this->LogLevelColor[$messageType]."[$messageType][".$this->getTime()."]"
                    ."\e[0;32m". $message . "\n";
            }
        }
    }

    /**
     * Writes content to the logfile
     * @param string $message
     */
    private function writeToLogFile($message) {
        flock($this->logfilehandle,LOCK_EX);
        fwrite($this->logfilehandle,$message."\n");
        flock($this->logfilehandle,LOCK_UN);
    }

    /**
     * Returns the current timestamp in dd.mm.YYYY - HH:MM:SS format
     * @return string with the current date
     */
    private function getTime() {
        return date("d.m.Y-H:i:s");
    }

    /**
     * Closes the current logfile.
     */
    public function closeLogFile() {
        if($this->logfilehandle!=NULL) {
            fclose($this->logfilehandle);
            $this->logfilehandle=NULL;
        }
    }

    /**
     * Opens a given logfile and closes the old one before, if another logfile was opened before.
     * @param string $logfile is a path to a logfile
     * @throws LogFileOpenErrorException
     */
    public function openLogFile($logfile) {

        $this->closeLogFile(); //close old logfile if opened;
        $dir =  dirname($logfile);
        if (!is_dir($dir))
        {
            if (!mkdir($dir))
                throw new Exceptions\Logger\DirCreateError('Could not create dir');
        }
        $this->logfilehandle=@fopen($logfile,"a");

        if(!$this->logfilehandle)
            throw new Exceptions\Logger\LogFileOpenError('Could not open Logfile in append-mode');
    }

    /**
     * Convenience function to wrap logger->log($message,$messagetype);
     * @param string $message
     */
    public function notice($message) {
        $this->log($message,Logger::NOTICE);
    }

    public function info($message) {
        $this->log($message,Logger::INFO);
    }
    /**
     * Convenience function to wrap logger->log($message,$messagetype);
     * @param string $message
     */
    public function warn($message) {
        $this->log($message,Logger::WARNING);
    }

    /**
     * Convenience function to wrap logger->log($message,$messagetype);
     * @param string $message
     */
    public function error($message) {
        $this->log($message,Logger::ERROR);
    }

    /**
     * Convenience function to wrap logger->log($message,$messagetype);
     * @param string $message
     */
    public function fatal($message) {
        $this->log($message,Logger::FATAL);
    }

    public function service($message) {
        $this->log($message,Logger::SERVICE);
    }
}
?>