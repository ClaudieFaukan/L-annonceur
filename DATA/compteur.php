<?php

//FONCTION VUE ACCEUIL
function ajouter_vue()
{
    $fichier = 'DATA/compteur_acceuil';
    $fichier_journalier = $fichier.'_'.date('Y-m-d');
    incrementer_compteur($fichier);
    incrementer_compteur($fichier_journalier);
}

function incrementer_compteur($fichier)
{
    $vue = 1;
    if (file_exists($fichier)) {
        $vue = (int) file_get_contents($fichier);
        ++$vue;
        file_put_contents($fichier, $vue);
    } else {
        file_put_contents($fichier, '1');
    }
}

// RETOUR NOMBRE VUE ACCEUIL TOTAL

function nombre_vues_acceuil(): string
{
    $fichier = 'DATA/compteur_acceuil';

    return file_get_contents($fichier);
}

//.********************************* FONCTION PAR ID_ANNONCE ********************************.

function ajouter_vue_annonce()
{
    $fichier = 'DATA/compteur_annonce_ID';
    $fichier_annonce = $fichier.'_'.$_POST['ID_ANNONCE'];
    incrementer_compteur($fichier_annonce);
}
function nombre_vues_annonce(): string
{
    $fichier = 'DATA/compteur_annonce_ID';
    $fichier_annonce = $fichier.'_'.$_POST['ID_ANNONCE'];

    return file_get_contents($fichier_annonce);
}

function nbr_vue_today()
{
    $fichier = 'DATA/compteur_acceuil';
    $fichier_journalier = $fichier.'_'.date('Y-m-d');

    return file_get_contents($fichier_journalier);
}
