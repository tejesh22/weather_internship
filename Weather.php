<?php
/*
Plugin Name: Weather Display
Description: Display weather for the specified location.
Version: 1.0
Author: Tejesh
*/

function display_weather() {
    $api_key = '397130a7ebe353979ece21b5f22d551b';

    if (isset($_GET['location'])) {
        $location = sanitize_text_field($_GET['location']);
    } else {
        $location = 'Delhi'; // Default location as per in assignment
    }

    $url = "https://api.openweathermap.org/data/2.5/weather?q={$location}&appid={$api_key}";

    $response = wp_remote_get($url);

    if (is_array($response)) {
        $data = json_decode($response['body']);

        if ($data) {
            echo "Weather in {$data->name}: {$data->weather[0]->description}, Temperature: {$data->main->temp}°C";
        } else {
            echo "Weather data not available for the specified location.";
        }
    }
}

add_shortcode('weather_display', 'display_weather');

// Add a user input form to the content
function display_location_input_form($content) {
    ob_start();
    ?>
    <form method="get" action="">
        <label for="location">Enter Location:</label>
        <input type="text" name="location" id="location" placeholder="Enter a city">
        <input type="submit" value="Get Weather">
    </form>

    [weather_display]
    <?php
    $form_content = ob_get_clean();
    return $form_content . $content;
}
add_filter('the_content', 'display_location_input_form');
