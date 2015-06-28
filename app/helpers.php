<?php

function replacePagebreakWithCut($content) {
    return preg_replace("#<!-- pagebreak -->#", "<a name='articlecut'></a>", $content);
}

function extractBeforePagebreak($content) {
    return $content;
}

function toMoscowTZ(DateTime $utcTime) {
    $moscowTz = new \DateTimeZone('Europe/Moscow');
    $utcTime->setTimezone($moscowTz);
    return $utcTime;

}