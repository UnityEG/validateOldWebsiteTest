<?php

return array(
    // set your paypal credential
    'client_id' => 'AZ_LTGAF2f9Vu8EicUsgrYdox7zyBWyYHw0hscrY0E1648qWAHDFPs3VaYxXshQN5jCgDposfdu8ldRD',
    'secret' => 'EPqLvOMy4sFtXRF6yBUf6Y6-4vBS8qSpXZw9zKAbu7rQHGy67CJXDS8uyj6yVbipO-U_F3cZbTRg9Edt',
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',
        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,
        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,
        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
?>