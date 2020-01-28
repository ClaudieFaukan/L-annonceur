<?php

    require_once 'Utilisateur.php';
    require_once 'Rubrique.php';
     class Annonce implements JsonSerializable
     {
         private $id_annonce;
         private $utilisateur;
         private $rubrique;
         private $en_tete;
         private $corp;
         private $date_depot;
         private $date_validite;

         // Construction
         public function __construct(Utilisateur $user, Rubrique $rubrique, $enTete, $corp, $dateDepot = 0, $dateValidite = 0, $id_annonce = -1)
         {
             $this->utilisateur = $user;
             $this->rubrique = $rubrique;
             $this->en_tete = $enTete;
             $this->corp = $corp;
             $this->date_depot = $dateDepot;
             $this->date_validite = $dateValidite;
             $this->id_annonce = $id_annonce;
         }

         //Tostring
         public function __toString()
         {
             return
            $this->en_tete.
            $this->corp.
            $this->date_depot.
            $this->date_validite;
         }

         //Getters
         public function getId_annonce()
         {
             return $this->id_annonce;
         }

         public function getUser()
         {
             return $this->utilisateur;
         }

         public function getRubrique()
         {
             return $this->rubrique;
         }

         public function getEnTete()
         {
             return $this->en_tete;
         }

         public function getCorps()
         {
             return $this->corp;
         }

         public function getDate_depot()
         {
             return $this->date_depot;
         }

         public function getDateValidite()
         {
             return $this->date_validite;
         }

         //Setters
         public function setEnTete($enTete)
         {
             $this->en_tete = $enTete;
         }

         public function setCorps($corps)
         {
             $this->corp = $corps;
         }

         public function setDateDepot($dateDepot)
         {
             $this->date_depot = $dateDepot;
         }

         public function setDateValidite($dateValidite)
         {
             $this->date_validite = $dateValidite;
         }

         /**
          * Set the value of id_annonce.
          *
          * @param mixed $id_annonce
          *
          * @return self
          */
         public function setId_annonce($id_annonce)
         {
             $this->id_annonce = $id_annonce;

             return $this;
         }

         public function jsonSerialize()
         {
             return[
                 'En_tete' => $this->en_tete,
                 'Corp' => $this->corp,
                 'Date_validite' => $this->date_validite,
                 'Id_annonce' => $this->id_annonce,
             ]
            ;
         }
     }
