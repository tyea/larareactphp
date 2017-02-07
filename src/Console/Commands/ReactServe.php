<?php

namespace LaravelReactPHP\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ReactServe extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'react-serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Serve the application on the ReactPHP server";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $host = $this->input->getOption('host');

        $port = $this->input->getOption('port');

        $this->info("Laravel ReactPHP server started on http://{$host}:{$port}");

        $verbose = $this->option('verbose');

        with(new \LaravelReactPHP\Server($host, $port, $verbose))->run();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['host', null, InputOption::VALUE_OPTIONAL, 'The host address to serve the application on.', 'localhost'],
            ['port', null, InputOption::VALUE_OPTIONAL, 'The port to serve the application on.', 8000)]
    ];
}
}
