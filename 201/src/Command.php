<?php
namespace Osky;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class Command extends SymfonyCommand
{

    public function __construct()
    {
        parent::__construct();
    }
    protected function searchReddit(InputInterface $input, OutputInterface $output)
    {
        $output -> writeln([
            'Reddit Searcher v0.2.0',
            '======================',
            '',
        ]);

        echo "Enter the name of the subreddit you want to search in (default: webdev): ";
        $subreddit = rtrim(fgets(STDIN));
        if ($subreddit === '') {
            $subreddit = 'webdev'; // set default value to webdev
        } else {
            $subreddit = strtolower($subreddit);
        }
        ;

        echo "Enter your search query (default: php): ";
        $search_query = rtrim(fgets(STDIN));
        if ($search_query === '') {
            $search_query = 'php'; // set default value to php
        } else {
            $search_query = strtolower($search_query);
        }
        ;

        echo "\n\nSearching for '{$search_query}' in the '{$subreddit}' subreddit...\n\n\n";

        // Set up cURL to fetch data from the subreddit's JSON feed
        $url = "https://www.reddit.com/r/{$subreddit}.json";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'cli:oskytest.search:v0.1.0 (by /u/crafty-most-4944)');
        // Enable verbose output to see the request and response details
        // curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Execute the cURL request
        $json = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Seems like there was an error fetching the data. Is Reddit down?';
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Decode the JSON data
            $data = json_decode($json, true);

            // If we get a 404 response, the subreddit doesn't exist
            if (isset($data['error']) && $data['error'] == 404) {
                echo "The subreddit '{$subreddit}' does not exist.\n";
                exit;
            }

            // Filter posts based on the search query in the title and selftext
            $filteredPosts = [];
            foreach ($data['data']['children'] as $child) {
                $title = strtolower($child['data']['title']);
                $selftext = strtolower($child['data']['selftext'] ?? ''); // Use an empty string if selftext is not set

                if (strpos($title, $search_query) !== false || strpos($selftext, $search_query) !== false) {
                    $filteredPosts[] = $child;
                }
            }

            $filteredPostsArray = array();

            // Display filtered posts
            foreach ($filteredPosts as $post) {

                date_default_timezone_set("Asia/Kuala_Lumpur"); // UTC+8
                $post_date = date('Y-m-d H:i:s', $post['data']['created']);
                $post_title = $post['data']['title'];
                $post_url = $post['data']['url'];
                $post_content = $post['data']['selftext'];

                // Truncate the content to 30 characters
                $post_title = strlen($post_title) > 30 ? substr($post_title, 0, 30) . '...' : $post_title;
                $post_content = strlen($post_content) > 30 ? substr($post_content, 0, 30) . '...' : $post_content;

                // Add the post to the filteredPostsArray
                array_push($filteredPostsArray, array($post_date, $post_title, $post_url, $post_content));

            }

            // Sort the items in the filteredPostsArray by their title in alphabetical order
            usort($filteredPostsArray, function ($a, $b) {
                return $a[1] <=> $b[1];
            });

            $table = new Table($output);
            $table
                ->setHeaders(['Date', 'Title', 'URL', 'Excerpt'])
                ->setRows(
                    $filteredPostsArray,
                );
            ;

            if (empty ($filteredPostsArray)) {
                echo "No posts found matching the search query '{$search_query}' in the '{$subreddit}' subreddit.\n";
            } else {
                $table->render();
                echo "Found " . count($filteredPostsArray) . " posts matching the search query '{$search_query}' in the '{$subreddit}' subreddit.\n";

            }
            ;
        }

        curl_close($ch);

    }

}
