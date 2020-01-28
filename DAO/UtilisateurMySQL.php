<?php

    class UtilisateurDeMySQL
    {
        public function getAll()
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query('SELECT * FROM utilisateur');
                $tableau = [];
                while ($rubrique = $stmt->fetchObject()) {
                    $tableau[] = $rubrique;
                }

                return print_r($tableau);
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function rechercherUtilisateur(string $nom)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query("SELECT * FROM utilisateur WHERE NOM = '{$nom}'");
                $tableau = [];
                while ($rubrique = $stmt->fetchObject()) {
                    $tableau[] = $rubrique;
                }

                return print_r($tableau);
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function update(string $nom, string $prenom, string $mail, string $mdp, int $id_user)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('UPDATE utilisateur SET NOM = :nom, PRENOM = :prenom, MAIL= :mail , MDP= :mdp WHERE ID_USER = :id_user');
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
                $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmt->execute();

                return print $stmt->rowCount();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }
    }
