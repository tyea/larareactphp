<?php

namespace LaravelReactPHP\Console\Commands;

use Illuminate\Console\Command;

class ReactServe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'react-serve
                            {--l|listen=tcp://127.0.0.1:8080 : Listen address.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Serve the application on the ReactPHP server";

    /**
     * @deprecated since laravel 5.5
     */
    public function fire()
    {
        $this->handle();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $listen = $this->input->getOption('listen');

        $this->info("Laravel ReactPHP server started on {$listen}");

        $verbose = $this->option('verbose');

        $app = $this->getLaravel();

        $reactServer = new \LaravelReactPHP\LaravelReactServer($listen, $verbose);

        $app->instance('react.server', $reactServer);

        $reactServer->run();
    }
}
