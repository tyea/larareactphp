<?php

namespace Tyea\LaraReactPhp\Console\Commands;

use Illuminate\Console\Command;
use Tyea\LaraReactPhp\ReactPhpServer;

class ServeReactPhpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve:reactphp {--l|listen=tcp://127.0.0.1:8080 : Listen address.}';

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

        $reactServer = new ReactPhpServer($listen, $verbose);

        $app->instance('react.server', $reactServer);

        $reactServer->run();
    }
}
