<?php

    class Rubrique implements JsonSerializable
    {
        private $rubrique_id;
        private $libelle;

        // Construction de données
        public function __construct($libelle, $rubrique_id = -1)
        {
            $this->libelle = $libelle;
            $this->rubrique_id = $rubrique_id;
        }

        // Tostring
        public function __toString()
        {
            return ' LIBELLE : '.$this->libelle.' RUBRIQUE_ID : '.$this->rubrique_id;
        }

        // Autoriser la lecture d'une donnée 'private'
        public function getId()
        {
            return $this->rubrique_id;
        }

        public function getLibelle()
        {
            return $this->libelle;
        }

        // Modifier une donnée 'private'

        public function setLibelle($libelle)
        {
            $this->libelle = $libelle;
        }

        public function setRubrique_id($rubrique_id)
        {
            $this->rubrique_id = $rubrique_id;

            return $this;
        }

        public function jsonSerialize()
        {
            return[
                'libelle' => $this->libelle,
                'id' => $this->rubrique_id,
            ];
        }
    }
