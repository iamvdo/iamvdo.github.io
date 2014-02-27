<?php

/*
 * Helper
 * Date au format francais + affichage temps écoulé plutot que la date
 *
 */
function setDateFr ($timestamp, $fullDate = false) {

	$moisFr = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'décembre');
	$today = new DateTime();

	$diff = 'à l\'instant';

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
				$diff = (floor($diffJours) == 1) ? floor($diffJours) . ' jour':floor($diffJours) . ' jours';
			} else {
				$diffMois = $diffJours / 30;
				// sinon, si moins de 12 mois
				if( $diffMois < 12 ){
					$diff = floor($diffMois) . ' mois';
				} else {

					$diffAn = floor($diffMois / 12);
					$diffResteMois = $diffMois % 12;

					$diff = ($diffAn == 1) ? $diffAn . ' an' : $diffAn . ' ans';
					if ($diffResteMois !== 0) {
						$diff .= ', ' . $diffResteMois . ' mois';
					}

					if ( $fullDate ) {
						$diff .= ' (' . $moisFr[Date('m', $timestamp) - 1] . ' ' . Date('Y', $timestamp) . ')';
					}
				}
			}
		}
	}

	return $diff;
}
 ?>