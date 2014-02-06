<?php

/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * 
 * @author Jacques Bouthier <jacques@jack31.net>
 * @author Schplurtz le Déboulonné <schplurtz@laposte.net>
 */
$lang['menu']                  = 'Administration du plugin de News';
$lang['btn_prune']             = 'Supprimer les sélections';
$lang['btn_restore']           = 'Restaurer';
$lang['btn_review']            = 'Réviser les suppressions';
$lang['btn_confirm']           = 'Confirmer les suppressions';
$lang['invalid']               = 'Entrée invalide détectée !';
$lang['btn_generate']          = 'Diffuser';
$lang['btn_info']              = 'afficher/cacher l\'aide';
$lang['btn_confirmfeed']       = 'Confirmer le sousflux';
$lang['select_feed']           = 'Sélection de sousflux';
$lang['no_permission']         = 'Vous n\'avez pas la permission de créer un nouveau flux';
$lang['instructions']          = '<p><h3>Aide</h3>Pour supprimer un flux, cliquer sur la case à cocher "Supprimer" en dessous du nom du flux. Ensuite cliquer sur "Supprimer les sélections" en haut de l\'écran. Vous pouvez supprimez plusieurs flux à la fois en une seule opération en cochant plusieurs cases.  La table des flux sera mise à jour pour réfléter les suppressions, mais les suppressions ne seront effectives qu\'après avoir cliqué sur le bouton "Confirmer les Suppressions", moment auquel la base de données sera mise à jour. Cliquer sur "Restaurer" avant de confirmer remettra la table dans son état initial.</p> 
<p> 
Une suppression affecte seulement le statut actuel de la base de données. Vous devez également supprimer les balises de flux des pages qui l\'ont généré. Si les balises de flux restent sur une page, le flux de cette page sera regénéré lors de la prochaine mise à jour du cache par DokuWiki</p>
<p>Si votre site autorise plus d\'un flux, on y fait référence ici sous le terme de sous-flux. Pour modifier ou générer un sousflux, sélectionner le sous flux par son titre à partir du menu déroulant "Sélection de sousflux" puis xliquer sur le bouton "Confirmer le sousflux". Puis, poursuivre comme indiqué plus haut.</p>
';
