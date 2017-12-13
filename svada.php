<?php
//phpcs:ignoreFile

header( 'Content-Type: text/html;charset=utf-8' );

$mySvada = new Svada();

$page_title = $mySvada->get_title();

?><!DOCTYPE html>
<html lang="nb-NO">
<head>
	<meta charset="utf-8">
	<title><?php echo $page_title; ?></title>

<style>

body {
	font-family: Georgia, serif;
}

h1, h2 {
	font-weight: normal;
	text-transform: uppercase;
}

h1 {
	font-size: 200%;
}

h2 {
	font-size: 150%;
}

p {
	max-width: 40em;
	line-height: 1.4em;
}



</style>
</head>
<body>
<?php


echo '<h1>' . $page_title . '</h1>';

for ( $i = 0, $num_paragraphs = mt_rand( 1, 5 ); $i < $num_paragraphs; $i++ ) {
	echo '<p>' . $mySvada->get_paragraph() . '</p>';
}

for ( $section = 0, $num_sections = mt_rand( 1, 5 ); $section < $num_sections; $section++ ) {
	echo '<h2>' . $mySvada->get_title() . '</h2>';
	for ( $paragraph = 0, $num_paragraphs = mt_rand( 1, 5 ); $paragraph < $num_paragraphs; $paragraph++ ) {
		echo '<p>' . $mySvada->get_paragraph() . '</p>';
		$list_rand = mt_rand( 0, 9 );
		if ( 1 == $list_rand ) {
			$list = $mySvada->get_list();
			echo '<ul><li>' . implode( '</li><li>', $list ) . '</li></ul>';
		} elseif ( 2 == $list_rand ) {
			$list = $mySvada->get_list();
			echo '<ol><li>' . implode( '</li><li>', $list ) . '</li></ol>';
		}
	}
}

?>

</body>
</html>

<?php
class Svada {

	protected $_strings;

	public function __construct() {
		$this->_strings = json_decode('[["Gitt","Under hensyntagen til","I lys av","Vedrørende","Grunnet","I betraktning av","Forutsatt","Med utgangspunkt i","I forhold til","Sett hen til","I henhold til","Med tanke på","Uavhengig av","Sett på bakgrunn av","Sammenholdt med","På grunn av","Med hensyn til","Under forutsetning av","Etter en totalvurdering av","Uten hensyn til","Avhengig av","På grunnlag av","I og med","Under henvisning til"],["en integrert","en optimal","en sømløs","en implisitt","en proaktiv","en betydelig","en økt","en vesentlig","en ikke ubetydelig","en kostnadseffektiv","en avtagende","en vedvarende","en tiltagende","en gjeldende","en helhetlig","en manglende","en særlig","en løpende","en langsiktig","en bærekraftig","en resultatorientert","en tverrfaglig","en kommunikativ","en inkluderende"],["målsetting","effekt","struktur","agenda","tidshorisont","overveielse","mobilitet","treffsikkerhet","innsats","kvalitetssikring","problematikk","ressursbruk","avveining","avklaring","implementering","styringsinnsats","innovasjon","effektivisering","kvalitetsheving","utvikling","måloppnåelse","oppgaveløsning","arbeidsmodell","organisasjon"],["synliggjøres","tas det høyde for","iverksettes","identifiseres","initieres","lokaliseres","kommuniseres","styrkes","realiseres","effektueres","forankres","maksimeres","konkretiseres","tilgjengeliggjøres","utvides","dokumenteres","spores","innhentes","revitaliseres","stabiliseres","genereres","stimuleres","balanseres","ivaretas"],["potensialet","risikofaktorene","fokus","synergieffekten","incitamentet","forankringen","insentivene","innsatsen","erfaringsutvekslingen","informasjonsflyten","kriteriene","strategien","økningen","egenarten","tilstedeværelsen","oppfølgingen","resultatene","kunnskapene","betydningen","kompetansehevingen","instrumentet","scenarioet","spisskompetansen","relasjonene"],["innenfor rammen av","som en følge av","for så vidt gjelder","med henblikk på","i forhold til","hva angår","parallelt med","i relasjon til","i tilknytning til","på bakgrunn av","avhengig av","hva gjelder","eller sagt på en annen måte:","på samme måte som","i motsetning til","innenfor","i tillegg til","gjennom","ut fra","med sikte på","på tvers av","på linje med","utenom","i forlengelsen av"],["en samlet vurdering","forholdene","konseptet","ressurssituasjonen","tilgjengeliggjøringen","føringene","evalueringen","implementeringen","kjernevirksomheten","visjonen","satsingsområdet","problemstillingen","beskaffenheten","vesentligheten","egenarten","målområdet","verdiene","realitetsorienteringen","resultatoppnåelsen","behovene","løsningen","parametrene","ressursinnsatsen","konsekvensaspektet"]]');
	}


	public function get_title() {
		$parts = array();

		$cols = array( 4, 3, 5, 6 );
		foreach ( $cols as $col ) {
			$key = array_rand( $this->_strings[$col] );
			$parts[] = $this->_strings[ $col ][ $key ];
		}

		return implode( ' ', $parts );
	}

	public function get_paragraph() {

		$sentences = array();

		$num_sentences = mt_rand( 3, 10 );

		for ( $i = 0; $i < $num_sentences; $i++ ) {
			$sentences[] = $this->get_sentence();
		}
		
		return implode( ' ', $sentences );

	}

	public function get_sentence() {

		$phraseparts = array();
		for ( $col = 0, $numcols = count( $this->_strings); $col < $numcols; $col++ ) {
			$key = array_rand( $this->_strings[$col] );
			$phraseparts[] = $this->_strings[ $col ][ $key ];
		}

		$vada = implode( ' ', $phraseparts ) . '.';

		return $vada;
	}

	public function get_list() {
		$cols = array( 2, 4, 6 );
		$col = $cols[ array_rand( $cols ) ];

		$num_terms = mt_rand( 2, 7 );
		$term_keys = array_rand( $this->_strings[ $col ], $num_terms );

		$terms = array();

		foreach ( $term_keys as $key ) {
			$terms[] = $this->_strings[ $col ][ $key ];
		}

		return $terms;
	}

}
