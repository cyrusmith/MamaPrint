<?php

function replacePagebreakWithCut($content) {
    return preg_replace("#<!-- pagebreak -->#", "<a name='articlecut'></a>", $content);
}

function extractBeforePagebreak($content) {
    return $content;
}