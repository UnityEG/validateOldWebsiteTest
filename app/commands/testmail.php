<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class testmail extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'testmail:testmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending test mail.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        Mail::send( 'emails.welcome', array(), function($message) {
            $message->to( 'therock_624@hotmail.com', 'John Smith' )->subject( 'Welcome!' );
        } );
    }

}
