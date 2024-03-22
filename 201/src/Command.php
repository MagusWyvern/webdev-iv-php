<?php
namespace Osky;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
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
            'Reddit Searcher v0.1.0',
            '======================',
            '',
        ]);

        echo "Enter the name of the subreddit you want to search: (webdev) ";
        $subreddit = rtrim(fgets(STDIN));
        if ($subreddit === '') {
            $subreddit = 'webdev'; // set default value to webdev
        } else {
            $subreddit = strtolower($subreddit);
        }
        ;

        echo "Enter your search query: (php) ";
        $search_query = rtrim(fgets(STDIN));
        if ($search_query === '') {
            $search_query = 'php'; // set default value to php
        } else {
            $search_query = strtolower($search_query);
        }
        ;

        $accessToken = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IlNIQTI1NjpzS3dsMnlsV0VtMjVmcXhwTU40cWY4MXE2OWFFdWFyMnpLMUdhVGxjdWNZIiwidHlwIjoiSldUIn0.eyJzdWIiOiJ1c2VyIiwiZXhwIjoxNzExMDk0MzU5Ljk1MjQyNCwiaWF0IjoxNzExMDA3OTU5Ljk1MjQyNCwianRpIjoiMk5JbXJBNnF4RnROdFo4OHN4OUh6S0RZTk5yRmRRIiwiY2lkIjoid0l5WnVKM3hNS2htb2xmZDNnLXQ3USIsImxpZCI6InQyXzI3MXp1bzV1IiwiYWlkIjoidDJfMjcxenVvNXUiLCJsY2EiOjE1MzY3NjAwNTIxOTcsInNjcCI6ImVKeUtWc3BNU2MwcnlTeXBWSW9GQkFBQV9fOGNMd1JuIiwicmNpZCI6IjB4R19MRlRxZURvSjZCcGptNTFLSXFBMFZ1cGFGdTVFWXJSMjljeHJ0Y0kiLCJmbG8iOjh9.R4XxMfFA_FPchifaM239iwQFxIBu35gfsfIO0CTvOtGEc0FzI2oL3PrLIXtLq0VvdWioysc2iIjEVA2V7K0VZSzyUB_1FYC89VhUGW9bzQADDWfux9KtYhuulgBsEyxqDlPGXPGeQNuRdnFekQBX9RGFtrg_2fCN8RFFsMk7M8JJtPOheFXAkUS7TSZtJOpbVFFbiDVIaVMDPC96wpsUT0iLRYrmergMVzRbk7hWoIZovatdyMgQPl4vsD1P1d2oNOrAlrR7zycCGPHLoI6iwAXR5-3Zsdg57Y-ve5OAnexEihu0f7eJnRtMItD2WB1oHaW7oSQ4xqjWrD95UPi-kA';

        $client = new Client([
            'base_uri' => 'https://oauth.reddit.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'User-Agent' => 'cli:oskytest.search:v0.1.0 (by /u/crafty-most-4944)'
            ]
        ]);

        echo "\n\nSearching for '{$search_query}' in the '{$subreddit}' subreddit...\n\n";

        $table = new Table($output);
        $table
            ->setHeaders(['Date', 'Title', 'URL', 'Excerpt'])
            ->setRows([
                ['2018-09-18 19:00:00', 'Title 1', 'http://example.com/1', 'Excerpt 1'],
                ['2018-09-18 19:00:00', 'Title 2', 'http://example.com/2', 'Excerpt 2'],
                ['2018-09-18 19:00:00', 'Title 3', 'http://example.com/3', 'Excerpt 3'],
            ])
        ;
        $table->render();

        // https://www.reddit.com/dev/api#GET_subreddits_search


        // Set up cURL to fetch data from the subreddit's JSON feed
        $url = "https://www.reddit.com/r/{$subreddit}.json";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'cli:oskytest.search:v0.1.0 (by /u/crafty-most-4944)');
// Enable verbose output to see the request and response details
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Execute the cURL request
        $json = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Decode the JSON data
            $data = json_decode($json, true);

            // Display the titles of the posts
            foreach ($data['data']['children'] as $child) {
                echo $child['data']['title'] . "\n";
            }
        }

        curl_close($ch);

        $data = json_decode($json, true);

        echo $data;

        // Assuming you want to display the titles of the posts
        // foreach ($data['data']['children'] as $child) {
        //     echo $child['data']['title'] . "\n";
        // }

    }

}
