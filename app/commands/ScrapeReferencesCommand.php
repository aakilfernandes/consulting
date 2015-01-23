<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScrapeReferencesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:scrape-references';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$pages = Curl::get('https://docs.angularjs.org/js/search-data.json')->body;

		foreach($pages as $page){
			
			if(strpos($page->path,'error/')!==0) continue;
			
			$reference = Reference::where('path','=',$page->path)->first();
			if(!$reference) $reference = new Reference;

			$html = Curl::get("https://docs.angularjs.org/partials/{$page->path}.html")->body;

			$reference->path = $page->path;
			$reference->html = $html;
			$reference->save();
		
			sleep(1);

		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
