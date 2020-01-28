<?php

    include_once dirname(__DIR__).'/Domain/Rubrique.php';
    require_once 'RubriqueDAO.php';

     class MySQLRubriqueDAO implements RubriqueDAO
     {
         public function insert(Rubrique $rubrique)
         {
             try {
                 $stmt = ConnexionBdd::getConnexion()->prepare('INSERT INTO rubrique(LIBELLE) VALUES (:LIBELLE)');
                 $stmt->bindValue(':LIBELLE', $rubrique->getLibelle(), PDO::PARAM_STR);
                 //si tu veux proteger ton insertion d'un htmlentities script par exemple il faut explode et filtrer <script> et ne pas insert
                 try {
                     $stmt->execute();
                 } catch (PDOException $e) {
                     return print 'Libelle déjà existant, saisissez un autre';
                 }

                 $curId = ConnexionBdd::getConnexion()->lastInsertId();
                 $rubrique->setRubrique_id($curId);
                 $libelle = $rubrique->getLibelle();

                 return   print "Succès ! L'Id_rubrique de ".$libelle.' est le '.$curId."\n";
             } catch (BDDException $e) {
                 echo $e->getMessage()."\n";
                 echo (int) $e->getCode()."\n";

                 return -1;
             }
         }

         public function update(Rubrique $rubrique, $newlibelle)
         {
             try {
                 $stmt = ConnexionBdd::getConnexion()->prepare('UPDATE rubrique SET LIBELLE = :libelle WHERE ID_RUBRIQUE = :id_rubrique LIMIT 1');
                 $stmt->bindValue(':libelle', $newlibelle, PDO::PARAM_STR);
                 $stmt->bindValue(':id_rubrique', $rubrique->getId(), PDO::PARAM_STR);
                 $stmt->execute();

                 return print 'Le libelle : '.$newlibelle." a bien été modifier pour l'id rubrique : ".$rubrique->getId();
             } catch (BDDException $e) {
                 echo $e->getMessage()."\n";
                 echo (int) $e->getCode()."\n";

                 return -1;
             }
         }

         public function getAll()
         {
             try {
                 $stmt = ConnexionBdd::getConnexion()->query('SELECT * FROM rubrique');
                 //  $stmt->execute();
                 //  $stmt->setFetchMode(PDO::FETCH_ASSOC);

                 return $stmt->fetchall();
                 //  foreach ($data as $value) {
                //      echo $value['LIBELLE'];
                //  }

                //  return;
             } catch (BDDException $e) {
                 echo $e->getMessage()."\n";
                 echo (int) $e->getCode()."\n";

                 return -1;
             }
         }

         public function delete(Rubrique $rubrique)
         {
             try {
                 $stmt = ConnexionBdd::getConnexion()->prepare('DELETE FROM rubrique WHERE ID_RUBRIQUE = :id_rubrique  LIMIT 1 ');
                 $stmt->bindValue(':id_rubrique', $rubrique->getId(), PDO::PARAM_INT);
                 $stmt->execute();

                 return print 'La rubrique à bien été suprrimer ';
             } catch (BDDException $e) {
                 echo $e->getMessage()."\n";
                 echo (int) $e->getCode()."\n";

                 return -1;
             }
         }
     }
