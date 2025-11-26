<?php
const AI_CATEGORIES = [
    'Élelmiszer',
    'Utazás',
    'Számlák',
    'Szórakozás',
    'Autó',
    'Egyéb',
];

/**
 * "AI" kategorizálás – jelenleg egy egyszerű kulcsszó alapú logika,
 * de a helye és a paraméterezése pont olyan, mintha valódi AI hívás lenne.
 *
 * Visszatérés: a kategória neve a fenti listából.
 */
function categorizeDescription(string $description): string
{
    $text = mb_strtolower($description, 'UTF-8');

    // --- Autó / tankolás / szerviz ---
    if (mb_strpos($text, 'mol') !== false ||
        mb_strpos($text, 'tankolás') !== false ||
        mb_strpos($text, 'benzin') !== false ||
        mb_strpos($text, 'szerviz') !== false ||
        mb_strpos($text, 'autó') !== false) {
        return 'Autó';
    }

    // --- Élelmiszer / bevásárlás ---
    if (mb_strpos($text, 'spar') !== false ||
        mb_strpos($text, 'aldi') !== false ||
        mb_strpos($text, 'lidl') !== false ||
        mb_strpos($text, 'tesco') !== false ||
        mb_strpos($text, 'bevásárlás') !== false ||
        mb_strpos($text, 'élelmiszer') !== false ||
        mb_strpos($text, 'bolt') !== false) {
        return 'Élelmiszer';
    }

    // --- Szórakozás ---
    if (mb_strpos($text, 'mozi') !== false ||
        mb_strpos($text, 'cinema') !== false ||
        mb_strpos($text, 'sörözés') !== false ||
        mb_strpos($text, 'kocsma') !== false ||
        mb_strpos($text, 'koncert') !== false ||
        mb_strpos($text, 'játék') !== false) {
        return 'Szórakozás';
    }

    // --- Számlák ---
    if (mb_strpos($text, 'villany') !== false ||
        mb_strpos($text, 'áram') !== false ||
        mb_strpos($text, 'gáz') !== false ||
        mb_strpos($text, 'víz') !== false ||
        mb_strpos($text, 'internet') !== false ||
        mb_strpos($text, 'bérleti díj') !== false ||
        mb_strpos($text, 'lakbér') !== false ||
        mb_strpos($text, 'számla') !== false) {
        return 'Számlák';
    }

    // --- Utazás ---
    if (mb_strpos($text, 'bkv') !== false ||
        mb_strpos($text, 'máv') !== false ||
        mb_strpos($text, 'busz') !== false ||
        mb_strpos($text, 'jegy') !== false ||
        mb_strpos($text, 'bérlet') !== false ||
        mb_strpos($text, 'taxi') !== false ||
        mb_strpos($text, 'utazás') !== false) {
        return 'Utazás';
    }

    // Ha semmi nem illik, fallback:
    return 'Egyéb';
}

