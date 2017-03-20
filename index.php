<?php
ini_set('memory_limit', '512M');

require_once 'thesaurus.php';

require_once 'movies.php';

require_once 'names.php';

require_once 'places.php';

$vowels = ['a', 'e', 'i', 'o', 'u'];

$ignored_words = [
	'the', 'a', 'an', 'or', 'is', 'not', 'with',
	'i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'us', 'her', 'his', 'their', 'them',
	'at', 'of', 'to', 'and', 'in', 'over', 'one', '&'
];

$ignored_words = array_flip($ignored_words);

$plural = [
	'/(quiz)$/i'                     => "$1zes",
	'/^(ox)$/i'                      => "$1en",
	'/([m|l])ouse$/i'                => "$1ice",
	'/(matr|vert|ind)ix|ex$/i'       => "$1ices",
	'/(x|ch|ss|sh)$/i'               => "$1es",
	'/([^aeiouy]|qu)y$/i'            => "$1ies",
	'/(hive)$/i'                     => "$1s",
	'/(?:([^f])fe|([lr])f)$/i'       => "$1$2ves",
	'/(shea|lea|loa|thie)f$/i'       => "$1ves",
	'/sis$/i'                        => "ses",
	'/([ti])um$/i'                   => "$1a",
	'/(tomat|potat|ech|her|vet)o$/i' => "$1oes",
	'/(bu)s$/i'                      => "$1ses",
	'/(alias)$/i'                    => "$1es",
	'/(octop)us$/i'                  => "$1i",
	'/(ax|test)is$/i'                => "$1es",
	'/(us)$/i'                       => "$1es",
	'/s$/i'                          => "s",
	'/$/'                            => "s"
];

$singular = [
	'/(quiz)zes$/i'                                                    => "$1",
	'/(matr)ices$/i'                                                   => "$1ix",
	'/(vert|ind)ices$/i'                                               => "$1ex",
	'/^(ox)en$/i'                                                      => "$1",
	'/(alias)es$/i'                                                    => "$1",
	'/(octop|vir)i$/i'                                                 => "$1us",
	'/(cris|ax|test)es$/i'                                             => "$1is",
	'/(shoe)s$/i'                                                      => "$1",
	'/(o)es$/i'                                                        => "$1",
	'/(bus)es$/i'                                                      => "$1",
	'/([m|l])ice$/i'                                                   => "$1ouse",
	'/(x|ch|ss|sh)es$/i'                                               => "$1",
	'/(m)ovies$/i'                                                     => "$1ovie",
	'/(s)eries$/i'                                                     => "$1eries",
	'/([^aeiouy]|qu)ies$/i'                                            => "$1y",
	'/([lr])ves$/i'                                                    => "$1f",
	'/(tive)s$/i'                                                      => "$1",
	'/(hive)s$/i'                                                      => "$1",
	'/(li|wi|kni)ves$/i'                                               => "$1fe",
	'/(shea|loa|lea|thie)ves$/i'                                       => "$1f",
	'/(^analy)ses$/i'                                                  => "$1sis",
	'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
	'/([ti])a$/i'                                                      => "$1um",
	'/(n)ews$/i'                                                       => "$1ews",
	'/(h|bl)ouses$/i'                                                  => "$1ouse",
	'/(corpse)s$/i'                                                    => "$1",
	'/(us)es$/i'                                                       => "$1",
	'/s$/i'                                                            => ""
];

$irregular = [
	'move'   => 'moves',
	'foot'   => 'feet',
	'goose'  => 'geese',
	'sex'    => 'sexes',
	'child'  => 'children',
	'man'    => 'men',
	'tooth'  => 'teeth',
	'person' => 'people',
	'valve'  => 'valves',
	'zombie' => 'zombies',
];

$uncountable = [
	'sheep',
	'fish',
	'deer',
	'series',
	'species',
	'money',
	'rice',
	'information',
	'equipment',
	'data',
	'chaos',
	'food',
	'rain'
];

$uncountable = array_flip($uncountable);

function pluralize( $string )
{
	global $plural, $singular, $irregular, $uncountable;

	// save some time in the case that singular and plural are the same
	if ( isset( $uncountable[$string] ) )
		return false;

	// check for irregular singular forms
	foreach ( $irregular as $pattern => $result )
	{
		$pattern = '/'. $pattern .'$/i';

		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string);
	}

	// check for matches using regular expressions
	foreach ( $plural as $pattern => $result )
	{
		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string );
	}

	return false;
}

function singularize( $string )
{
	global $plural, $singular, $irregular, $uncountable;

	// save some time in the case that singular and plural are the same
	if ( isset( $uncountable[$string] ) )
		return false;

	// check for irregular plural forms
	foreach ( $irregular as $result => $pattern )
	{
		$pattern = '/'. $pattern .'$/i';

		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string);
	}

	// check for matches using regular expressions
	foreach ( $singular as $pattern => $result )
	{
		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string );
	}

	return false;
}

function synonymous_name( $name )
{
	global $names;

	foreach ($names as $nameCollection) {

		foreach ($nameCollection as $nameVal) {

			if($nameVal == $name) {
				$key = array_search($nameVal, $nameCollection);

				unset($nameCollection[$key]);

				$key = random_key($nameCollection);

				return $nameCollection[$key];
			}
		}
	}

	return false;
}

function synonymous_place( $place )
{
	global $places;

	if ( !empty( $places[$place] ) ) {
		$key = random_key($places[$place]);

		return $places[$place][$key];
	}

	return false;
}

function seperate_suffix_symbols( $word )
{
	$pattern = "/([\w\-]+)(?:'s?)?([,:;!?]*)/";

	preg_match($pattern, $word, $matches);

	return $matches;
}

function ends_with( $haystack, $needle )
{
	$length = strlen( $needle );
	if ( $length == 0 ) {
		return true;
	}

	return ( substr( $haystack, -$length ) === $needle );
}

function random_key( $array )
{
	$keys = array_keys( $array );
	$index = mt_rand( 0, count( $keys ) -1);

	return $keys[$index];
}

function cinenymize( $title = null )
{
	global $movies;
	global $thesaurus;
	global $ignored_words;
	global $vowels;

	if ( empty( $title ) ) {

		$title = $movies[random_key( $movies )];

	}

	$title_words = explode( " ", $title );

	foreach ( $title_words as $index => $word ) {

		$synonym = false;
		$plural_word = false;

		$word = strtolower($word);

		$formatted_word = seperate_suffix_symbols( $word );

		$word = $formatted_word[1];
		$suffix = "". $formatted_word[2];

		if ( !isset( $ignored_words[$word] ) && !is_numeric( $word ) ) {

			if ( $name_word = synonymous_name( $word ) ) {
				//The word is a name, pick a nickname
				$word = ucfirst( $name_word );

			}

			if ( $place_word = synonymous_place( $word ) ) {
				//The word is a place name, pick a nickname
				$word = ucfirst( $place_word );
			}

			if ( !isset( $thesaurus[$word] ) && ( strlen( $word ) >= 6 ) ) {

				if ( ends_with( $word, "er" ) ) {

					$word = substr( $word, 0, -2 );
					$suffix = "er". $suffix;

				} elseif ( ends_with( $word, "est" ) ) {

					$word = substr( $word, 0, -3 );
					$suffix = "est". $suffix;

				}
			}

			if ( !$name_word && !$place_word ) {

				//Check if word exists in the thesaurus
				if ( !isset( $thesaurus[$word] ) ) {
					$word = singularize( $word );
					$plural_word = true;
				}

				if (isset($thesaurus[$word])) {

					//Grab a synonym of the root word
					$synonym = random_key( $thesaurus[$word] );

					$word = $thesaurus[$word][$synonym];

					//Pluralize the synonym if the original word was plural
					if ($plural_word) {

						$word = pluralize( $word );

					}

				}
			}

			//Replace the original word in the title and restore any suffixes
			if ( $synonym || $name_word || $place_word ) {
				$title_words[$index] = ucwords( $word . $suffix );

				if ($index > 0) {
					if ( strtolower($title_words[$index - 1]) == 'a' && in_array( substr( $word, 0, 1 ), $vowels ) ) {
						$title_words[$index - 1] = $title_words[$index - 1] . 'n';
					} elseif ( strtolower($title_words[$index - 1]) == 'an' && !in_array( substr( $word, 0, 1 ), $vowels ) ) {
						$title_words[$index - 1] = substr($title_words[$index - 1], 1);
					}
				}
			}
		}
	}

	$new_title = implode( ' ', $title_words );

	if ( php_sapi_name() == "cli" ) {

		cli( $new_title, $title );

	} else {

		web_view( $new_title, $title );

	}
}

function web_view( $new_title, $original_title )
{
	include 'site.php';
}

function cli( $new_title, $title )
{
//	system( 'reset' );
	echo "\n  What's this movie: \"{$new_title}\"?\n  Answer: ";
	$handle = fopen( "php://stdin", "r" );
	$line = fgets( $handle );

	$formatted_line = preg_replace('/[\\W_]/', '', strtolower( $line ) );
	$formatted_title = preg_replace('/[\\W_]/', '', strtolower( $title ));

	if ( $formatted_line == $formatted_title) {

		echo "\n  \"{$title}\" is correct!\n";

	} else {

		echo "\n  Wrong! The answer is \"{$title}\"\n";

	}

	echo "  Enter a new title, or press enter to generate one: ";
	$handle = fopen( "php://stdin", "r" );
	$line = fgets( $handle );

	cinenymize(trim($line));
}

if ( !empty( $argv[1] ) ) {
	$title = $argv[1];
} elseif ( !empty( $_GET['title'] ) ) {
	$title = $_GET['title'];
} else {
	$title = $movies[random_key( $movies )];
}

cinenymize($title);
