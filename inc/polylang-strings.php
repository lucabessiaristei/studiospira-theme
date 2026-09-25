<?php

// Registers the theme's pll_e()/pll__() strings with Polylang so they show
// up under Lingue > Traduzioni — without this, calling pll_e() on a string
// Polylang has never heard of just echoes the Italian text unconditionally,
// regardless of the current language.

if (!defined('ABSPATH')) exit;

add_action('init', function() {
    if (!function_exists('pll_register_string')) return;

    $group = 'Studio Spira';

    $strings = [
        'A cura di',
        'Anno',
        'Anno inizio',
        'Anno fine',
        'Articoli',
        'Budget',
        'Categoria',
        'Chiudi',
        'Cognome',
        'Committenza',
        "Destinazione d'uso",
        'Cookie Policy',
        'Editore',
        'Email',
        'Esplora intervento',
        'Filtri',
        'Fondazione',
        'Galleria',
        'Hai un edificio storico da restaurare?',
        'Indirizzo',
        'Interventi in evidenza',
        'Interventi',
        'Invia',
        'ISBN',
        'Leggi articolo',
        'Menu',
        'Messaggio',
        'Nessun intervento corrispondente ai filtri',
        'Nome',
        'Pagine',
        'Parlaci del tuo edificio',
        'Privacy policy',
        'Privacy Policy',
        'Prossimo intervento',
        'Pubblicazioni',
        'Reset filtri',
        'Scopri chi siamo',
        'Sede',
        'Telefono',
        'Tutti gli interventi',
        "Con l'invio del presente modulo acconsento al trattamento dei dati unicamente per la richiesta in oggetto. Consenso esplicito secondo il GDPR 679/2016. Leggi l'informativa sulla",
        'Messaggio inviato, grazie! Ti risponderemo il prima possibile.',
        'Non è stato possibile inviare il messaggio. Controlla i campi obbligatori e riprova.',
        'Compra',
        'Foto precedente',
        'Foto successiva',
        'Interventi di restauro e recupero',
        'Posizione',
        'Scarica',
        'Servizio',
        'Servizi',
        'Team',
        'Nuovo messaggio dal form contatti',
        'Ciao %s,',
        'Grazie per averci scritto. Abbiamo ricevuto il tuo messaggio e ti risponderemo il prima possibile.',
        'Messaggio ricevuto',
        'Abbiamo ricevuto il tuo messaggio',
    ];

    foreach ($strings as $string) {
        pll_register_string($string, $string, $group);
    }
});
