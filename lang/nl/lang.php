<?php

/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * 
 * @author Rene <wllywlnt@yahoo.com>
 */
$lang['menu']                  = 'News plugin beheer';
$lang['btn_prune']             = 'Verwijder selecties';
$lang['btn_restore']           = 'Herstel';
$lang['btn_review']            = 'Bekijk verwijderingen';
$lang['btn_confirm']           = 'Bevestig verwijderingen';
$lang['invalid']               = 'Niet toegestane invoer ontdekt!';
$lang['btn_generate']          = 'Genereer Feed';
$lang['btn_info']              = 'Toon/ Verberg Help';
$lang['btn_confirmfeed']       = 'Bevestig Subfeed';
$lang['select_feed']           = 'Kies Subfeed';
$lang['no_permission']         = 'Je hebt geen toestemming om News feeds te genereren of bij te werken.';
$lang['instructions']          = '<p><h3>Help</h3>Om een feed te verwijderen, kies "Verwijder" naast de naam van de feed. Kies daarna 
de "Verwijder selectie" knop bovenin het scherm. Je kunt meerdere feeds in een verwijder actie uitvoeren
door meer dan een regel te selecteren. De tabel zal direct worden bijgewerkt
maar de verwijderde regels worden pas definitief verwijderd nadat dit is bevestigd door te klikken op "Bevestig verwijderen", waarna de database
definitief wordt bijgewerkt. Indien je op "Herstel" klikt voor bevestiging verwijderen dan wordt de tabel in originele staat herstelt.</p>
<p>
Een verwijdering heeft alleen effect op de huidige gegevens in de database. Je moet ook de plugin syntax verwijderen van de pagina
die  
de feed genereert. Als de plugin syntax op de pagina blijft, dan zal de feed weer worden hersteld als de Dokuwiki cache wordt bijgewerkt. 
</p>    
<p>
Indien je site meer dan een feed toestaat, wordt er naar verwezen als "subfeeds".  Indien je een subfeed wil wijzigen en/of maken, kies dan de subfeed met
de titel van het "Kies Subfeed" menu en klik op de " Bevestig Subfeed" knop. Ga verder als hierboven beschreven.
</p> 
';
