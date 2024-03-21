<!DOCTYPE html>
<html>

<head>
    <title>WebDev Test - Junior PHP</title>
</head>

<body>

    <main>
        <?php

        $json = file_get_contents('data.json');

        $json_data = json_decode($json, true);

        $parsed_data = array();

        foreach ($json_data as $value) {
            $news_title = $value['title'];
            $news_link = $value['link'];
            $news_description = $value['description'];
            $news_pubDate = $value['pubDate'];

            // Add the data to a new array
            $parsed_data[] = array(
                'title' => $news_title,
                'link' => $news_link,
                'description' => $news_description,
                'pubDate' => $news_pubDate
            );
        }
        ;

        // Function to determine the correct ordinal suffix for a given day
        function getOrdinalSuffix($day)
        {
            if ($day % 10 == 1 && $day != 11) {
                return 'st';
            } elseif ($day % 10 == 2 && $day != 12) {
                return 'nd';
            } elseif ($day % 10 == 3 && $day != 13) {
                return 'rd';
            } else {
                return 'th';
            }
        }

        // Function to format a given date string according to the specified format
        function formatDate($dateString)
        {
            // Create a DateTime object from the input date string, specifying the input format
            $date = DateTime::createFromFormat('D, d M Y H:i:s T', $dateString);

            // Extract various parts of the date
            $day = $date->format('j'); // Day of the month
            $ordinalSuffix = getOrdinalSuffix($day); // Ordinal suffix for the day
            $longMonthName = $date->format('F'); // Full month name
            $year = $date->format('Y'); // Year
            $hour = $date->format('g'); // Hour without leading zero
            $minute = $date->format('i'); // Minute
            $meridiem = $date->format('a'); // Ante/Post Meridiem
        
            // https://www.php.net/manual/en/datetime.format.php
        
            // Construct the formatted date string
            return "{$date->format('l')}, {$day}{$ordinalSuffix} of {$longMonthName} {$year} {$hour}:{$minute} {$meridiem}";
        }

        // Function to check if a link is a URL
        function isUrl($link)
        {
            return filter_var($link, FILTER_VALIDATE_URL) !== false;

            // https://www.php.net/manual/en/function.filter-var.php
            // https://www.php.net/manual/en/filter.filters.validate.php
        }

        // Function to display the "Read More" link if a URL is present
        function displayReadMoreLink($links)
        {
            foreach ($links as $link) {
                if (isUrl($link)) {
                    echo '<a href="' . htmlspecialchars($link) . '" target="_blank">Read More</a>';
                    return; // Exit the loop once a URL is found
                }
            }
        }

        // Sort the array by the title alphabetically
        usort($parsed_data, function ($a, $b) {
            return $a['title'] <=> $b['title'];

            // https://www.php.net/manual/en/function.usort.php
            // https://www.php.net/manual/en/language.operators.comparison.php
        });



        foreach ($parsed_data as $value) {

            echo '<div class="news-item">';

            echo '<h2>' . $value['title'] . '</h2>';

            echo '<p>' . $value['description'] . '</p>';

            if (is_array($value['link'])) {
                displayReadMoreLink($value['link']);
            } elseif (isUrl($value['link'])) {
                echo '<a href="' . htmlspecialchars($value['link']) . '" target="_blank">Read More</a>';
            }

            echo '<p style="font-style: italic">' . formatDate($value['pubDate']) . '</p>';

            echo '</div>';
        }
        ;
        ?>

    </main>



    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const mainChildren = document.querySelector('main').children;
            let duration = 400; // Base duration in milliseconds
            Array.from(mainChildren).forEach(function (child, index) {
                child.style.opacity = 0;
                child.style.transition = `opacity ${duration}ms ease-in`;
                setTimeout(function () {
                    child.style.opacity = 1;
                }, index * duration); // Delay based on the index
                duration += 100; // Increase duration for each child
            });
        });

    </script>
</body>

</html>