<?php

include_once dirname(__DIR__).'/Domain/Annonce.php';
require_once 'AnnonceDAO.php';
require_once 'RubriqueDAO.php';
require_once 'UtilisateurDAO.php';

class MySQLAnnonceDAO implements AnnonceDAO
{
    public function insert(Annonce $annonce)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('INSERT INTO annonce (ID_USER,ID_RUBRIQUE,EN_TETE,CORP,DATE_DEPOT,DATE_VALIDITE) 
                VALUES (:id_user,:id_rubrique,:en_tete,:corp,:date_depot,:duree)');

            $stmt->bindValue(':id_user', $annonce->getUser()->getId_user(), PDO::PARAM_INT);
            $stmt->bindValue(':id_rubrique', $annonce->getRubrique()->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':en_tete', $annonce->getEnTete(), PDO::PARAM_STR);
            $stmt->bindValue(':corp', $annonce->getCorps(), PDO::PARAM_STR);
            $stmt->bindValue(':duree', $annonce->getDateValidite(), PDO::PARAM_STR);
            $stmt->bindValue(':date_depot', $annonce->getDate_depot(), PDO::PARAM_STR);
            $stmt->execute();
            $curId = ConnexionBdd::getConnexion()->lastInsertId();
            $annonce->setId_annonce($curId);

            return   print "L' Id_annonce est le ".$curId."\n";
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function delete($id_annonce)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM annonce WHERE ID_ANNONCE = :id_annonce LIMIT 1 ');
            $stmt->bindValue(':id_annonce', $id_annonce, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    /**
     *  ! MODIFICATION DE LA FUNCTION DELETE !
     *
     * @param mixed $id_annonce
     * @param mixed $modif_id_rubrique
     * @param mixed $modif_date_validite
     */
    // public function delete(Annonce $annonce)
    // {
    //     try {
    //         $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM annonce WHERE ID_ANNONCE = :id_annonce LIMIT 1 ');
    //         $stmt->bindValue(':id_annonce', $annonce->getId_annonce(), PDO::PARAM_INT);
    //         $stmt->execute();

    //         return print $stmt->rowCount()."\n";
    //     } catch (BDDException $e) {
    //         echo $e->getMessage()."\n";
    //         echo (int) $e->getCode()."\n";

    //         return -1;
    //     }
    // }

    public function update(Annonce $annonce)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('UPDATE  annonce SET EN_TETE=:en_tete , CORP=:corp, ID_RUBRIQUE=:id_rubrique , DATE_VALIDITE=:date_validite  WHERE ID_ANNONCE=:id_annonce');
            $stmt->bindValue(':en_tete', $annonce->getEnTete(), PDO::PARAM_STR);
            $stmt->bindValue(':corp', $annonce->getCorps(), PDO::PARAM_STR);
            $stmt->bindValue(':id_rubrique', $annonce->getRubrique()->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':date_validite', $annonce->getDateValidite(), PDO::PARAM_STR);
            $stmt->bindValue(':id_annonce', $annonce->getId_annonce(), PDO::PARAM_INT);

            return $stmt->execute();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    /**
     * ! MODIFICATION FUNCTION UPDATE SI ROLLBACK VERIFIER FUNCTION ANNONCE DAO !!
     */
    // public function update(Annonce $annonce, string $modif_entete, string $modif_corp)
    // {
    //     try {
    //         $stmt = ConnexionBdd::getConnexion()->prepare('UPDATE  annonce SET EN_TETE=:en_tete, CORP=:corp  WHERE ID_ANNONCE=:id_annonce');
    //         $stmt->bindValue(':en_tete', $modif_entete, PDO::PARAM_STR);
    //         $stmt->bindValue(':corp', $modif_corp, PDO::PARAM_STR);
    //         $stmt->bindValue(':id_annonce', $annonce->getId_annonce(), PDO::PARAM_INT);
    //         $stmt->execute();

    //         return print $stmt->rowCount()."\n";
    //     } catch (BDDException $e) {
    //         echo $e->getMessage()."\n";
    //         echo (int) $e->getCode()."\n";

    //         return -1;
    //     }
    // }

    public function getByRubrique(Rubrique $rubrique)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query("SELECT DISTINCT annonce.ID_ANNONCE, annonce.ID_USER, annonce.ID_RUBRIQUE, annonce.EN_TETE, 
            annonce.CORP, annonce.DATE_VALIDITE, annonce.DATE_DEPOT, utilisateur.NOM, rubrique.LIBELLE FROM annonce
            INNER JOIN utilisateur ON annonce.ID_USER = utilisateur.ID_USER
            INNER JOIN rubrique ON annonce.ID_RUBRIQUE = rubrique.ID_RUBRIQUE
            WHERE annonce.ID_RUBRIQUE = {$rubrique->getId()}");
            $stmt->execute();

            return $stmt->fetchAll();
            // while ($rubrique = $stmt->fetchObject()) {
            //     $tableau[] = $rubrique;
            // }

            // return print_r($tableau);
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    //ATTENTION MODIFIER LORDRE DES ELEMENTS ET VERIFIER OU FAIRE DE MEME POUR by RUBRIQUE
    public function getByUser(Utilisateur $utilisateur)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query("SELECT annonce.ID_ANNONCE, annonce.ID_USER, annonce.ID_RUBRIQUE, annonce.EN_TETE, 
            annonce.CORP, annonce.DATE_VALIDITE, annonce.DATE_DEPOT, utilisateur.NOM, rubrique.LIBELLE FROM annonce
            INNER JOIN utilisateur ON annonce.ID_USER = utilisateur.ID_USER
            INNER JOIN rubrique ON annonce.ID_RUBRIQUE = rubrique.ID_RUBRIQUE
            WHERE annonce.ID_USER = {$utilisateur->getId_user()};");

            $stmt->execute();

            return $stmt->fetchall();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function deletePerimees()
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM annonce WHERE DATE_VALIDITE< NOW()');
            $stmt->execute();

            return print $stmt->rowCount()."\n";
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function getByAnnonce(string $id_annonce)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query("SELECT * FROM annonce INNER JOIN utilisateur ON utilisateur.ID_USER  = annonce.ID_USER 
            INNER JOIN rubrique ON rubrique.ID_RUBRIQUE  = annonce.ID_RUBRIQUE 
            WHERE annonce.ID_ANNONCE = {$id_annonce}");

            return $stmt->fetchObject();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function getAnnonce($id_annonce)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query("SELECT annonce.ID_ANNONCE, annonce.ID_USER, annonce.ID_RUBRIQUE, annonce.EN_TETE, 
            annonce.CORP, annonce.DATE_VALIDITE, annonce.DATE_DEPOT, utilisateur.NOM, utilisateur.PRENOM, rubrique.LIBELLE, images.ID_IMAGE, images.ID_ANNONCE, images.HREF FROM annonce
            INNER JOIN utilisateur ON annonce.ID_USER = utilisateur.ID_USER
            INNER JOIN rubrique ON annonce.ID_RUBRIQUE = rubrique.ID_RUBRIQUE
            INNER JOIN images ON images.ID_ANNONCE = annonce.ID_ANNONCE
            WHERE annonce.ID_ANNONCE = {$id_annonce};");
            $stmt->execute();

            return $stmt->fetchObject();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function nbr_annonces()
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query('SELECT COUNT(*) FROM annonce');

            return $stmt->fetchColumn();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function nombresPerimees()
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('SELECT COUNT(*) FROM annonce WHERE DATE_VALIDITE < NOW()');
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function addPhotos($id_annonce, $href)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('INSERT INTO images(ID_ANNONCE,HREF) VALUES (:id_annonce, :href );');
            $stmt->bindValue(':id_annonce', $id_annonce, PDO::PARAM_INT);
            $stmt->bindValue(':href', $href, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function getPhotos($id_annonce)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query("SELECT * FROM images WHERE ID_ANNONCE='{$id_annonce}'");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function getPhotoByRubrique($id_rubrique)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query("SELECT images.ID_ANNONCE, images.HREF, images.ID_IMAGE, rubrique.ID_RUBRIQUE, annonce.ID_ANNONCE, annonce.ID_RUBRIQUE
            FROM images 
            INNER JOIN annonce ON annonce.ID_ANNONCE = images.ID_ANNONCE
            INNER JOIN rubrique ON rubrique.ID_RUBRIQUE = annonce.ID_RUBRIQUE
            WHERE rubrique.ID_RUBRIQUE ='{$id_rubrique}' 
            GROUP BY annonce.ID_ANNONCE");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function getPhotoByUser($id_user)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query("SELECT images.ID_ANNONCE, images.HREF, images.ID_IMAGE, rubrique.ID_RUBRIQUE, annonce.ID_ANNONCE, annonce.ID_RUBRIQUE
            FROM images 
            INNER JOIN annonce ON annonce.ID_ANNONCE = images.ID_ANNONCE
            INNER JOIN rubrique ON rubrique.ID_RUBRIQUE = annonce.ID_RUBRIQUE
            WHERE annonce.ID_USER ='{$id_user}' 
            GROUP BY annonce.ID_ANNONCE");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function updatePhotoAnnonce($old_id_image, $new_href)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('UPDATE images SET HREF = :new_href WHERE ID_IMAGE = :old_id_image;');
            $stmt->bindValue(':new_href', $new_href, PDO::PARAM_STR);
            $stmt->bindValue(':old_id_image', $old_id_image, PDO::PARAM_INT);
            $stmt->execute();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function searchQuery($sql)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->query($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }

    public function getHREFSById($id)
    {
        try {
            $stmt = ConnexionBdd::getConnexion()->prepare('SELECT HREF FROM images WHERE ID_ANNONCE = :id_annonce');
            $stmt->bindValue(':id_annonce', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (BDDException $e) {
            echo $e->getMessage()."\n";
            echo (int) $e->getCode()."\n";

            return -1;
        }
    }
}
