<?php

/*
 * Helper
 * Date au format francais + affichage temps écoulé plutot que la date
 *
 */
function setDate ($timestamp, $lang, $fullDate = false) {

	$today = new DateTime();

	$diff = l::get('date.justnow');

	$diffMinutes = ( $today->getTimestamp() - $timestamp ) / 60;

	// si moins d'1 minute
	if ( $diffMinutes < 1 ) {
		return $diff;
	}
	// si moins de 60min
	else if ( $diffMinutes < 60 ){
		// affiche les minutes
		$diff = floor( $diffMinutes ) . 'min';
	} else {
		$diffHeures = $diffMinutes / 60;
		// sinon, si moins de 24h
		if( $diffHeures < 24 ){
			// affiche heures
			$diff = floor( $diffHeures ) . 'h';
		} else {
			$diffJours = $diffHeures / 24;
			// sinon, si moins de 30 jours
			if( $diffJours < 30 ){
				// affiche jours
				$diff = (floor($diffJours) == 1) ? floor($diffJours) . ' ' . l::get('date.day'):floor($diffJours) . ' ' . l::get('date.days');
			} else {
				$diffMois = $diffJours / 30;
				// sinon, si moins de 12 mois
				if( $diffMois < 12 ){
					$diff = (floor($diffMois) == 1) ? floor($diffMois) . ' ' . l::get('date.month') : floor($diffMois) . ' ' . l::get('date.months');
				} else {

					$diffAn = floor($diffMois / 12);
					$diffResteMois = $diffMois % 12;

					$diff = ($diffAn == 1) ? $diffAn . ' ' . l::get('date.year') : $diffAn . ' ' . l::get('date.years');
					if ($diffResteMois !== 0) {
						$diff .= ', ' . ((floor($diffResteMois) == 1) ? floor($diffResteMois) . ' ' . l::get('date.month') : floor($diffResteMois) . ' ' . l::get('date.months'));
					}
				}
			}
		}
	}

	return $diff;
}

function setFullDate ($timestamp, $lang) {
	$moisFr = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'décembre');
	$month = Date('F', $timestamp);
	$year = Date('Y', $timestamp);
	if ($lang === 'fr') {
		$month = $moisFr[Date('m', $timestamp) - 1];
	}
	$date = ' (' . $month . ' ' . $year . ')';
	return $date;
}



 ?>