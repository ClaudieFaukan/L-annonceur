<?php

 require_once dirname(__DIR__).'/Domain/Utilisateur.php';

 interface UtilisateurDAO
 {
     public function insert(Utilisateur $utilisateur,$confirmKey);

     public function identifier(Utilisateur $utilisateur);

     public function delete(Utilisateur $utilisateur);
 }
