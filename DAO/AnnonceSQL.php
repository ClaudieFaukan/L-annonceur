<?php

    class AnnonceSQL
    {
        public function getAll()
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query('SELECT * FROM annonce');
                $tableau = [];
                while ($annonce = $stmt->fetchObject()) {
                    $tableau[] = $annonce;
                }
                print_r($tableau);
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function update(int $id_user, int $id_rubrique, string $en_tete, string $corp, int $id_annonce)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('UPDATE annonce SET ID_USER=:id_user, ID_RUBRIQUE=:id_rubrique, EN_TETE=:en_tete, CORP=:corp WHERE ID_ANNONCE = :id_annonce');
                $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmt->bindParam(':id_rubrique', $id_rubrique, PDO::PARAM_INT);
                $stmt->bindParam(':en_tete', $en_tete, PDO::PARAM_STR);
                $stmt->bindParam(':corp', $corp, PDO::PARAM_STR);
                $stmt->bindParam(':id_annonce', $id_annonce, PDO::PARAM_STR);
                $stmt->execute();

                return print $stmt->rowCount()."\n";
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function getByRubrique(int $rubrique)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query("SELECT* FROM annonce WHERE ID_RUBRIQUE= {$rubrique}");
                $tableau = [];
                while ($tab = $stmt->fetchObject()) {
                    $tableau[] = $tab;
                }
                print_r($tableau);
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function getByUtilisateur(int $utilisateur)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query("SELECT* FROM annonce WHERE ID_USER= {$utilisateur}");
                $tableau = [];
                while ($tab = $stmt->fetchObject()) {
                    $tableau[] = $tab;
                }
                print_r($tableau);
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function delete(int $id_annonce)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM annonce WHERE ID_ANNONCE= :id_annonce');
                $stmt->bindParam(':id_annonce', $id_annonce, PDO::PARAM_INT);
                $stmt->execute();

                return print $stmt->rowCount();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }
    }
