<?php
namespace Osky;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

class Command extends SymfonyCommand
{

    public function __construct()
    {
        parent::__construct();
    }
    protected function searchReddit(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Reddit Search v0.1.0',
            '=====================',
            '',
        ]);

        echo "Enter the name of the subreddit you want to search: (webdev) ";
        $subreddit = rtrim(fgets(STDIN));
        if ($subreddit === '') {
            $subreddit = 'webdev'; // set default value to webdev
        }
        
        echo "Enter your search query: (php) ";
        $search_query = rtrim(fgets(STDIN));
        if ($search_query === '') {
            $search_query = 'php'; // set default value to php
        }

        // https://www.reddit.com/dev/api#GET_subreddits_search

        $accessToken = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IlNIQTI1NjpzS3dsMnlsV0VtMjVmcXhwTU40cWY4MXE2OWFFdWFyMnpLMUdhVGxjdWNZIiwidHlwIjoiSldUIn0.eyJzdWIiOiJ1c2VyIiwiZXhwIjoxNzExMDk0MzU5Ljk1MjQyNCwiaWF0IjoxNzExMDA3OTU5Ljk1MjQyNCwianRpIjoiMk5JbXJBNnF4RnROdFo4OHN4OUh6S0RZTk5yRmRRIiwiY2lkIjoid0l5WnVKM3hNS2htb2xmZDNnLXQ3USIsImxpZCI6InQyXzI3MXp1bzV1IiwiYWlkIjoidDJfMjcxenVvNXUiLCJsY2EiOjE1MzY3NjAwNTIxOTcsInNjcCI6ImVKeUtWc3BNU2MwcnlTeXBWSW9GQkFBQV9fOGNMd1JuIiwicmNpZCI6IjB4R19MRlRxZURvSjZCcGptNTFLSXFBMFZ1cGFGdTVFWXJSMjljeHJ0Y0kiLCJmbG8iOjh9.R4XxMfFA_FPchifaM239iwQFxIBu35gfsfIO0CTvOtGEc0FzI2oL3PrLIXtLq0VvdWioysc2iIjEVA2V7K0VZSzyUB_1FYC89VhUGW9bzQADDWfux9KtYhuulgBsEyxqDlPGXPGeQNuRdnFekQBX9RGFtrg_2fCN8RFFsMk7M8JJtPOheFXAkUS7TSZtJOpbVFFbiDVIaVMDPC96wpsUT0iLRYrmergMVzRbk7hWoIZovatdyMgQPl4vsD1P1d2oNOrAlrR7zycCGPHLoI6iwAXR5-3Zsdg57Y-ve5OAnexEihu0f7eJnRtMItD2WB1oHaW7oSQ4xqjWrD95UPi-kA';

        $client = new Client([
            'base_uri' => 'https://oauth.reddit.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'User-Agent' => 'cli:oskytest.search:v0.1.0 (by /u/crafty-most-4944)'
            ]
        ]);

        $response = $client->get("/r/{$subreddit}/search", [
            'query' => [
                'q' => $search_query,
                'limit' => 100, // Limit the results to the latest 100 posts
                'restrict_sr' => true, // Restrict search to the specified subreddit
                'sort' => 'new', 
                't' => 'all' 
            ]
        ]);

        $body = $response->getBody();
        $content = $body->getContents();

        echo $content;
    }

}