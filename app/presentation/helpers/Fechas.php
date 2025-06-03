<?php

/**
 *
 * @param string $utcString 
 * @param string $format     
 * @return string          
 */
function aHoraElSalvador(string $utcString, string $format = 'Y-m-d H:i'): string {
    $dt = new \DateTime($utcString, new \DateTimeZone('UTC'));
    $dt->setTimezone(new \DateTimeZone('America/El_Salvador'));
    return $dt->format($format);
}
