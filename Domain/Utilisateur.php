<?php

    class Utilisateur implements JsonSerializable
    {
        /**
         * @var string Mot de passe de l'utilisateur
         */
        private $mdp;
        /**
         * @var string Nom de l'utilisateur
         */
        private $nom;

        /**
         * @var int Identifiant numérique de l'utilisateur générer par la database en autoincrement
         */
        private $id_user;

        private $email;

        private $admin;

        private $prenom;

        private $actif;

        // Construction de données
        public function __construct($nom = '', $prenom = '', $mdp = '', $email, $admin = 'non', $id_user = -1)
        {
            $this->mdp = $mdp;
            $this->nom = $nom;
            $this->email = $email;
            $this->id_user = $id_user;
            $this->admin = $admin;
            $this->prenom = $prenom;
        }

        // Tostring
        public function __toString()
        {
            return $this->nom.$this->mdp;
        }

        // Autoriser la lecture d'une donnée 'private'

        public function getMdp()
        {
            return $this->mdp;
        }

        public function getNom()
        {
            return $this->nom;
        }

        public function getId_user()
        {
            return $this->id_user;
        }

        public function setId_user($curId)
        {
            $this->id_user = $curId;

            return $this;
        }

        // Modifier une donnée 'private'

        public function setMdp($mdp)
        {
            $this->mdp = $mdp;
        }

        public function setNom($nom)
        {
            $this->nom = $nom;
        }

        // modifie le mdp en crypt sha256
        public function getSha256()
        {
            $sha = hash('sha256', $this->mdp);

            return $this->mdp = $sha;
        }

        /**
         * Get the value of email.
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set the value of email.
         *
         * @param mixed $email
         *
         * @return self
         */
        public function setEmail($email)
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get the value of admin.
         */
        public function getAdmin()
        {
            return $this->admin;
        }

        /**
         * Set the value of admin.
         *
         * @param mixed $admin
         *
         * @return self
         */
        public function setAdmin($admin)
        {
            $this->admin = $admin;

            return $this;
        }

        /**
         * Get the value of prenom.
         */
        public function getPrenom()
        {
            return $this->prenom;
        }

        /**
         * Set the value of prenom.
         *
         * @param mixed $prenom
         *
         * @return self
         */
        public function setPrenom($prenom)
        {
            $this->prenom = $prenom;

            return $this;
        }

        /**
         * Get the value of actif.
         */
        public function getActif()
        {
            return $this->actif;
        }

        /**
         * Set the value of actif.
         *
         * @param mixed $actif
         *
         * @return self
         */
        public function setActif($actif)
        {
            $this->actif = $actif;

            return $this;
        }

        public function jsonSerialize()
        {
            return [
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'admin' => $this->admin,
                'id' => $this->id_user,
            ];
        }
    }
