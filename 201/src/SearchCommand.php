<?php 
namespace Osky;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Osky\Command;
class SearchCommand extends Command
{
    
    public function configure()
    {
        $this -> setName('reddit:search')
            -> setDescription('Search a subreddit for a specific query')
            -> setHelp('This command allows you to search a subreddit for a specific query');

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this -> searchReddit($input, $output);

        return 0;
    }
}