<?php
// Set the time limit for php to 0 seconds
set_time_limit(0);
$worker_id = isset($argv[1]) ? $argv[1] : getmypid();

require 'Autoloader.php';
Loader\Autoloader::register();

$logger = new Logger(Config::getWorkerLogFile(),Config::$wokerLogLevel,Config::$workerDebugLevel);

$logger->service("Worker [$worker_id] Starting...");
$logger->log("Connected to Redis Server");

$predis = new Predis\Client(array(
    'scheme' => 'tcp',
    'host'   => Config::REDIS_HOST,
    'port'   => Config::REDIS_PORT,
));

$predis->hset('worker.status', $worker_id, 'Started');
$predis->hset('worker.status.last_time', $worker_id, time());

$logger->log('Waiting for a Job .');
while(true)
{
    // Setting the Worker's Status
    $predis->hset('worker.status', $worker_id, 'Waiting');
    $predis->hset('worker.status.last_time', $worker_id, time());
    $job = $predis->blpop('queue.priority.high'
        , 'queue.priority.normal'
        , 'queue.priority.low'
        , 10);

    // If a job was pulled out
    if($job)
    {
        /*
         * Start Working
         *
         * This is where you will use the data from the
         */
        // Setting the Worker's Status
        $predis->hset('worker.status', $worker_id, 'Working');
        $predis->hset('worker.status.last_time', $worker_id, time());

        $queue_name = $job[0]; // 0 is the name of the queue
        $details = json_decode($job[1]); // parse the json data from the job

    }
    else
    {

    }
}

// Setting the Worker's Status
$predis->hset('worker.status', $worker_id, 'Closed');
$predis->hset('worker.status.last_time', $worker_id, time());
$logger->service("Worker [$worker_id] Finished!");

