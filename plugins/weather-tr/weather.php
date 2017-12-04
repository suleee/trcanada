<?php

function httpGet($url)
{
   $ch = curl_init();
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false); 
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
}

function getW() {
    $w = '{"coord":{"lon":-123.12,"lat":49.25},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"base":"stations","main":{"temp":7.24,"pressure":1018,"humidity":87,"temp_min":5,"temp_max":9},"visibility":24140,"wind":{"speed":6.2,"deg":90},"clouds":{"all":90},"dt":1511849700,"sys":{"type":1,"id":3359,"message":0.2456,"country":"CA","sunrise":1511883786,"sunset":1511914679},"id":6173331,"name":"Vancouver","cod":200}';
    return $w;
}
function getF() {
	// $today_weather_url = 'http://api.openweathermap.org/data/2.5/forecast?q=vancouver,ca&units=metric&cnt=7&appid=b8d0c5f0b3d878c123756fc0a4284ea9';
	$w = '{"cod":"200","message":0.1698,"cnt":7,"list":[{"dt":1512345600,"main":{"temp":5.39,"temp_min":4.07,"temp_max":5.39,"pressure":1017.9,"sea_level":1037.21,"grnd_level":1017.9,"humidity":100,"temp_kf":1.32},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":56},"wind":{"speed":1.26,"deg":269},"rain":{},"snow":{"3h":0.0095000000000001},"sys":{"pod":"n"},"dt_txt":"2017-12-04 00:00:00"},{"dt":1512356400,"main":{"temp":3.76,"temp_min":2.77,"temp_max":3.76,"pressure":1019.95,"sea_level":1039.41,"grnd_level":1019.95,"humidity":100,"temp_kf":0.99},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":12},"wind":{"speed":0.71,"deg":277.5},"rain":{},"snow":{"3h":0.023},"sys":{"pod":"n"},"dt_txt":"2017-12-04 03:00:00"},{"dt":1512367200,"main":{"temp":2.04,"temp_min":1.38,"temp_max":2.04,"pressure":1022.11,"sea_level":1041.72,"grnd_level":1022.11,"humidity":100,"temp_kf":0.66},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":32},"wind":{"speed":0.5,"deg":20.5069},"rain":{},"snow":{"3h":0.00675},"sys":{"pod":"n"},"dt_txt":"2017-12-04 06:00:00"},{"dt":1512378000,"main":{"temp":2.24,"temp_min":1.91,"temp_max":2.24,"pressure":1024.04,"sea_level":1043.66,"grnd_level":1024.04,"humidity":100,"temp_kf":0.33},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":68},"wind":{"speed":0.71,"deg":73.0029},"rain":{},"snow":{"3h":0.01125},"sys":{"pod":"n"},"dt_txt":"2017-12-04 09:00:00"},{"dt":1512388800,"main":{"temp":2.36,"temp_min":2.36,"temp_max":2.36,"pressure":1025.93,"sea_level":1045.5,"grnd_level":1025.93,"humidity":100,"temp_kf":0},"weather":[{"id":600,"main":"Snow","description":"light snow","icon":"13n"}],"clouds":{"all":56},"wind":{"speed":0.99,"deg":93.0011},"rain":{},"snow":{"3h":0.035},"sys":{"pod":"n"},"dt_txt":"2017-12-04 12:00:00"},{"dt":1512399600,"main":{"temp":2.38,"temp_min":2.38,"temp_max":2.38,"pressure":1027.29,"sea_level":1046.91,"grnd_level":1027.29,"humidity":100,"temp_kf":0},"weather":[{"id":600,"main":"Snow","description":"light snow","icon":"13n"}],"clouds":{"all":68},"wind":{"speed":1.55,"deg":99.5072},"rain":{},"snow":{"3h":0.045},"sys":{"pod":"n"},"dt_txt":"2017-12-04 15:00:00"},{"dt":1512410400,"main":{"temp":3.13,"temp_min":3.13,"temp_max":3.13,"pressure":1028.59,"sea_level":1048.21,"grnd_level":1028.59,"humidity":100,"temp_kf":0},"weather":[{"id":600,"main":"Snow","description":"light snow","icon":"13d"}],"clouds":{"all":64},"wind":{"speed":1.39,"deg":103.003},"rain":{},"snow":{"3h":0.1375},"sys":{"pod":"d"},"dt_txt":"2017-12-04 18:00:00"}],"city":{"id":6173331,"name":"Vancouver","coord":{"lat":49.2497,"lon":-123.1194},"country":"CA"}}';
	return $w;
}

function isTimeToUpdate() {
	$fname = "weathertime.txt";
	if (file_exists($fname)) {
		$time = file_get_contents($fname);
		if ($time < time()) {
			file_put_contents($fname, time()+3600);
			return true;
		}
		return false;
	} else {
		file_put_contents($fname, time()+3600);
		return true;
	}
}

function getTodayWeather() {
	$fname = "weathertoday.json";
	if(isTimeToUpdate() || !file_exists($fname)) {
		$json = httpGet("http://api.openweathermap.org/data/2.5/weather?q=vancouver,ca&appid=b8d0c5f0b3d878c123756fc0a4284ea9&units=metric");
		file_put_contents($fname, $json);
	} else {
		$json = file_get_contents($fname);
	}
	return $json;
}

function getForecastWeather() {
	$fname = "weatherforecast.json";
	if(isTimeToUpdate() || !file_exists($fname)) {
		$json = httpGet("http://api.openweathermap.org/data/2.5/forecast?q=vancouver,ca&units=metric&appid=b8d0c5f0b3d878c123756fc0a4284ea9");
		file_put_contents($fname, $json);
	} else {
		$json = file_get_contents($fname);
	}
	return $json;
}


class td_weather {

	private static $caching_time = 10800;  // 3 hours
	private static $caching_overtime = 315360000; // 60 * 60 * 24 * 365 * 10
	//private static $owm_api_key = 'f5dc074e364b4d0bbaacbab0030031a3';

	private static $owm_api_keys = array (
		'b8d0c5f0b3d878c123756fc0a4284ea9',
		'b8d0c5f0b3d878c123756fc0a4284ea9',
		'b8d0c5f0b3d878c123756fc0a4284ea9'
	);


	/**
	 * Used by all the shortcodes + widget to render the weather. The top bar has a separate function bellow
	 * @param $atts
	 * @param $block_uid
	 * @param $template string -> block_template | top_bar_template
	 * @return string
	 */
	static function render_generic($atts) {

        $block_uid = 'abc';
        $current_temp_label = 'curr temp';
        $current_speed_label = 'curr speed';

		$current_unit = 0; // 0 - metric
		$current_temp_label = 'C';
		$current_speed_label = 'kmh';

        $atts['w_location'] = 'Vancouver,ca';
		// prepare the data and do an api call
		$weather_data = array (
			'block_uid' => '',
			'location' => $atts['w_location'],
			'api_location' => $atts['w_location'],  // the current location. It is updated by the wheater API
			'api_language' => '', //this is set down bellow
			//'api_key' => self::get_a_owm_key(),
			'today_icon' => 'clear-sky-d',
			'today_icon_text' => '',
			'today_temp' => array (
				0,  // metric
				0   // imperial
			),
			'today_humidity' => '',
			'today_wind_speed' => array (
				0, // metric
				0 // imperial
			),
			'today_min' => array (
				0, // metric
				0 // imperial
			),
			'today_max' => array (
				0, // metric
				0 // imperial
			),
			'today_clouds' => 0,
			'current_unit' => $current_unit,
			'forecast' => array()
		);

		$weather_data['block_uid'] = 'abc';

		self::get_today_data($atts, $weather_data);
		$r = self::get_five_days_data($atts, $weather_data);
		// var_dump($weather_data);

		// render the HTML
		$buffy = '<!-- weather -->';
        $buffy .= self::render_block_template($atts, $weather_data, $current_temp_label, $current_speed_label, $block_uid);

		return $buffy;
	}

	/**
	 * renders the template that is used on all weather blocks and widgets
	 * @param $atts - the atts that the block gets
	 * @param $weather_data - the precomputed weather data
	 * @param $current_temp_label - C/F
	 * @param $current_speed_label - mph/kmh
	 * @param $block_uid the unique id of the block
	 * @return string - HTML the rendered template
	 */
	private static function render_block_template($atts, $weather_data, $current_temp_label, $current_speed_label, $block_uid) {
		$current_unit = $weather_data['current_unit'];
		ob_start();
		?>

		<div class="td-weather-header">
			<div class="td-weather-city"><?php echo $atts['w_location'] ?></div>
			<div class="td-weather-condition"><?php echo $weather_data['today_icon_text'] ?></div>
			<i class="td-location-icon td-icons-location"  data-block-uid="<?php echo $weather_data['block_uid'] ?>"></i>
		</div>

		<div class="td-weather-set-location">
			<form class="td-manual-location-form" action="#" data-block-uid="<?php echo $weather_data['block_uid'] ?>">
				<input id="<?php echo $weather_data['block_uid'] ?>" class="td-location-set-input" type="text" name="location" value="" >
				<label>enter location</label>
			</form>
		</div>

		<div class="td-weather-temperature">
			<div class="td-weather-temp-wrap">
				<div class="td-weather-animated-icon">
					<span class="td_animation_sprite-27-100-80-0-0-1 <?php echo $weather_data['today_icon'] ?> td-w-today-icon" data-td-block-uid="<?php echo $block_uid?>"></span>
				</div>
				<div class="td-weather-now" data-block-uid="<?php echo $weather_data['block_uid'] ?>">
					<span class="td-big-degrees"><?php echo $weather_data['today_temp'][$current_unit] ?></span>
					<span class="td-circle">&deg;</span>
					<span class="td-weather-unit"><?php echo $current_temp_label; ?></span>
				</div>
				<div class="td-weather-lo-hi">
					<div class="td-weather-degrees-wrap">
						<i class="td-up-icon td-icons-arrows-up"></i>
						<span class="td-small-degrees td-w-high-temp"><?php echo $weather_data['today_max'][$current_unit] ?></span>
						<span class="td-circle">&deg;</span>
					</div>
					<div class="td-weather-degrees-wrap">
						<i class="td-down-icon td-icons-arrows-down"></i>
						<span class="td-small-degrees td-w-low-temp"><?php echo $weather_data['today_min'][$current_unit] ?></span>
						<span class="td-circle">&deg;</span>
					</div>
				</div>
			</div>
		</div>

		<div class="td-weather-info-wrap">
			<div class="td-weather-information">
				<div class="td-weather-section-1">
					<i class="td-icons-drop"></i>
					<span class="td-weather-parameter td-w-today-humidity"><?php echo $weather_data['today_humidity'] ?>%</span>
				</div>
				<div class="td-weather-section-2">
					<i class="td-icons-wind"></i>
					<span class="td-weather-parameter td-w-today-wind-speed"><?php echo $weather_data['today_wind_speed'][$current_unit] . $current_speed_label; ?></span>
				</div>
				<div class="td-weather-section-3">
					<i class="td-icons-cloud"></i>
					<span class="td-weather-parameter td-w-today-clouds"><?php echo $weather_data['today_clouds'] ?>%</span>
				</div>
			</div>


			<div class="td-weather-week">
				<?php

				foreach ($weather_data['forecast'] as $forecast_index => $day_forecast) {
					?>
					<div class="td-weather-days">
						<div class="td-day-<?php echo $forecast_index ?>"><?php echo $day_forecast['day_name'] ?></div>
						<div class="td-day-degrees">
							<span class="td-degrees-<?php echo $forecast_index ?>"><?php echo $day_forecast['day_temp'][$current_unit] ?></span>
							<span class="td-circle">&deg;</span>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private static function get_today_data($atts, &$weather_data) {
	
		$json_api_response = getTodayWeather();

		$api_response = @json_decode($json_api_response, true);
		if ($api_response === null and json_last_error() !== JSON_ERROR_NONE) {
			return 'Error decoding the json from OpenWeatherMap';
		}

		if ($api_response['cod'] != 200) {
			if ($api_response['cod'] == 404) {
				return 'City not found'; // fix the incorect error message form the api :|
			}
			if (isset($api_response['message'])) {
				return $api_response['message'];
			}
			return 'OWM code != 200. No message provided';
		}

		//print_r($api_response);

		// current location
		if (isset($api_response['name'])) {
			$weather_data['api_location'] = $api_response['name'];
		}

		// min max current temperature
		if (isset($api_response['main']['temp'])) {
			$weather_data['today_temp'][0] = round($api_response['main']['temp'], 1);
			$weather_data['today_temp'][1] = self::celsius_to_fahrenheit($api_response['main']['temp']);
		}
		if (isset($api_response['main']['temp_min'])) {
			$weather_data['today_min'][0] = round($api_response['main']['temp_min'], 1);
			$weather_data['today_min'][1] = self::celsius_to_fahrenheit($api_response['main']['temp_min']);
		}
		if (isset($api_response['main']['temp_max'])) {
			$weather_data['today_max'][0] = round($api_response['main']['temp_max'], 1);
			$weather_data['today_max'][1] = self::celsius_to_fahrenheit($api_response['main']['temp_max']);
		}

		// humidity
		if (isset($api_response['main']['humidity'])) {
			$weather_data['today_humidity'] = round($api_response['main']['humidity']);
		}

		// wind speed and direction
		if (isset($api_response['wind']['speed'])) {
			$weather_data['today_wind_speed'][0] = round($api_response['wind']['speed'], 1);
			$weather_data['today_wind_speed'][1] = self::kmph_to_mph($api_response['wind']['speed']);
		}

		// forecast description
		if (isset($api_response['weather'][0]['description'])) {
			$weather_data['today_icon_text'] = $api_response['weather'][0]['description'];
		}

		// clouds
		if (isset($api_response['clouds']['all'])) {
			$weather_data['today_clouds'] = round($api_response['clouds']['all']);
		}

		// icon
		if (isset($api_response['weather'][0]['icon'])) {
			$icons = array (
				// day
				'01d' => 'clear-sky-d',
				'02d' => 'few-clouds-d',
				'03d' => 'scattered-clouds-d',
				'04d' => 'broken-clouds-d',
				'09d' => 'shower-rain-d',   // ploaie hardcore
				'10d' => 'rain-d',          // ploaie light
				'11d' => 'thunderstorm-d',
				'13d' => 'snow-d',
				'50d' => 'mist-d',

				//night
				'01n' => 'clear-sky-n',
				'02n' => 'few-clouds-n',
				'03n' => 'scattered-clouds-n',
				'04n' => 'broken-clouds-n',
				'09n' => 'shower-rain-n',   // ploaie hardcore
				'10n' => 'rain-n',          // ploaie light
				'11n' => 'thunderstorm-n',
				'13n' => 'snow-n',
				'50n' => 'mist-n',
			);

			$weather_data['today_icon'] = 'clear-sky-d'; // the default icon :) if we get an error or strange icons as a reply
			if (isset($icons[$api_response['weather'][0]['icon']])) {
				$weather_data['today_icon'] = $icons[$api_response['weather'][0]['icon']];
			}
		}  // end icon

		return true;  // return true if ~everything is ok
	}



	/**
	 * adds to the &$weather_data the information for the next 5 days
	 * @param $atts - the shortcode atts
	 * @param $weather_data - BYREF weather data - this function will add to it
	 *
	 * @return bool|string
	 *   - true: if everything is ok
	 *   - string: the error message, if there was an error
	 */
	private static function owm_get_five_days_data ($atts, &$weather_data) {
		// request 7 days because the current day may be today in a different timezone
		$today_weather_url = 'http://api.openweathermap.org/data/2.5/forecast/daily?q=' . urlencode($atts['w_location']) . '&lang=' . $atts['w_language'] . '&units=metric&cnt=7&appid=' . $weather_data['api_key'];

		//print("<pre>".print_r($today_weather_url,true)."</pre>");

		$json_api_response = td_remote_http::get_page($today_weather_url, __CLASS__);

		//print("<pre> json city forecast API response: ".print_r($json_api_response,true)."</pre>");


		// fail
		if ($json_api_response === false) {
            td_log::log(__FILE__, __FUNCTION__, 'Api call failed', $today_weather_url);
			return 'Error getting remote data for 5 days forecast. Please check your server configuration';
		}

		// try to decode the json
		$api_response = @json_decode($json_api_response, true);
		if ($api_response === null and json_last_error() !== JSON_ERROR_NONE) {
            td_log::log(__FILE__, __FUNCTION__, 'Error decoding the json', $api_response);
			return 'Error decoding the json from OpenWeatherMap';
		}


		// today in format like: 20150210
		$today_date = date( 'Ymd', current_time( 'timestamp', 0 ) );

		if (!empty($api_response['list']) and is_array($api_response['list'])) {
			$cnt = 0;

			foreach ($api_response['list'] as $index => $day_forecast) {

				if (
					!empty($day_forecast['dt'])
					and !empty($day_forecast['temp']['day'])
					and $today_date < date('Ymd', $day_forecast['dt'])  // compare today with the forecast date in the format 20150210, today must be smaller. We have to do this hack
				) {                                                     // because the api return UTC time and we may have different timezones on the server. Avoid showing the same day twice
					if ($cnt > 4) { // show only 5
						break;
					}
					$weather_data['forecast'][] = array (
						'timestamp' => $day_forecast['dt'],
						//'timestamp_readable' => date('Ymd', $day_forecast['dt']),
						'day_temp' => array (
							round($day_forecast['temp']['day']), // metric
							round(self::celsius_to_fahrenheit($day_forecast['temp']['day']))  //imperial
						),
						'day_name' => date_i18n('D', $day_forecast['dt']),
						'owm_day_index' => $index // used in js to update only the displayed days when we do api calls from JS
					);
					$cnt++;
				}

			}
			return true;
		}
		return false; // return true if ~everything is ok
	}

	private static function get_five_days_data ($atts, &$weather_data) {

		$json_api_response = getForecastWeather();

		// fail
		if ($json_api_response === false) {
            td_log::log(__FILE__, __FUNCTION__, 'Api call failed', $today_weather_url);
			return 'Error getting remote data for 5 days forecast. Please check your server configuration';
		}

		// try to decode the json
		$api_response = @json_decode($json_api_response, true);
		if ($api_response === null and json_last_error() !== JSON_ERROR_NONE) {
            td_log::log(__FILE__, __FUNCTION__, 'Error decoding the json', $api_response);
			return 'Error decoding the json from OpenWeatherMap';
		}


		// today in format like: 20150210
		$today_date = date( 'Ymd', current_time( 'timestamp', 0 ) );
		// echo "<pre>";
		$week = array();
		foreach ($api_response['list'] as $index => $day) {
			$dd = explode(" ", $day['dt_txt']);
			if (array_key_exists($dd[0], $week)) {
				$data = $week[$dd[0]];
				$max = $data['max'];
				$min = $data['min'];
				if ($max < $day['main']['temp']) {
					$week[$dd[0]]['max'] = $day['main']['temp'];
				}
				if ($min > $day['main']['temp']) {
					$week[$dd[0]]['min'] = $day['main']['temp'];
				}
			} else {
				$week[$dd[0]] = array(
					'max' => $day['main']['temp'], 
					'min' => $day['main']['temp'],
					'timestamp' => $day['dt'],
					'day_name' => $day['dt'],
					'owm_day_index' => $index
				);
			}
		}
		
		foreach($week as $index => $day_forecast) {
			$weather_data['forecast'][] = array (
				'timestamp' => $day_forecast['day_name'],
				'day_temp' => array(
					round($day_forecast['max']),
					round(self::celsius_to_fahrenheit($day_forecast['max']))
				),
				'day_name' => date_i18n('D', $day_forecast['day_name']),
				'owm_day_index' => $index // used in js to update only the displayed days when we do api calls from JS
			);
		}

		// var_dump($week);
		// echo "</pre>";

		// if (!empty($api_response['list']) and is_array($api_response['list'])) {
		// 	$cnt = 0;

		// 	foreach ($api_response['list'] as $index => $day_forecast) {
		// 		if (
		// 			!empty($day_forecast['dt'])
		// 			and !empty($day_forecast['main']['temp'])
		// 			and $today_date < date('Ymd', $day_forecast['dt'])  // compare today with the forecast date in the format 20150210, today must be smaller. We have to do this hack
		// 		) {                                                     // because the api return UTC time and we may have different timezones on the server. Avoid showing the same day twice
		// 			if ($cnt > 4) { // show only 5
		// 				break;
		// 			}
		// 			$dd = explode(" ", $day_forecast['dt_txt']);
		// 			$dd = $dd[0];

		// 			$weather_data['forecast'][] = array (
		// 				'timestamp' => $day_forecast['dt'],
		// 				'day_temp' => array(
		// 					round($week[$dd]['max']),
		// 					round(self::celsius_to_fahrenheit($week[$dd]['max']))
		// 				),
		// 				// 'day_temp' => array (
		// 				// 	round($day_forecast['main']['temp']), // metric
		// 				// 	round(self::celsius_to_fahrenheit($day_forecast['main']['temp']))  //imperial
		// 				// ),
		// 				'day_name' => date_i18n('D', $day_forecast['dt']),
		// 				'owm_day_index' => $index // used in js to update only the displayed days when we do api calls from JS
		// 			);
		// 			$cnt++;
		// 		}

		// 	}
		// 	// echo "</pre>";
		// 	// var_dump($weather_data['forecast']);
		// 	return true;
		// }
		return true; // return true if ~everything is ok
	}

	/**
	 * convert celsius to fahrenheit + rounding (no decimals if result > 100 or one decimal if result < 100)
	 * @param $celsius_degrees
	 * @return float
	 */
	private static function celsius_to_fahrenheit ($celsius_degrees) {
		$f_degrees = $celsius_degrees * 9 / 5 + 32;

		$rounded_val = round($f_degrees, 1);
		if ($rounded_val > 99.9) {  // if the value is bigger than 100, round it with no zecimals
			return round($f_degrees);
		}

		return $rounded_val;
	}



	/**
	 * rounding to .1
	 * @param $kmph
	 * @return float
	 */
	private static function kmph_to_mph ($kmph) {
		return round($kmph * 0.621371192, 1);
	}



	/**
	 * Show an error if the user is logged in. It does not check for admin
	 * @param $msg
	 * @return string
	 */
	private static function error($msg) {
		if (is_user_logged_in()) {
			return $msg;
		}
		return '';
	}

	private static function get_a_owm_key( $avoid_keys = array() ) {

		if (empty($avoid_keys)) {
			return self::$owm_api_keys[rand(0, count(self::$owm_api_keys) - 1)];
		}

		$available_keys = array_values(array_diff(self::$owm_api_keys, $avoid_keys));

		if (empty($available_keys)) {
			return null;
		}

		return $available_keys[rand(0, count($available_keys) - 1)];
	}
}