<?php
/*.**************************************************************************************************************************************************************************
 ***************************************************************** LE CONTROLEUR MAIN ****************************************************************************************.
 *****************************************************************************************************************************************************************************.*/
session_start();
if (empty($_GET)) {
    afficheracceuil();
}

if (isset($_GET['action'])) {
    $action = htmlspecialchars($_GET['action']);
    /*.***********************************                               *******************************************************
        **********************************          USER CONTROLER        **********************************************************.
        *********************************                                 ******************************************************.*/

    //***********************************        VIEW CONTROLER            **************************************** */

    if ('inscription' == $action) {                 // Vue Connexion / Inscription
        incscriptionConnexion();
    }
    if ('monCompte' == $action) {                   // Vue compte user ou administrateur
        if ('non' == $_SESSION['Admin']) {
            monCompte();
        } else {
            compteAdministrateur();
        }
    }
    if ('createAnnonce' == $action) {               //  Vue création annonce
        createAnnonce();
    }
    if ('selectRubrique' == $action) {              // Vue des annonces d'une rubrique
        annoncelist();
        exit();
    }
    if ('home' == $action) {                        // Vue acceuil pour fil Arianne
        afficheracceuil();
    }
    if ('updateAnnonce' == $action) {               // Vue update annonce
        updateAnnonce();
    }
    if ('vueAnnonce' == $action) {                  // Vue d'une annonce précise
        vueAnnonce();
    }
    if ('confirmation' == $action) {                // Vue confirmation mail lors de l'inscription (lien de cette page dans mail)
        vueConfirmation();
    }
    if ('vueforgetpassword' == $action) {           //  Vue lors du clic user sur mdp oublier
        vueforgetpassword();
    }
    if ('reinitialisationMDP' == $action) {         //  Vue de la reinisitialisation MDP
        reinitialiserMDP();
    }
    //**********************************        CONEXION /INSCRIPTION /DECONNEXION  CONTROLER      *************************************** */

    if ('cnx' == $action) {                         //  Connexion User/Adminisatrateur
        connexion();
    }
    if ('form_insc' == $action) {                   //  Inscription User en BDD
        inscription();
    }
    if ('logout' == $action) {                      //  Déconnexion de la session
        if (!empty($_SESSION)) {
            logout();
        } else {
            afficheracceuil();
        }
    }
    //*************************************     CREATION /UPDATE /DELETE ANNONCE   CONTROLER   ************************************************/

    if ('postAnnonce' == $action) {                 // Création annonce en BDD
        depotAnnonce();
        afficheracceuil();
    }

    if ('deleteAnnonce' == $action) {               //  Supprime une annonce précise
        deleteAnnonce();
    }

    if ('updateAnnonceBDD' == $action) {            // Met à jour l'annonce précise dans la BDD
        updateAnnonceBDD();
    }
    //*********************************************         REINITIALISER /ANNULER MOT DE PASSE   CONTROLER      ***************************************/
    if ('forgetpassword' == $action) {              //  Action envoi du mail(user.mail) et réinitialsation du MDP en BDD
        forgetpassword();
    }
    if ('cancelreinitpassword' == $action) {        //  Action via lien mail user pour annuler la reinitialisaiton du mdp et reprendre son ancien mdp
        cancelreinitpass();
    }

    /*.*************************************************************                              ******************************************************
        ***********************************************************       ADMINISTARTEUR         *********************************************************.
        ***********************************************************                              ****************************************************.*/

    if ('deletePerimes' == $action) {               //  Action supprimer les annonces périmées de la date
        deletePerimes();
        compteAdministrateur();
    }
    if ('ajoutRubrique' == $action) {               //  Action d'ajout une rubrique
        ajoutRubrique();
        compteAdministrateur();
    }
    if ('updateRubrique' == $action) {              //  Action de mis à jour du libelle d'une rubrique ciblé
        updateRubrique();
        compteAdministrateur();
    }
    if ('deleteRubrique' == $action) {              //  Action de suppression d'une rubrique ciblé
        deleteRubrique();
    }
    if ('deleteUser' == $action) {                  //  Action de suppression d'un utilisateur ciblé
        deleteUser();
    }
    if ('filtreRubrique' == $action) {              //  Action pour selectionner les annonces d'une rubrique en menu déroulant
        compteAdministrateur();
    }
    if ('deleteMDP' == $action) {                   //  Action pour réinitialiser le MDP d'un utilisateur ciblé + envoi mail à l'utilisateur pour saisir son nouvau mdp
        reinitialiseMDP();
    }
    if ('newmdp' == $action) {                      //  Action de réinitialistion du MDP en BDD
        updateMDP();
    }


    if ('listerAnnonceAjax1' == $action){
        listerAnnonceAjax1();
    }
 

}

/*.**************************************************************************************************************************************************************************
 *****************************************************************       LES VUES        ****************************************************************************************.
 *****************************************************************************************************************************************************************************.*/

  /*.**********************************************************************************************************************************
 *****************************************************************       USER(vues)       ***************************************************.
 **************************************************************************************************************************************.*/

 //******************************************************             ACCUEIL           *********************************************************
    function afficheracceuil()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DATA/compteur.php';
        $r = new MySQLRubriqueDAO();
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);

        $url = htmlspecialchars($_SERVER['PHP_SELF']);
        if (!empty($_SESSION)) {                        //  Si session active personnalise l'environnement user
            $prenom = $_SESSION['Prenom'];
            $admin = $_SESSION['Admin'];
            $mail = $_SESSION['Email'];
            $nom = $_SESSION['Nom'];
            echo $twig->render(
                'home.twig',
                [
                    'url' => $url,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'admin' => $admin,
                    'mail' => $mail,
                    'rubs' => $r->getAll(), // Récuperer->donne à la vue twig toutes les rubriques pour menu déroulant
                ]
            );
        } else {
            echo $twig->render(
                'home.twig',
                ['url' => $url,
                    'rubs' => $r->getall(), // Récuperer->donne à la vue twig toutes les rubriques pour menu déroulant
                ]
            );
        }
        ajouter_vue();
    }
    //******************************************************             CONNEXION / INSCRIPTION             *********************************************************
    function incscriptionConnexion()
    {
        require_once 'vendor/autoload.php';
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        $url = htmlspecialchars($_SERVER['PHP_SELF']);
        echo $twig->render(
            'inscription.twig',
            ['url' => $url]
        );
    }
    //******************************************************             MDP OUBLIEE                 *********************************************************
    function vueforgetpassword()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        $r = new MySQLRubriqueDAO();
        $url = htmlspecialchars($_SERVER['PHP_SELF']);
        echo $twig->render(
            'forgetpassword.twig',
            ['url' => $url,
                'rubs' => $r->getAll(), // Récuperer->donne à la vue twig toutes les rubriques pour menu déroulant
            ]
        );
    }
    //******************************************************             MOT DE PASSE REINITIALISER (via lien mail)              *********************************************************
    function forgetpassword()                                       // Envoi du mail et réinitialisation du MDP -> renvoi à l'acceuil
    {
        require_once 'DAO/MySQLUtilisateurDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'Domain/Utilisateur.php';
        $userDAO = new MySQLUtilisateurDAO();
        $user = new Utilisateur('', '', '', $_POST['mail'], '', '');        //  Initialisation d'un utlisateur via son mail
        $userDAO->identifierMail($user);                                    //  Récupération et modification de $user grâce aux infos inscrit en BDD (nom,prenom etc..)
        //Création de la clé unique pour modification MDP
        $lengthKey = 15;
        $key = '';
        for ($i = 1; $i < $lengthKey; ++$i) {
            $key .= mt_rand(0, 9);
            $keyok = $key;
        }//Fin de création

        $userDAO->reinitialiseMDP($user->getId_user(), $keyok);                 //  Modification du mot de passe avec la $key et insert $key en KEY_MDP pour vérifiaction + tard
        mailMDP($user, $key);                                                   //  Envoi du mail à l'utilisateur, et la $key pour vérification si match entre key envoyer et key isncrit en BDD
        echo 'Le mot de passe est réinitialiser, veuillez vous connecter à votre boite mail pour continuer ';
        afficheracceuil();
    }
    //******************************************************             PAGE MON COMPTE USER               *********************************************************
    function monCompte()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'Domain/Utilisateur.php';
        // Création d'un utilisateur via la session pour ensuite récupérer ses données d'annonces par exemple
        $u = new Utilisateur(htmlspecialchars($_SESSION['Nom']), htmlspecialchars($_SESSION['Prenom']), '', htmlspecialchars($_SESSION['Email']), htmlspecialchars($_SESSION['Email']), htmlspecialchars($_SESSION['ID']));
        $a = new MySQLAnnonceDAO();
        $r = new MySQLRubriqueDAO();
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
        //! REFACTORING !
        $url = htmlspecialchars($_SERVER['PHP_SELF']);
        $prenom = htmlspecialchars($_SESSION['Prenom']);
        $admin = htmlspecialchars($_SESSION['Admin']);
        $mail = htmlspecialchars($_SESSION['Email']);
        $nom = htmlspecialchars($_SESSION['Nom']);

        echo $twig->render('monCompte.twig', [
            'url' => $url,
            'nom' => $nom,
            'prenom' => $prenom,
            'admin' => $admin,
            'mail' => $mail,
            'rubs' => $r->getAll(),                             //  Recupère toutes les rubriques
            'annonces' => $a->getByUser($u),                    //  Recupère toutes les annonces de l'utilisateur
            'images' => $a->getPhotoByUser($u->getId_user()),   //  Récupère toutes les 1eres images d'une annonce et pour cette utlisateur
        ]);
    }
//******************************************************             CREATION D'UNE ANNONCE             *********************************************************
    function createAnnonce()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        $r = new MySQLRubriqueDAO();
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        $url = htmlspecialchars($_SERVER['PHP_SELF']);

        if (!empty($_SESSION)) {                                // Si session active
            //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
            //! REFACTORING !
            $prenom = htmlspecialchars($_SESSION['Prenom']);
            $admin = htmlspecialchars($_SESSION['Admin']);
            $mail = htmlspecialchars($_SESSION['Email']);
            $nom = htmlspecialchars($_SESSION['Nom']);

            echo $twig->render('createAnnonce.twig', [
                'url' => $url,
                'nom' => $nom,
                'prenom' => $prenom,
                'admin' => $admin,
                'mail' => $mail,
                'rubs' => $r->getAll(),                         //  Récupère toutes les rubriques
            ]);
        } else {
            echo $twig->render('createAnnonce.twig', [
                'url' => $url,
                'rubs' => $r->getAll(),                         //  Récupère toutes les rubriques
            ]);
        }
    }
    //******************************************************             ANNONCES D'UNE RUBRIQUE                *********************************************************
        function annoncelist()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'Domain/Rubrique.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        $rubs = new MySQLRubriqueDAO();
        $rub = new Rubrique('', $_POST['ID_RUBRIQUE']);
        $annonces = new MySQLAnnonceDAO();

        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);

        $url = $_SERVER['PHP_SELF'];
        if (!empty($_SESSION)) {
            $prenom = $_SESSION['Prenom'];
            $admin = $_SESSION['Admin'];
            $mail = $_SESSION['Email'];
            $nom = $_SESSION['Nom'];

            echo $twig->render(
                'annonceList.twig',
                [
                    'annonces' => $annonces->getByRubrique($rub),
                    'url' => $url,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'admin' => $admin,
                    'mail' => $mail,
                    'rubs' => $rubs->getAll(),
                    'rubriqueafficher' => $rub,
                    'images' => $annonces->getPhotoByRubrique($rub->getId()),
                ]
            );
        } else {
            echo $twig->render(
                'annonceList.twig',
                [
                    'annonces' => $annonces->getByRubrique($rub),
                    'url' => $url,
                    'rubs' => $rubs->getAll(),
                    'images' => $annonces->getPhotoByRubrique($rub->getId()),
                ]
            );
        }
    }

   /* function annoncelist()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'Domain/Rubrique.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        if (empty($_POST['ID_RUBRIQUE'])) {
            $annonces = new MySQLAnnonceDAO();

            if (isset($_POST['query'])) {
                $query = $_POST['query'];
                $s = explode(' ', $query);
                $i = 0;
                $requeteSQL = 'SELECT * FROM annonce INNER JOIN images ON annonce.ID_ANNONCE = images.ID_ANNONCE ';
                foreach ($s as $value) {
                    if (strlen($s > 3)) {
                        if (0 == $i) {
                            $requeteSQL .= 'WHERE ';
                        } else {
                            $requeteSQL .= ' OR ';
                        }
                        $requeteSQL .= "EN_TETE,CORP LIKE '%{$value}%'";
                        $requeteSQL .= ' GROUP BY annonce.ID_ANNONCE ';
                        ++$i;
                    }
                }

                $recherche = $annonces->searchQuery($requeteSQL);

                $nombresResultat = $recherche->rowcount();

                if ($nombresResultat <= 3) {
                    $s = explode(' ', $query);
                    $i = 0;
                    $requeteSQL = 'SELECT * FROM annonce INNER JOIN images ON annonce.ID_ANNONCE = images.ID_ANNONCE ';
                    foreach ($s as $value) {
                        if (strlen($s > 3)) {
                            if (0 == $i) {
                                $requeteSQL .= 'WHERE ';
                            } else {
                                $requeteSQL .= ' OR ';
                            }
                            $requeteSQL .= "CORP LIKE '%{$value}%'";
                            $requeteSQL .= ' GROUP BY annonce.ID_ANNONCE ';
                            ++$i;
                        }
                    }
                    $recherche_advance = $annonces->searchQuery($requeteSQL);
                }
            }
            $rubs = new MySQLRubriqueDAO();

            $loader = new Twig\Loader\FilesystemLoader('templates');
            $twig = new Twig\Environment($loader, [
                'cache' => false, //.__DIR__.'/tmp',
            ]);

            //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
            //! REFACTORING !
            $url = htmlspecialchars($_SERVER['PHP_SELF']);
            if (!empty($_SESSION)) {                                                //  Si session active
                $prenom = htmlspecialchars($_SESSION['Prenom']);
                $admin = htmlspecialchars($_SESSION['Admin']);
                $mail = htmlspecialchars($_SESSION['Email']);
                $nom = htmlspecialchars($_SESSION['Nom']);

                echo $twig->render(
                    'annonceList.twig',
                    [
                        'annonces' => $recherche,              //  Retourne toutes les annonces d'une rubrique en objet
                        'url' => $url,
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'admin' => $admin,
                        'mail' => $mail,
                        'rubs' => $rubs->getAll(),                                  //  Retourne toutes les rubriques pour le menu déroulant du header

                        'images' => $recherche,   //  Retourne les 1ere images des annonces de la rubriques
                    ]
                );
            } else {
                echo $twig->render(                                                 //  Si session non active
                     'annonceList.twig',
                    [
                        'annonces' => $recherche,
                        'url' => $url,
                        'rubs' => $rubs->getAll(),                                  //  Retourne toutes les rubriques pour le menu déroulant du header

                        'images' => $recherche,   //  Retourne les 1ere images des annonces de la rubriques
                    ]
                );
            }
        } else {
            $rubs = new MySQLRubriqueDAO();
            $rub = new Rubrique('', htmlspecialchars($_POST['ID_RUBRIQUE']));           //  Création d'une rubrique via l'ID de la rubrique selctionner dans le menu déroulant
            $annonces = new MySQLAnnonceDAO();

            $loader = new Twig\Loader\FilesystemLoader('templates');
            $twig = new Twig\Environment($loader, [
                'cache' => false, //.__DIR__.'/tmp',
            ]);

            //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
            //! REFACTORING !
            $url = htmlspecialchars($_SERVER['PHP_SELF']);
            if (!empty($_SESSION)) {                                                //  Si session active
                $prenom = htmlspecialchars($_SESSION['Prenom']);
                $admin = htmlspecialchars($_SESSION['Admin']);
                $mail = htmlspecialchars($_SESSION['Email']);
                $nom = htmlspecialchars($_SESSION['Nom']);

                echo $twig->render(
                    'annonceList.twig',
                    [
                        'annonces' => $recherche,              //  Retourne toutes les annonces d'une rubrique en objet
                        'url' => $url,
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'admin' => $admin,
                        'mail' => $mail,
                        'rubs' => $rubs->getAll(),                                  //  Retourne toutes les rubriques pour le menu déroulant du header
                        'rubriqueafficher' => $rub->getId(),                        //  Sert pour préselection dans le menu des rubriques, la rubrique active
                        'images' => $annonces->getPhotoByRubrique($rub->getId()),   //  Retourne les 1ere images des annonces de la rubriques
                    ]
                );
            } else {
                echo $twig->render(                                                 //  Si session non active
                    'annonceList.twig',
                    [
                        'annonces' => $annonces->getByRubrique($rub),               //  Retourne toutes les annonces d'une rubrique en objet
                        'url' => $url,
                        'rubs' => $rubs->getAll(),                                  //  Retourne toutes les rubriques pour le menu déroulant du header
                        'rubriqueafficher' => $rub->getId(),                        //  Sert pour préselection dans le menu des rubriques, la rubrique active
                        'images' => $annonces->getPhotoByRubrique($rub->getId()),   //  Retourne les 1ere images des annonces de la rubriques
                    ]
                );
            }
        }
    }*/



        //******************************************************             UPDATE ANNONCE             *********************************************************
    function updateAnnonce()                                                            //  Met à jour une annonce ciblé
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        $a = new MySQLAnnonceDAO();
        $r = new MySQLRubriqueDAO();
        //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
        //! REFACTORING !
        $url = htmlspecialchars($_SERVER['PHP_SELF']);
        $prenom = htmlspecialchars($_SESSION['Prenom']);
        $admin = htmlspecialchars($_SESSION['Admin']);
        $mail = htmlspecialchars($_SESSION['Email']);
        $nom = htmlspecialchars($_SESSION['Nom']);
        $id_annonce = htmlspecialchars($_POST['ID_ANNONCE']);                               //  Selectionne l'annonce ciblé par ID

        echo $twig->render(
            'updateAnnonce.twig',
            [
                'url' => $url,
                'nom' => $nom,
                'prenom' => $prenom,
                'admin' => $admin,
                'mail' => $mail,
                'rubs' => $r->getAll(),                                                       //    Recupère toute les rubriques pour le menu déroulant du header
                'annonce' => $a->getByAnnonce($id_annonce),                                   //    Récupère toutes les donnés d'une annonce ciblé
                'images' => $a->getPhotos($id_annonce),                                       //    Récupère toutes les photos liées à l'annonce
            ]
        );
    }
    

    //******************************************************             VUE ANNONCE                *********************************************************
    function vueAnnonce()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        require_once 'DATA/compteur.php';
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        $a = new MySQLAnnonceDAO();
        $r = new MySQLRubriqueDAO();
        $ra = $r->getAll();
        $url = htmlspecialchars($_SERVER['PHP_SELF']);
        //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
        //! REFACTORING !
        if (!empty($_SESSION)) {                                                                        //  Si session active alors:
            $prenom = htmlspecialchars($_SESSION['Prenom']);
            $admin = htmlspecialchars($_SESSION['Admin']);
            $mail = htmlspecialchars($_SESSION['Email']);
            $nom = htmlspecialchars($_SESSION['Nom']);
        }

        $fichier = './DATA/compteur_annonce_ID'.'_'.htmlspecialchars($_POST['ID_ANNONCE']);              // Création d'un fichier text pour compter les vues de l'annonce et distingué par l'ID
        if (file_exists($fichier)) {                                                                     // Si le fichier à déjà été créer :
            $vue = nombre_vues_annonce();                                                                // Retourne le nombre de vue inscrit dans le fichier
        } else {                                                                                         // Sinon:
            $vue = 0;                                                                                    // Nombres de vue = 0
        }
        //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
        //! REFACTORING !
        if (!empty($_SESSION)) {                                                                          //    Si session active :
            echo $twig->render(
                'vueAnnonce.twig',
                ['url' => $url,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'admin' => $admin,
                    'mail' => $mail,
                    'rubs' => $ra,                                                                          //  Récupère toutes les rubriques pour le menu dérourlant dans le header
                    'annonce' => $a->getAnnonce(htmlspecialchars($_POST['ID_ANNONCE'])),                    //  Récupère toutes les données contenue dans une annonce ciblé
                    'nbr_vues' => $vue,                                                                     //  Retourne le nombres de vue dans le fichier data ID-annonce
                    'images' => $a->getPhotos(htmlspecialchars($_POST['ID_ANNONCE'])),                      //  Retourne toutes les photos liées à l'annonce
                ]
            );
        } else {                                                                                             // Si session non active :
            echo $twig->render(
                'vueAnnonce.twig',
                ['url' => $url,
                    'rubs' => $r->getAll(),                                                                 //  Récupère toutes les rubriques pour le menu dérourlant dans le header
                    'annonce' => $a->getAnnonce(htmlspecialchars($_POST['ID_ANNONCE'])),                    //  Récupère toutes les données contenue dans une annonce ciblé
                    'nbr_vues' => $vue,                                                                     //  Retourne le nombres de vue dans le fichier data ID-annonce
                    'images' => $a->getPhotos(htmlspecialchars($_POST['ID_ANNONCE'])),                      //  Retourne toutes les photos liées à l'annonce
                ]
            );
        }

        ajouter_vue_annonce();                                                                               // Incrémentation du compteur de vue de l'annonce choisie
    }
//******************************************************             CONFIRMATION MAIL              *********************************************************
    function vueConfirmation()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DAO/MySQLUtilisateurDAO.php';
        require_once 'DAO/BDDException.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        $url = $_SERVER['PHP_SELF'];
        $rubriques = new MySQLRubriqueDAO();
        $requser = new MySQLUtilisateurDAO();

        //  Test et vérification des get attendus présent
        if (isset($_GET['mail'], $_GET['confirmKey']) and !empty($_GET['mail']) and !empty($_GET['confirmKey'])) {
            $mail = htmlspecialchars(urldecode($_GET['mail']));                                                         //  Récupération du mail user
            $key = $_GET['confirmKey'];                                                                                 //  Récupération de la clé de confirmation

            try {
                $user = $requser->identificationConfirmation($mail, $key);                                              //  Vérification dans la BDD de la clé et mail, si match en la requete envoyer et celle présente en BDD
                $activeCompte = $stmt = ConnexionBdd::getConnexion()->prepare("UPDATE utilisateur SET COMPTE_ACTIF = 1 WHERE MAIL= '{$mail}' AND CONFIRMATION_KEY = '{$key}'");  // Si match alors on update le compte en actif
                $activeCompte->execute();
            } catch (BDDException $e) {
                echo $e->getMessage()."\n";
                echo (int) $e->getCode()."\n";

                return -1;
            }

            echo $twig->render(
                'confirmation.twig',
                ['url' => $url,
                    'rubs' => $rubriques->getAll(),                                                                      // Retourne toutes les rubriques menu déroulant pour le header
                ]
            );
        }
    }
    //******************************************************             REINITIALISATION MDP            *********************************************************
        function reinitialiserMDP()
        {
            require_once 'vendor/autoload.php';
            require_once 'Domain/Utilisateur.php';
            require_once 'DAO/MySQLUtilisateurDAO.php';
            require_once 'DAO/ConnexionBDD.php';
            $user = new Utilisateur('', '', '', '', '', urldecode($_GET['ID_USER']));
            $userDAO = new MySQLUtilisateurDAO();
            $loader = new Twig\Loader\FilesystemLoader('templates');
            $twig = new Twig\Environment($loader, [
                'cache' => false, //.__DIR__.'/tmp',
            ]);
            $userOK = $userDAO->reinitialiseMDP($_GET['ID_USER'], $_GET['KEY_MDP']);
            if (true == $userOK) {
                echo $twig->render('reinitialiseMDP.twig', ['user' => $user]);
                exit();
            }

            echo 'Une erreur est survenue ?!';
        }

     //**********************************************************        BARRE DE RECHERCHE ANNONCE     ******************************************************************/
     function searchByQuery()
     {                                      // Recherche par query uniquement dans toutes les rubriques
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLAnnonceDAO.php';
         require_once 'DAO/MySQLRubriqueDAO.php';
         require_once 'vendor/autoload.php';
         $loader = new Twig\Loader\FilesystemLoader('templates');
         $twig = new Twig\Environment($loader, [
             'cache' => false, //.__DIR__.'/tmp',
         ]);
         $a = new MySQLAnnonceDAO();
         $query = $_POST['query'];
         $requete = $a->searchQuery($query);

         return $requete;
     }
    function searchByRubAndQuery()
    {
        // recherche par query dans une rubrique précise
    }
    function searchNoQueryNoRub()
    {
        // recherche sans query ni rubrique afficher les dernières annonce poster par ordre décroissant
    }

    /*.****************************************************************************************************************************************************
     *****************************************************************       ADMINISATREUR (vue)       ***************************************************.
    *****************************************************************************************************************************************************.*/

    function compteAdministrateur()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLRubriqueDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'Domain/Utilisateur.php';
        require_once 'DAO/MySQLUtilisateurDAO.php';
        require_once 'Domain/Rubrique.php';
        require_once 'DATA/compteur.php';

        if ('filtreRubrique' == htmlspecialchars($_GET['action'])) {                // Pour filtrer les annonces d'une rubrique à l'intérieur d'un menu déroulant spécial admin
            $rub = new Rubrique('', htmlspecialchars($_POST['ID_RUBRIQUE']));
            $a = new MySQLAnnonceDAO();
        }
        //Récupération des DATA du site
        $vue_acceuil = nombre_vues_acceuil();
        $d = new DateTime();
        $date = date_format($d, 'Y-m-d');
        $fichier = './DATA/compteur_acceuil_'.$date;

        // Création d'un utlisateur pour récupérer les annonces liés
        $u = new Utilisateur(htmlspecialchars($_SESSION['Nom']), htmlspecialchars($_SESSION['Prenom']), '', htmlspecialchars($_SESSION['Email']), htmlspecialchars($_SESSION['Admin']), htmlspecialchars($_SESSION['ID']));
        $a = new MySQLAnnonceDAO();
        $r = new MySQLRubriqueDAO();
        $users = new MySQLUtilisateurDAO();

        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);

        //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
        //! REFACTORING !
        $url = htmlspecialchars($_SERVER['PHP_SELF']);
        $prenom = htmlspecialchars($_SESSION['Prenom']);
        $admin = htmlspecialchars($_SESSION['Admin']);
        $mail = htmlspecialchars($_SESSION['Email']);
        $nom = htmlspecialchars($_SESSION['Nom']);
        if (isset($rub)) {                                                              //  Si filtre rubrique est actif
            echo $twig->render(
                'compteAdministrateur.twig',
                ['url' => $url,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'admin' => $admin,
                    'mail' => $mail,
                    'rubs' => $r->getAll(),                                             //  Récupère toutes les rubriques
                    'annonces' => $a->getByUser($u),                                    //  Récupère toutes les annonces de l'utlisateur
                    'users' => $users->getAll(),                                        //  Récupère tout les membres inscrit
                    'annonces_Rub' => $a->getByRubrique($rub),                          //  Récupère et filtre les annonces par rubriques
                    'vue_acceuil' => $vue_acceuil,                                      //  DATA analyse du nombres de vues sur la page acceuil
                    'nbr_annonces' => $a->nbr_annonces(),                               //  DATA analyse sur le nombres total d'annonces en ligne
                    'nbr_utilisateur' => $users->nombresUtilisateur(),                  //  DATA analyse sur le nombres total de membres
                    'nbr_visite_today' => nbr_vue_today(),                              //  DATA analyse sur le nombres de vue aujourd'hui
                    'nbr_perimees' => $a->nombresPerimees(),                            //  DATA analyse sur le nombres d'annonces périmés à ce jour en ligne
                    'images' => $a->getPhotoByUser($u->getId_user()),                   //  Récupère les premères images de l'annnonce de l'Adminisatrateur
                ]
            );
        } else {
            $annonces_Rub = 'Aucune Annonce Dispo';                                     //  Sinon
            echo $twig->render(
                'compteAdministrateur.twig',
                ['url' => $url,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'admin' => $admin,
                    'mail' => $mail,
                    'rubs' => $r->getAll(),                                              //  Récupère toutes les rubriques
                    'annonces' => $a->getByUser($u),                                     //  Récupère toutes les annonces de l'utlisateur
                    'users' => $users->getAll(),                                         //  Récupère tout les membres inscrit
                    'annonces_Rub' => $annonces_Rub,                                     //  Récupère et filtre les annonces par rubriques
                    'vue_acceuil' => $vue_acceuil,                                       //  DATA analyse du nombres de vues sur la page acceuil
                    'nbr_annonces' => $a->nbr_annonces(),                                //  DATA analyse sur le nombres total d'annonces en ligne
                    'nbr_utilisateur' => $users->nombresUtilisateur(),                   //  DATA analyse sur le nombres total de membres
                    'nbr_visite_today' => nbr_vue_today(),                               //  DATA analyse sur le nombres de vue aujourd'hui
                    'nbr_perimees' => $a->nombresPerimees(),                             //  DATA analyse sur le nombres d'annonces périmés à ce jour en ligne
                    'images' => $a->getPhotoByUser($u->getId_user()),                    //  Récupère les premères images de l'annnonce de l'Adminisatrateur
                ]
            );
        }
    }

    /*.*****************************************************                            ********************************************************************************************
     *******************************************************         LES FONCTIONS      *********************************************************************************************.
     *******************************************************                            ******************************************************************************************.*/

    //******************************************************             CONNEXION          *********************************************************

    function Connexion()
    {
        require_once './DAO/ConnexionBDD.php';
        require_once './DAO/MySQLUtilisateurDAO.php';
        require_once './Domain/Utilisateur.php';
        $user = new Utilisateur('', '', ($_POST['pass_cnx']), htmlspecialchars($_POST['email_cnx']), '');                       //  Initialisation d'un user
        $userDAO = new MySQLUtilisateurDAO();

        try {
            $userDAO->identifier($user);                                                                                        //  Vérification dans la BBD si l'adresse mail et le MDP corresponde à un user,si oui user recupère toutes les infos sur celui ci (nom, prenom..)

            if ($user->getId_user() >= 1) {                                                                                     //  Si l'id est supérieur à 1 cela signifie que le user est dans la bdd
                if (1 == $user->getActif()) {                                                                                   //  Si Actif = 1 alors le compte est vérifié
                    $_SESSION['Nom'] = htmlspecialchars($user->getNom());                                                       //  Insert des infos user dans la session:
                    $_SESSION['Prenom'] = htmlspecialchars($user->getPrenom());
                    $_SESSION['Admin'] = htmlspecialchars($user->getAdmin());
                    $_SESSION['Email'] = htmlspecialchars($user->getEmail());
                    $_SESSION['ID'] = htmlspecialchars($user->getId_user());
                    $_SESSION['ACTIF'] = htmlspecialchars($user->getActif());
                    afficheracceuil();
                    exit();
                }

                echo ' Veuillez confirmer votre adresse mail, sur votre messagerie.';                                           //  Si compte non validé:
                afficheracceuil();
                exit();

                echo 'veuillez verifier vos identifiant de connexion';                                                          //  Si compte validé mais erreur de saisie
            }

            echo 'Mot de passe ou identifiant incorrect';
            afficheracceuil();
        } catch (Exception $e) {
            echo 'Une errreur est survenue';
        }
    }

    //******************************************************             DECONNEXION            *********************************************************
    function logout()
    {
        session_destroy();

        require_once 'vendor/autoload.php';
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        echo $twig->render('home.twig');
    }

    //*********************************************************         INSCRIPTION             ***********************************************************
    /**
     * TODO :: Lors du retour acceuil rendre l'action inscription reussi plus ergonomique et plus temporaire (quelques secondes).
     * TODO:: Idem pour les email déjà enregistrer.
     */
    function inscription()
    {
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DAO/MySQLUtilisateurDAO.php';
        require_once 'Domain/Utilisateur.php';
        $user = new Utilisateur(htmlspecialchars($_POST['nom']), htmlspecialchars($_POST['prenom']), htmlspecialchars($_POST['mdp']), htmlspecialchars($_POST['email']), htmlspecialchars('non'));
        $userDAO = new MySQLUtilisateurDAO();

        try {                                                                                               // GENERATION DE LA CLE POUR CONFIRMATION COMPTE PAR MAIL
            $lengthKey = 15;
            $key = '';
            for ($i = 1; $i < $lengthKey; ++$i) {
                $key .= mt_rand(0, 9);
            }
            $userDAO->insert($user, $key);                                                                  //  INSERTION DU USER DANS LA BDD
            confirmMail($user, $key);                                                                       //  Envoi du mail de confirmation
            echo ' Un mail de confirmation viens de vous être envoyer, pensez à vérifier vos spams.';
        } catch (BDDException $e) {
            echo 'erreur !';
        }
        afficheracceuil();
    }

    // .**************************************************          DEPOT ANNONCE            *************************************************.

    function depotAnnonce()
    {
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'Domain/Annonce.php';
        require_once 'Domain/Utilisateur.php';
        require_once 'Domain/Rubrique.php';
        //! Inutile de se répéter car pour passer à la vue ses information il aurait fallu juste 'utilisateur' => $u (Utilisateur) et ensuite dans la vue exemple utlisateur.prenom ou meme session.prenom
        //! REFACTORING !
        if (!empty($_SESSION)) {
            $rub = new Rubrique('', htmlspecialchars($_POST['id_rubrique']));                                   //  Récupération de l'id rubrique choisie
            $user = new Utilisateur(
                htmlspecialchars($_SESSION['Nom']),
                htmlspecialchars($_SESSION['Prenom']),
                '',
                htmlspecialchars($_SESSION['Email']),
                htmlspecialchars($_SESSION['Admin']),
                htmlspecialchars($_SESSION['ID'])
            );

            //DATE VALIDITE :: STRING
            $duree = new DateTime();
            $duree->add(new DateInterval('P'.htmlspecialchars($_POST['duree'].'D')));
            $dv = $duree->format('Y-m-d');
            //DATE du jour :: STRING
            $dt = new DateTime();
            $date = $dt->format('Y-m-d');
            //  Initialisation de l'annonce
            $annonce = new Annonce($user, $rub, htmlspecialchars($_POST['titre_annonce']), htmlspecialchars($_POST['description_ann']), $date, $dv);
            $annonceDAO = new MySQLAnnonceDAO();


            try {
                $annonceDAO->insert($annonce);                                                                  //  Insertion de l'annonce dans la BDD
                //****************************************      PHOTOS          *************************************************************************
                $fileName = $_FILES['file']['name'];
                if ('' == $fileName) {                                                                      //  Vérification si images présente si non présente alors:
                    $noImageHREF = 'upload/no-img.jpg';                                                     //  Chemin de la photo par défaut (no-image)
                    $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);                        //  Liaison de l'image à l'annonce et insertion dans la BDD X 3
                    $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                    $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                } else {                                                                                    //  SI image présente alors :
                    //IMAGE 1
                    $file = $_FILES['file'];
                    $fileName = $_FILES['file']['name'];                                                    //  Nom de l'image uploader
                    $fileTmpName = $_FILES['file']['tmp_name'];                                             //  Nom temporaire de l'image uploader
                    $fileSize = $_FILES['file']['size'];                                                    //  Taille de l'image uploader
                    $fileError = $_FILES['file']['error'];                                                  //  Code erreur de l'iamge
                    $fileType = $_FILES['file']['type'];                                                    //  Quelle extension du fichier

                    $fileExt = explode('.', $fileName);                                                     //  Recupération .extension de l'image
                    $fileActualExt = strtolower(end($fileExt));

                    $allowed = ['jpg', 'jpeg', 'png'];                                                      //  On autorise toutes ces extensions

                    if (in_array($fileActualExt, $allowed)) {                                               //  On compare l'extension, à notre tableau d'extension accepté
                        if (0 === $fileError) {                                                             //  On vérifie que le fichier uploader ne comporte pas d'érreur
                            if ($fileSize < 2000000) {                                                      //  On compare la taille du fichier par rapport à notre limite ici 2 MO
                                $fileNameNew = uniqid('', true).'.'.$fileActualExt;                         //  On renomme le fichier par un uniqid et on y ajoute à la fin (.)l'extension du fichier
                                $fileDestination = 'upload/'.$fileNameNew;                                  //  On détermine le chemin de destination du fichier
                                move_uploaded_file($fileTmpName, $fileDestination);                         //  On déplace le fichier de son emplacement temporaire à celui définitif
                                $annonceDAO->addPhotos($annonce->getId_annonce(), $fileDestination);        // On insère l'image en bdd avec son chemin et l'id annonce associé
                            } else {
                                echo "Votre fichiers est trop volumineux est n'a pas été télécharger";       // SI SIZE FICHIER > limite
                                $noImageHREF = 'upload/no-img.jpg';
                                $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);             // Insertion image par defaut
                            }
                        } else {
                            echo ' Une erreur est survenue lors du téléchargement de voter fichier';           //  SI ERROR > 0
                            $noImageHREF = 'upload/no-img.jpg';
                            $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);                    //  Insertion image par defaut
                        }
                    } else {
                        echo " Les fichiers télécharger ne corresponde pas aux types d'extensions accepter";       //   SI (.)Extension non valide
                        $noImageHREF = 'upload/no-img.jpg';
                        $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);                            //  Insertion image par défaut
                    }
                    //  IMAGE 2
                    if (isset($_FILES['file_1'])) {                                                             // test de présence (idem image 1) si oui alors :
                        $fileName1 = $_FILES['file_1']['name'];
                        if ('' == $fileName1) {                                                                      //  Vérification si images présente si non présente alors:
                            $noImageHREF = 'upload/no-img.jpg';                                                     //  Chemin de la photo par défaut (no-image)
                            $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                        } else {
                            $file1 = $_FILES['file_1'];
                            $fileName1 = $_FILES['file_1']['name'];
                            $fileTmpName1 = $_FILES['file_1']['tmp_name'];
                            $fileSize1 = $_FILES['file_1']['size'];
                            $fileError1 = $_FILES['file_1']['error'];
                            $fileType1 = $_FILES['file_1']['type'];

                            $fileExt1 = explode('.', $fileName1);
                            $fileActualExt1 = strtolower(end($fileExt1));

                            $allowed1 = ['jpg', 'jpeg', 'png'];

                            if (in_array($fileActualExt1, $allowed1)) {
                                if (0 === $fileError1) {
                                    if ($fileSize1 < 2000000) {
                                        $fileNameNew1 = uniqid('', true).'.'.$fileActualExt1;
                                        $fileDestination1 = 'upload/'.$fileNameNew1;
                                        move_uploaded_file($fileTmpName1, $fileDestination1);
                                        $annonceDAO->addPhotos($annonce->getId_annonce(), $fileDestination1);
                                    } else {
                                        echo "Votre fichiers est trop volumineux est n'a pas été télécharger";
                                        $noImageHREF = 'upload/no-img.jpg';
                                        $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                                    }
                                } else {
                                    echo ' Une erreur est survenue lors du téléchargement de voter fichier';
                                    $noImageHREF = 'upload/no-img.jpg';
                                    $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                                }
                            } else {
                                echo " Les fichiers télécharger ne corresponde pas aux types d'extensions accepter";
                                $noImageHREF = 'upload/no-img.jpg';
                                $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                            }
                        }
                    }
                    //  IMAGE 3
                    if (isset($_FILES['file_2'])) {                                                                     // Test de présence si oui (idem image 1):
                        $fileName2 = $_FILES['file_2']['name'];
                        if ('' == $fileName1) {                                                                      //  Vérification si images présente si non présente alors:
                            $noImageHREF = 'upload/no-img.jpg';                                                     //  Chemin de la photo par défaut (no-image)
                            $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                        } else {
                            $file2 = $_FILES['file_2'];
                            $fileName2 = $_FILES['file_2']['name'];
                            $fileTmpName2 = $_FILES['file_2']['tmp_name'];
                            $fileSize2 = $_FILES['file_2']['size'];
                            $fileError2 = $_FILES['file_2']['error'];
                            $fileType2 = $_FILES['file_2']['type'];

                            $fileExt2 = explode('.', $fileName2);
                            $fileActualExt2 = strtolower(end($fileExt2));

                            $allowed2 = ['jpg', 'jpeg', 'png'];

                            if (in_array($fileActualExt2, $allowed2)) {
                                if (0 === $fileError2) {
                                    if ($fileSize2 < 2000000) {
                                        $fileNameNew2 = uniqid('', true).'.'.$fileActualExt2;
                                        $fileDestination2 = 'upload/'.$fileNameNew2;
                                        move_uploaded_file($fileTmpName2, $fileDestination2);
                                        $annonceDAO->addPhotos($annonce->getId_annonce(), $fileDestination2);
                                    } else {
                                        echo "Votre fichiers est trop volumineux est n'a pas été télécharger";
                                        $noImageHREF = 'upload/no-img.jpg';
                                        $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                                    }
                                } else {
                                    echo ' Une erreur est survenue lors du téléchargement de voter fichier';
                                    $noImageHREF = 'upload/no-img.jpg';
                                    $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                                }
                            } else {
                                echo " Les fichiers télécharger ne corresponde pas aux types d'extensions accepter";
                                $noImageHREF = 'upload/no-img.jpg';
                                $annonceDAO->addPhotos($annonce->getId_annonce(), $noImageHREF);
                            }
                        }
                    }

                    echo ' Votre annonce a bien été poster ! ';                                                                     // Alors succès du dépot de photos
                }
            }catch (BDDException $e) {
                // A AMELIORER
                echo ' erreur';
            }
        } else {
            echo 'veuillez vous connecter au préalable';                                                                        //  Si clique sur depot annonce alors que non connecter
            incscriptionConnexion();
            exit();
        }
    }

    // .******************************************************          DELETE ANNONCE          ***********************************************************.

    function deleteAnnonce()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        $loader = new Twig\Loader\FilesystemLoader('templates');
        $twig = new Twig\Environment($loader, [
            'cache' => false, //.__DIR__.'/tmp',
        ]);
        $a = new MySQLAnnonceDAO();

        $images = $a->getHREFSById($_POST['ID_ANNONCE']);                                      //   Récupération des HREF images de l'annonce ciblé
        foreach($images as $href){                                                              //  Suppréssion des fichiers images dans le dossier upload correspondant si différent de no-image (image par defaut)
            if($href->HREF == "upload/no-img.jpg"){
                // DO NOTHING
            }
            else{
                unlink($href);
            }
        }
        $a->delete($_POST['ID_ANNONCE']);                                                   //  Supprésion de l'annonce ciblé en cascade image

        echo ' annonce supprimer ! ';
        if ('oui' === htmlspecialchars($_SESSION['Admin'])) {                                //  Si admin retour compte admin
            compteAdministrateur();
            exit();
        }
        //  Sinon retour à mon compte user
        monCompte();
    }

    //.*************************************************        UPDATE ANNONCE      *************************************************************.
    //TODO: AJOUT DES UPDATE PHOTOS

    function updateAnnonceBDD()
    {
        require_once 'vendor/autoload.php';
        require_once 'DAO/MySQLAnnonceDAO.php';
        require_once 'DAO/ConnexionBDD.php';
        require_once 'Domain/Annonce.php';
        require_once 'Domain/Rubrique.php';
        require_once 'Domain/Utilisateur.php';
        $a = new MySQLAnnonceDAO();
        $u = new Utilisateur('', '', '', '', '', htmlspecialchars($_SESSION['ID']));                                    //  Initialisation pour l'update WHERE
        $r = new Rubrique('', htmlspecialchars($_POST['id_rubrique']));                                                 //  Récupération du nouvelle ID rubrique

        $duree = new DateTime();                                                                                        //  Récupération de la date d'expiration
        $duree->add(new DateInterval('P'.htmlspecialchars($_POST['duree']).'D'));
        $dv = $duree->format('Y-m-d');
        //  Initialisation de l'annonce
        $annonce = new Annonce($u, $r, htmlspecialchars($_POST['titre_annonce']), htmlspecialchars($_POST['description_ann']), '', $dv, htmlspecialchars($_POST['ID_ANNONCE']));
      

        try {
            $a->update($annonce);                                                                                            // Mis à jour de l'annonce dans la BDD
                                                                         
            //****************************************      PHOTOS          *************************************************************************
            $fileName = $_FILES['file1']['name'];
            if ('' == $fileName) {                                                                      //  Si utilisateur n'a pas pas update de nouvelle image ne rien faire 
              
            } else {                                                                                    //  Sinon :
                //IMAGE 1

                $old_id_image= $_POST['OLD_ID_IMAGE_1'];                                                 // Récupération de l'ID image à update
                $old_href_image = $_POST['OLD_HREF_1'];                                                  // Récupréation du chemin ou se trouve l'ancienne image
                $file = $_FILES['file1'];
                $fileName = $_FILES['file1']['name'];                                                    //  Nom de l'image uploader
                $fileTmpName = $_FILES['file1']['tmp_name'];                                             //  Nom temporaire de l'image uploader
                $fileSize = $_FILES['file1']['size'];                                                    //  Taille de l'image uploader
                $fileError = $_FILES['file1']['error'];                                                  //  Code erreur de l'iamge
                $fileType = $_FILES['file1']['type'];                                                    //  Quelle extension du fichier

                $fileExt = explode('.', $fileName);                                                     //  Recupération .extension de l'image
                $fileActualExt = strtolower(end($fileExt));

                $allowed = ['jpg', 'jpeg', 'png'];                                                      //  On autorise toutes ces extensions

                if (in_array($fileActualExt, $allowed)) {                                               //  On compare l'extension, à notre tableau d'extension accepté
                    if (0 === $fileError) {                                                             //  On vérifie que le fichier uploader ne comporte pas d'érreur
                        if ($fileSize < 2000000) {                                                      //  On compare la taille du fichier par rapport à notre limite ici 2 MO
                            $fileNameNew = uniqid('', true).'.'.$fileActualExt;                         //  On renomme le fichier par un uniqid et on y ajoute à la fin (.)l'extension du fichier
                            $fileDestination = 'upload/'.$fileNameNew;                                  //  On détermine le chemin de destination du fichier
                            move_uploaded_file($fileTmpName, $fileDestination);                         //  On déplace le fichier de son emplacement temporaire à celui définitif
                            $a->updatePhotoAnnonce($old_id_image,$fileDestination);                     //  On update le nouveau chemin du fichier correspondant à l'annonce dans la BDD

                            if ('upload/no-img.jpg' != $old_href_image) {                               //  Si l'ancien HREF est différent de image par defaut(no-image.jpg) alors:
                                unlink($old_href_image);                                                //  Supprime l'ancienne image, dans le dossier update
                            }
                            else{                                                                       //  Sinon:
                                //  Do nothing
                            }
                        } else {
                            echo "Votre fichiers est trop volumineux est n'a pas été télécharger";       // SI SIZE FICHIER > limite
                            $noImageHREF = 'upload/no-img.jpg';
                            $a->updatePhotoAnnonce($old_id_image,$noImageHREF);                         // Insertion image par defaut
                        }
                    } else {
                        echo ' Une erreur est survenue lors du téléchargement de voter fichier';           //  SI ERROR > 0
                        $noImageHREF = 'upload/no-img.jpg';
                        $a->updatePhotoAnnonce($old_id_image,$noImageHREF);                                 //  Insertion image par defaut
                    }
                } else {
                    echo " Les fichiers télécharger ne corresponde pas aux types d'extensions accepter";       //   SI (.)Extension non valide
                    $noImageHREF = 'upload/no-img.jpg';
                    $a->updatePhotoAnnonce($old_id_image,$noImageHREF);                                         //  Insertion image par défaut
                }
            }
                //  IMAGE 2
                if (isset($_FILES['file2'])) {                                                             // test de présence (idem image 1) si oui alors :
                    $fileName1 = $_FILES['file2']['name'];
                    if ('' == $fileName1) {                                                                      //   Si utilisateur n'a pas pas update de nouvelle image ne rien faire 
                       
                    } else {                                                                                    //  Sinon :
                        $old_id_image1 = $_POST['OLD_ID_IMAGE_2'];
                        $old_href_image2 = $_POST['OLD_HREF_2'];

                        $file1 = $_FILES['file2'];
                        $fileName1 = $_FILES['file2']['name'];
                        $fileTmpName1 = $_FILES['file2']['tmp_name'];
                        $fileSize1 = $_FILES['file2']['size'];
                        $fileError1 = $_FILES['file2']['error'];
                        $fileType1 = $_FILES['file2']['type'];

                        $fileExt1 = explode('.', $fileName1);
                        $fileActualExt1 = strtolower(end($fileExt1));

                        $allowed1 = ['jpg', 'jpeg', 'png'];

                        if (in_array($fileActualExt1, $allowed1)) {
                            if (0 === $fileError1) {
                                if ($fileSize1 < 2000000) {
                                    $fileNameNew1 = uniqid('', true).'.'.$fileActualExt1;
                                    $fileDestination1 = 'upload/'.$fileNameNew1;
                                    move_uploaded_file($fileTmpName1, $fileDestination1);
                                    $a->updatePhotoAnnonce($old_id_image1,$fileDestination1);
                                    if ('upload/no-img.jpg' != $old_href_image2) {
                                        unlink($old_href_image2);
                                    }
                                    else{
                                        //  Do nothing
                                    }
                                } else {
                                    echo "Votre fichiers est trop volumineux est n'a pas été télécharger";
                                    $noImageHREF = 'upload/no-img.jpg';
                                    $a->updatePhotoAnnonce($old_id_image1,$noImageHREF);
                                }
                            } else {
                                echo ' Une erreur est survenue lors du téléchargement de voter fichier';
                                $noImageHREF = 'upload/no-img.jpg';
                                $a->updatePhotoAnnonce($old_id_image1,$noImageHREF);
                            }
                        } else {
                            echo " Les fichiers télécharger ne corresponde pas aux types d'extensions accepter";
                            $noImageHREF = 'upload/no-img.jpg';
                            $a->updatePhotoAnnonce($old_id_image1,$noImageHREF);;
                        }
                    }
                }
                //  IMAGE 3
                if (isset($_FILES['file3'])) {                                                                     // Test de présence si oui (idem image 1):
                    $fileName2 = $_FILES['file3']['name'];
                    if ('' == $fileName2) {                                                                       //    Si utilisateur n'a pas pas update de nouvelle image ne rien faire 

                    } else {                                                                                       //   Sinon :

                        $old_id_image2 = $_POST['OLD_ID_IMAGE_3'];
                        $old_href_image3 = $_POST['OLD_HREF_3'];
                        $file2 = $_FILES['file3'];
                        $fileName2 = $_FILES['file3']['name'];
                        $fileTmpName2 = $_FILES['file3']['tmp_name'];
                        $fileSize2 = $_FILES['file3']['size'];
                        $fileError2 = $_FILES['file3']['error'];
                        $fileType2 = $_FILES['file3']['type'];

                        $fileExt2 = explode('.', $fileName2);
                        $fileActualExt2 = strtolower(end($fileExt2));

                        $allowed2 = ['jpg', 'jpeg', 'png'];

                        if (in_array($fileActualExt2, $allowed2)) {
                            if (0 === $fileError2) {
                                if ($fileSize2 < 2000000) {
                                    $fileNameNew2 = uniqid('', true).'.'.$fileActualExt2;
                                    $fileDestination2 = 'upload/'.$fileNameNew2;
                                    move_uploaded_file($fileTmpName2, $fileDestination2);
                                    $a->updatePhotoAnnonce($old_id_image2,$fileDestination2);
                                    if ($old_href_image3 != "upload/no-img.jpg"){
                                        unlink($old_href_image3);
                                    }
                                    else{
                                        //  Do nothing
                                    }
                                } else {
                                    echo "Votre fichiers est trop volumineux est n'a pas été télécharger";
                                    $noImageHREF = 'upload/no-img.jpg';
                                    $a->updatePhotoAnnonce($old_id_image2,$noImageHREF);
                                }
                            } else {
                                echo ' Une erreur est survenue lors du téléchargement de voter fichier';
                                $noImageHREF = 'upload/no-img.jpg';
                                $a->updatePhotoAnnonce($old_id_image2,$noImageHREF);
                            }
                        } else {
                            echo " Les fichiers télécharger ne corresponde pas aux types d'extensions accepter";
                            $noImageHREF = 'upload/no-img.jpg';
                            $a->updatePhotoAnnonce($old_id_image2,$noImageHREF);
                        }
                    }
                }
            }
        catch (BDDException $e) {
            // A AMELIORER
            echo ' erreur';
        }

        echo 'Annonce modifier ! ';

        if ('oui' === $_SESSION['Admin']) {                                                                               // SI admin = oui alors retour compte admin sinon mon compte user
            compteAdministrateur();
            exit();
        }
        monCompte();
    }

    //.*************************************************        MAIL DE CONFIRMATION ADRESSE MAIL        *************************************************************.
    function confirmMail(Utilisateur $user, $key)
    {
        $prenom = $user->getPrenom();
        $mail = $user->getEmail();
        $to = $mail; // notez la virgule

        // Sujet
        $subject = 'Confirmation de votre compte';

        // message

        $header = "MIME-Version: 1.0\r\n";
        $header .= 'From:"Support l Annonceur "<lannonceur.fil.rouhe@gmail.com>'."\n";
        $header .= 'Content-Type:text/html; charset="uft-8"'."\n";
        $header .= 'Content-Transfer-Encoding: 8bit';

        $message = "
            <html>
            <head>
            <title>Confirmation de votre compte</title>
           
            </head>
            <body>
            
                <div align='center'> 
                    <p> Bonjour {$prenom} </p>
                    <br>
                    <p> Vous venez de vous inscrire sur <a href='http://localhost/Annonce/main.php'>L'annonceur.fr</a></p>
                    <p> Pour utiliser toutes les fonctions de notre site vous devez confimer votre adresse mail,</p>
                    <p> en cliquant sur le lien de confirmation suivant : </p>
                    <br>      
                    
                    <sytong></sytong><a href='http://localhost/Annonce/main.php?action=confirmation&mail=".urlencode($mail).'&confirmKey='.$key."'></strong>
                    Confirmer votre adresse mail ! </a>
                    <br>
                  <p>******************************************************************************************************</p>
                    <strong></strong> <p> NE PAS REPONDRE AU MAIL, CE MAIL A ETE GENEREE AUTOMATIQUEMENT </p></strong>
                </div>
            </body>
            </html>
            ";
        //  Lien de validation :action confirmationmail(get pour intercepter dans le controleur main ) + on encode le mail user + la clé de confirmation pour pouvoir comparer sur page confirmation
        mail($to, $subject, $message, $header);     // Envoi du mail
    }

    //.*************************************************        MAIL DE REINITIALISATION MOT DE PASSE       *************************************************************.
    function mailMDP(Utilisateur $user, $key)
    {
        $prenom = $user->getPrenom();
        $mail = $user->getEmail();
        $id_user = $user->getId_user();
        $to = $mail; // notez la virgule

        // Sujet
        $subject = 'Reinitialisation de votre mot de passe';

        // message

        $header = "MIME-Version: 1.0\r\n";
        $header .= 'From:"Support l Annonceur "<lannonceur.fil.rouhe@gmail.com>'."\n";
        $header .= 'Content-Type:text/html; charset="uft-8"'."\n";
        $header .= 'Content-Transfer-Encoding: 8bit';

        $message = "
            <html>
            <head>
            <title>Reinitialisation de votre mot de passe</title>
            </head>
            <body>
            
                <div align='center'> 
                    <p> Bonjour {$prenom} </p>
                    <br>
                    <p> Vous venez de faire une demande de réinitialisation de votre mot de passe</a></p>
                    <p> Pour ce faire veuillez cliquer sur le lien suivant :</p>
                    <br>
                    <a href='http://localhost/Annonce/main.php?action=reinitialisationMDP&ID_USER=".urlencode($id_user).'&KEY_MDP='.$key."'>
                    Confirmer la réinitialisation de votre mot de passe ! </a>
                    <br>

                    <strong><p>Si vous n'êtes pas à l'origine de cette demande veuillez suivre ce lien pour annuler la demande</p></strong>
                    <a href='http://localhost/Annonce/main.php?action=cancelreinitpassword&ID_USER=".urlencode($id_user)."'>Annuler la demande de réinitialisation</a>
                  <p>******************************************************************************************************</p>
                    <strong></strong> <p> NE PAS REPONDRE AU MAIL, CE MAIL A ETE GENEREE AUTOMATIQUEMENT </p></strong>
                </div>
            </body>
            </html>
            ";
        // Lien de réinitialisation MDP:
        //  action reinitilisation MDP (sera intercepter par le controleur main)+ ID_USER+ KEY_MDP pour comparer lors de l'update en BDD que les inforamtions correspondent

        //  Lien d'annulation:
        //  actiob cancelreinitpassword( sera intercepter par le controleur main)+ ID_USER pour annuler la demande et remet l'ancien mot de passe comme actif
        mail($to, $subject, $message, $header);
    }
    //***************************************************       ANNULATION REINITIALISATION MDP (via mail)    ****************************************************************** */
    function cancelreinitpass()
    {
        require_once 'DAO/ConnexionBDD.php';
        require_once 'DAO/MySQLUtilisateurDAO.php';

        $userDAO = new MySQLUtilisateurDAO();

        $userDAO->cancelreinitialiseMDP($_GET['ID_USER']);                                          //Récupération de l'id et restoration de l'ancien mot de passe en tant qu'actif
        afficheracceuil();
        echo ' Reinitialisation du mot de passe annulé, vous conservez votre ancien mot de passe';
    }

     //*****************************************************     UPDATE MOT DE PASSE (via lien mail)     *********************************************** */
     function updateMDP()
     {
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLUtilisateurDAO.php';
         require_once 'Domain/Utilisateur.php';
         $mdp = new MySQLUtilisateurDAO();
         $user = new Utilisateur('', '', htmlspecialchars($_POST['PASSWORD']), '', '', htmlspecialchars($_POST['ID_USER']));
         $mdp->updateMDP($user);
         echo 'modification du mot de passe effectué';
         afficheracceuil();
     }

    /*.*********************************************************************************************************************************************************************************************.
     * .***************************************************************  FONCTION ADMINISTARTEUR   *************************************************************************************************.
     * .*********************************************************************************************************************************************************************************************.
     */
    //*****************************************************     SUPPRESION ANNONCE PERIMEE      *********************************************** */
     function deletePerimes()
     {
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLAnnonceDAO.php';

         $annonce = new MySQLAnnonceDAO();
         $annonce->deletePerimees();                                            //  Supprime les annonces dont la date de validité est inférieur à aujourd'hui
     }
     //*****************************************************     AJOUT UNE RUBRIQUE      ******************************************************************** */
     function ajoutRubrique()
     {
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLRubriqueDAO.php';
         require_once 'Domain/Rubrique.php';
         $r = new MySQLRubriqueDAO();
         $rub = new Rubrique(htmlspecialchars($_POST['LIBELLE']));
         $r->insert($rub);                                                              //  Insertion dans la BDD d'un nouveau libelle de rubrique
     }
     //*****************************************************     UPDATE LIBELLE RUBRIQUE      ************************************************************************ */
     function updateRubrique()
     {
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLRubriqueDAO.php';
         require_once 'Domain/Rubrique.php';
         $rub = new Rubrique('', htmlspecialchars($_POST['ID_RUBRIQUE']));                              //  Selection de la rubrique à modifier
         $r = new MySQLRubriqueDAO();
         $r->update($rub, htmlspecialchars($_POST['LIBELLE']));                                         //  Modification du libellé
     }
     //*****************************************************     SUPPRESION RUBRIQUE           *************************************************************** */
     function deleteRubrique()
     {
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLRubriqueDAO.php';
         require_once 'Domain/Rubrique.php';
         $rub = new Rubrique('', htmlspecialchars($_POST['ID_RUBRIQUE']));                              //  Selection de la rubrique
         $r = new MySQLRubriqueDAO();
         $r->delete($rub);                                                                              //  Suppresion de la rubrique
         compteAdministrateur();                                                                        //  Retour compte admin
     }
     //*****************************************************     SUPPRESION UTILISATEUR           ***************************************************************** */
     function deleteUser()
     {
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLUtilisateurDAO.php';
         require_once 'Domain/Utilisateur.php';
         require_once 'vendor/autoload.php';
         $loader = new Twig\Loader\FilesystemLoader('templates');
         $twig = new Twig\Environment($loader, [
             'cache' => false, //.__DIR__.'/tmp',
         ]);

         $user = new MySQLUtilisateurDAO();
         $query= $_POST['searchUser'];

         if(isset($query) AND !empty ($query)){
           
                $s = explode(' ', $query);
                $i = 0;
                $requeteSQL = 'SELECT * FROM utilisateur ';
                foreach ($s as $value) {
                    if (strlen($s > 3)) {
                        if (0 == $i) {
                            $requeteSQL .= 'WHERE ';
                        } else {
                            $requeteSQL .= ' OR ';
                        }
                        $requeteSQL .= "NOM LIKE '%{$value}%'";
                        ++$i;
                    }
                }
            $rechetrcheUser = $user->searchQuery($requeteSQL);
            $twig->render('compteAdministrateur.twig',[
                'rechercheUser'=> $rechetrcheUser,
            ]);
            exit();
         } 

         $u = new Utilisateur('', '', '', '', '', htmlspecialchars($_POST['ID_USER']));                 //  Selection d'un utilisateur
         $user->delete($u);                                                                             //  Suppresion de celui-çi
         compteAdministrateur();                                                                        //  Retour compte admin
     }

     //*****************************************************     REINITIALISATION DU MOT DE PASSE D'UN USER      *********************************************** */
     function reinitialiseMDP()
     {
         require_once 'DAO/ConnexionBDD.php';
         require_once 'DAO/MySQLUtilisateurDAO.php';
         require_once 'Domain/Utilisateur.php';
         $rmdp = new MySQLUtilisateurDAO();
         $utilisateur = new Utilisateur('', '', '', '', '', $_POST['ID_USER']);                                 //  Selection d'un utilisateur
         $rmdp->identifierID($utilisateur);                                                                     //  Recherche et incrémentation des données sur le user via BDD (ex: prenom, nom etc..)
                                                                                                                //  Création de la key mdp uniq
         $lengthKey = 15;
         $key = '';
         for ($i = 1; $i < $lengthKey; ++$i) {
             $key .= mt_rand(0, 9);
             $keyok = $key;
         }
         $rmdp->reinitialiseMDP(htmlspecialchars($_POST['ID_USER']), $keyok);                                     // Reinitialisation du mdp, et insert de la key mdp pour vérification + tard
         mailMDP($utilisateur, $key);                                                                             //  Envoi du mail de reinitialisation
         echo "Le mot de passe est réinitialiser, l'utilisateur as reçu un mail pour modifier son mot de passe ";
         compteAdministrateur();                                                                                    //  Retour compte admin
     }


     function listerAnnonceAjax1(){
         require_once 'vendor/autoload.php';
         require_once 'DAO/MySQLAnnonceDAO.php';
         require_once 'DAO/ConnexionBDD.php';
         require_once 'Domain/Rubrique.php';
         require_once 'DAO/MySQLRubriqueDAO.php';

         $rubs = new MySQLRubriqueDAO();
         $loader = new Twig\Loader\FilesystemLoader('templates');
         $twig = new Twig\Environment($loader, [
             'cache' => false, //.__DIR__.'/tmp',
         ]);
         if (isset( $_GET['ID_RUBRIQUE']) AND !empty( $_GET['ID_RUBRIQUE'])){
            $rub = new Rubrique('', $_GET['ID_RUBRIQUE']);
            $annonceDAO = new MySQLAnnonceDAO();
            $annrub = $annonceDAO->getByRubrique($rub);
           echo json_encode($annrub, JSON_UNESCAPED_UNICODE);
            exit();
         }

         $url = $_SERVER['PHP_SELF'];
         if (!empty($_SESSION)) {
             $prenom = $_SESSION['Prenom'];
             $admin = $_SESSION['Admin'];
             $mail = $_SESSION['Email'];
             $nom = $_SESSION['Nom'];

             echo $twig->render(
                     'listerAnnonceAjax1.html.twig',
                     [
                         'url' => $url,
                         'nom' => $nom,
                         'prenom' => $prenom,
                         'admin' => $admin,
                         'mail' => $mail,
                         'rubs' => $rubs->getAll(),
                     ]
                 );
             exit();
         }
       
         echo $twig->render(
                    'listerAnnonceAjax1.html.twig',
                    [
                        'url' => $url,
                        'rubs' => $rubs->getAll(),
                    ]
                );
        
     }



        