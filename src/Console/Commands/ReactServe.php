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
                            {--H|host=localhost : The host address to serve the application on.}
                            {--P|port=8080 : The port to serve the application on.}';

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
}
