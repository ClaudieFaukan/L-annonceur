<?php

    class RubriqueMySQL
    {
        public function getAll()
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query('SELECT * FROM rubrique');
                $tableau = [];
                while ($rubrique = $stmt->fetchObject()) {
                    $tableau[] = $rubrique;
                }
                print_r($tableau);
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function update(string $libelle, int $id_rubrique)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('UPDATE rubrique SET LIBELLE = :libelle WHERE ID_RUBRIQUE = :id_rubrique');
                $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
                $stmt->bindParam(':id_rubrique', $id_rubrique, PDO::PARAM_INT);
                $stmt->execute();

                return print $stmt->rowCount()."\n";
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function delete(string $libelle)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM rubrique WHERE LIBELLE = :libelle');
                $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
                $stmt->execute();

                return print $stmt->rowCount()."\n";
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }
    }
