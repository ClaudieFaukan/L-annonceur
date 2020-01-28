<?php
require_once './Domain/Utilisateur.php';
require_once 'UtilisateurDAO.php';

    class MySQLUtilisateurDAO implements UtilisateurDAO
    {
        public function insert(Utilisateur $utilisateur, $confirmKey)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query("SELECT * FROM utilisateur WHERE MAIL = '{$utilisateur->getEmail()}';");
                $stmt->execute();
                $result = $stmt->fetchColumn();
                if (false == $result) {
                    $stmt = ConnexionBdd::getConnexion()->prepare('INSERT INTO utilisateur(MDP,NOM,PRENOM,MAIL,ADMINISTRATEUR,CONFIRMATION_KEY) VALUES (:MDP,:NOM,:PRENOM,:MAIL,:ADMINISTRATEUR, :CONFIRMATION_KEY)');
                    $stmt->bindValue(':MDP', $utilisateur->getMdp(), PDO::PARAM_STR);
                    $stmt->bindValue(':NOM', $utilisateur->getNom(), PDO::PARAM_STR);
                    $stmt->bindValue(':MAIL', $utilisateur->getEmail(), PDO::PARAM_STR);
                    $stmt->bindValue(':ADMINISTRATEUR', $utilisateur->getAdmin(), PDO::PARAM_STR);
                    $stmt->bindValue(':PRENOM', $utilisateur->getPrenom(), PDO::PARAM_STR);
                    $stmt->bindValue(':CONFIRMATION_KEY', $confirmKey, PDO::PARAM_STR);

                    $stmt->execute();
                    $curId = ConnexionBdd::getConnexion()->lastInsertId();
                    $utilisateur->setId_user($curId);

                    return $nom = $utilisateur->getNom();
                }

                echo'Email déjà enregistrer';
                afficheracceuil();
                exit();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }

            $cnx = null;
        }

        public function identifier(Utilisateur $utilisateur)
        {
            // TODO REDUIRE ET RECUPERER LES INFOS DE LA SELECTION POUR INTEGRER SES DONNEES SI TRUE DANS UTULISATEUR POUR SESSION USER par ex
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare("SELECT * FROM utilisateur WHERE MDP = '{$utilisateur->getSha256()}' AND MAIL = '{$utilisateur->getEmail()}';");
                $stmt->execute();
                $result = $stmt->fetchObject(__CLASS__);
                if (true == $result) {
                    $utilisateur->setNom($result->NOM);
                    $utilisateur->setPrenom($result->PRENOM);
                    $utilisateur->setAdmin($result->ADMINISTRATEUR);
                    $utilisateur->setId_user($result->ID_USER);
                    $utilisateur->setActif($result->COMPTE_ACTIF);

                    return true;
                }
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function identifierID(Utilisateur $utilisateur)
        {
            // TODO REDUIRE ET RECUPERER LES INFOS DE LA SELECTION POUR INTEGRER SES DONNEES SI TRUE DANS UTULISATEUR POUR SESSION USER par ex
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare("SELECT * FROM utilisateur WHERE ID_USER = '{$utilisateur->getId_user()}';");
                $stmt->execute();
                $result = $stmt->fetchObject(__CLASS__);
                if (true == $result) {
                    $utilisateur->setNom($result->NOM);
                    $utilisateur->setPrenom($result->PRENOM);
                    $utilisateur->setAdmin($result->ADMINISTRATEUR);
                    $utilisateur->setId_user($result->ID_USER);
                    $utilisateur->setActif($result->COMPTE_ACTIF);
                    $utilisateur->setMdp($result->MDP);
                    $utilisateur->setEmail($result->MAIL);

                    return true;
                }
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function identifierMail(Utilisateur $utilisateur)
        {
            // TODO REDUIRE ET RECUPERER LES INFOS DE LA SELECTION POUR INTEGRER SES DONNEES SI TRUE DANS UTULISATEUR POUR SESSION USER par ex
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare("SELECT * FROM utilisateur WHERE MAIL = '{$utilisateur->getEmail()}';");
                $stmt->execute();
                $result = $stmt->fetchObject(__CLASS__);
                if (true == $result) {
                    $utilisateur->setNom($result->NOM);
                    $utilisateur->setPrenom($result->PRENOM);
                    $utilisateur->setAdmin($result->ADMINISTRATEUR);
                    $utilisateur->setId_user($result->ID_USER);
                    $utilisateur->setActif($result->COMPTE_ACTIF);
                    $utilisateur->setMdp($result->MDP);
                    $utilisateur->setEmail($result->MAIL);

                    return true;
                }
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function delete(Utilisateur $utilisateur)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM annonce WHERE ID_USER = :id_user');
                $stmt->bindValue(':id_user', $utilisateur->getId_user(), PDO::PARAM_INT);

                try {
                    $stmt->execute();
                } catch (BDDException $e) {
                    echo $e->getMessage()."\n";
                    echo (int) $e->getCode()."\n";

                    return -1;

                    return;
                }

                $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM utilisateur WHERE ID_USER = :id_user ');
                $stmt->bindValue(':id_user', $utilisateur->getId_user(), PDO::PARAM_INT);
                $stmt->execute();

                return print ' Utilisateur supprimer avec succès';
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function getAll()
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query('SELECT * FROM utilisateur;');

                return $stmt->fetchAll();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function nombresUtilisateur()
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->query('SELECT COUNT(*) FROM utilisateur;');

                return $stmt->fetchColumn();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function reinitialiseMDP($id_user, $key)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare("UPDATE utilisateur SET MDP = '{$key}', KEY_MDP = '{$key}' WHERE ID_USER={$id_user}");
                $stmt->execute();

                $test = $stmt->rowCount();
                if (0 == $test) {
                    return true;
                }

                return false;
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function cancelreinitialiseMDP($id_user)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare("UPDATE utilisateur AS u1 ,(SELECT SAVE_MDP FROM utilisateur WHERE ID_USER ='{$id_user}') AS u2 
                SET u1.MDP = u2.SAVE_MDP WHERE u1.ID_USER='{$id_user}'");
                $stmt->execute();

                $test = $stmt->rowCount();
                if (1 == $test) {
                    return true;
                }

                return false;
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function updateMDP(Utilisateur $utilisateur)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare("UPDATE utilisateur SET MDP = '{$utilisateur->getSha256()}' WHERE ID_USER={$utilisateur->getId_user()}");
                $stmt->execute();

                return $stmt->fetchColumn();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function findMDP(Utilisateur $utilisateur)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare("SELECT MDP,ID_USER FROM utilisateur WHERE MAIL='{$utilisateur->getEmail()}'");
                $stmt->execute();
                $mdp = $stmt->fetchObject();
                $utilisateur->setMdp($mdp->MDP);
                $utilisateur->setId_user($mdp->ID_USER);

                return;
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function identificationConfirmation($user_mail, $key)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('SELECT * FROM utilisateur WHERE MAIL = :USER_MAIL AND CONFIRMATION_KEY = :CONFIRM_KEY');
                $stmt->bindValue(':USER_MAIL', $user_mail, PDO::PARAM_STR);
                $stmt->bindValue(':CONFIRM_KEY', $key, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetchObject();
                if (false == $user) {
                    echo ' Une erreur est survenue lors de votre validation(error:key)';

                    return exit();
                }
                if (0 == $user->COMPTE_ACTIF) {
                    return $user;
                }

                echo ' Compte déjà validé';
                exit();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }
        }

        public function reinitialisationIdentification($key, $user_mail)
        {
            try {
                $stmt = ConnexionBdd::getConnexion()->prepare('SELECT * FROM utilisateur WHERE KEY_MDP = :KEY_MDP AND USER_MAIL = :MAIL');
                $stmt->bindValue(':MAIL', $user_mail, PDO::PARAM_STR);
                $stmt->bindValue(':KEY_MDP', $key, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetchObject();
                if (false == $user) {
                    echo ' Une erreur est survenue lors de votre validation(error:key)';

                    return exit();
                }
                if (0 === $user->KEY_MDP) {
                    echo ' Mot de passe déjà changer ! ';
                    exit();
                }

                return;
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
    }

    $cnx = null;
?>
    
   

   

