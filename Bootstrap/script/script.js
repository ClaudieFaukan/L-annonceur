$(function() {
  //DEBUT PAGE INSCRIPTION-CONNEXION

/**.***************************************************                           **************************************************************************************.
 * .***************************************************                           **************************************************************************************.
 * .***************************************************       FONCTION USER     **************************************************************************************.
 * .***************************************************                           **************************************************************************************.
 .*****************************************************                           **************************************************************************************.*/


    //***************************************************   VERIFICATION LORS DU LOGIN  ******************************************************

    $("#sub_connexion").click(function() {
      var valid = true;
        //EMAIL
      if ($('#InputEmail').val() == '') {
        $('#InputEmail').css("border-color", "red").next(".erreur_message").fadeIn().text("Email manquant");
        valid = false;
      } else if (!$("#InputEmail").val().match(/^[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-]+@[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-]+\.[a-z]{2,6}$/i)) {
        $("#InputEmail").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez entrer une adresse valide");
        valid = false;
      } else {
        $('#InputEmail').css("border-color", "green").next(".erreur_message").fadeOut();
      }
      //PASSWORD (non crypter,non proteger de l'injection ?)
      if ($("#InputPassword").val() == "") {
        $("#InputPassword").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez saisir votre mot de passe");
        valid = false;
      } else if (!$("#InputPassword").val().match(/^(?=.*[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-])(?=.*[0-9]).{8,}$/i)) {
        $("#InputPassword").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez vérifier votre mot de passe, (minimum 8 caractères)");
        valid = false;
      } else {
        $("#InputPassword").css("border-color", "green").next(".erreur_message").fadeOut();

      }
      return valid;
    });
    //***********************************************  VERIFICATION LORS DE L'INSCRIPTION   *****************************************************************
    $("#sub_insc").click(function() {

      var valid = true;

      //EMAIL
      if ($('#Email_insc').val() == '') {
        $('#Email_insc').css("border-color", "red").next(".erreur_message").fadeIn().text("Email manquant");
        valid = false;
      } else if (!$("#Email_insc").val().match(/^[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-]+@[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-]+\.[a-z]{2,6}$/i)) {
        $("#Email_insc").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez entrer une adresse valide");
        valid = false;
      } else {
        $('#Email_insc').css("border-color", "green").next(".erreur_message").fadeOut();
      }
        //PASSWORD 1 (non crypter,non proteger de l'injection ?)
      if ($("#Password_insc").val() == "") {
        $("#Password_insc").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez saisir votre mot de passe");
        valid = false;
      } else if (!$("#Password_insc").val().match(/^(?=.*[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-])(?=.*[0-9]).{8,}$/i)) {
        $("#Password_insc").css("border-color", "red").next(".erreur_message").fadeIn().text("minimum 8 caractères, au moins 1 Majuscule et 1 chiffres  ca ");
        valid = false;
      } else {
        $("#Password_insc").css("border-color", "green").next(".erreur_message").fadeOut();
          // PASWWORD 2
        if ($("#Password_insc").val() === $("#Password_insc1").val()){
          $("#Password_insc1").css("border-color", "green").next(".erreur_message").fadeOut();
          valid=true
        }
        else{
          $("#Password_insc1").css("border-color", "red").next(".erreur_message").fadeIn().text("Mot de passe non identique");
          valid= false;
        }
      }
      //NOM
      if ($('#Name_id').val() == '') {
        $('#Name_id').css("border-color", "red").next(".erreur_message").fadeIn().text("Nom manquant");
        valid = false;
      } else if (!$('#Name_id').val().match(/^[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-]{2}[^0-9,;./:!§ù%µ*$£¤=+)@`|{}"#~&²]+$/g)) {
        $('#Name_id').css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez entrer un nom valide");
        valid = false;
      } else {
        $('#Name_id').css("border-color", "green").next(".erreur_message").fadeOut();
      }
      //PRENOM
      if ($('#FirstName_id').val() == '') {
        $('#FirstName_id').css("border-color", "red").next(".erreur_message").fadeIn().text("Prénom manquant");
        valid = false;
      } else if (!$('#FirstName_id').val().match(/^[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-]{2}[^0-9,;./:!§ù%µ*$£¤=+)@`|{}"#~&²]+$/g)) {
        $('#FirstName_id').css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez entrer un prénom valide");
        valid = false;
      } else {
        $('#FirstName_id').css("border-color", "green").next(".erreur_message").fadeOut();
        
      }



      return valid;
    });
  //FIN DE PAGE INSCRIPTION-connexion

  //*********************************************   PAGE CREATION/UPDATE  annonce   **********************************************
  $("#subm_id").click(function(){
    var valid=true;

    //Description
    if ($('#description_id').val() == '') {
      $('#description_id').css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez ajouter une description");
      valid = false;
    }
    else {
      $('#description_id').css("border-color", "green").next(".erreur_message").fadeOut();
    }
    //TITRE
    if ($('#titre_annonce').val() == '') {
      $('#titre_annonce').css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez ajouter une description");
      valid = false;
    }
    else {
      $('#titre_annonce').css("border-color", "green").next(".erreur_message").fadeOut();
    }
    //Selection rubrique
    if($("#rub_id option:selected").val() == "0"){
      $('#rub_id').css("border-color", "red");

      $("#group_option").next(".erreur_message").fadeIn().text(" Veuillez selectionner une catégorie");
      valid = false;
    }
    else {
      $('#rub_id').css("border-color", "green");
      $("#group_option").next(".erreur_message").fadeOut();
    }

    return valid;

  });
    //*****************************************************  PREVIEW IMAGE   ******************************************************************
    //image 1
    function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function (e) {
              //id <img scr="#"
              $('#imagePreview').attr('src', e.target.result);
          }
          
          reader.readAsDataURL(input.files[0]);
      }
    }
    // id input upload file
    $("#imgInp").change(function(){
        readURL(this);
    });

    //image2
    function readURL1(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function (e) {
              $('#imagePreview_1').attr('src', e.target.result);
          }
          
          reader.readAsDataURL(input.files[0]);
      }
    }
    $("#imgInp_1").change(function(){
        readURL1(this);
    });
  //image 3
  function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#imagePreview_2').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
  }
  $("#imgInp_2").change(function(){
    readURL2(this);
  });

  /**
   * *************************************************** UPDATE PHOTO ANNONCE *******************************************************************.
   */

    //image 1
    function readURLUp(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function (e) {
              //id <img scr="#"
              $('#imagePreviewUpdate1').attr('src', e.target.result);
          }
          
          reader.readAsDataURL(input.files[0]);
      }
    }
    // id input upload file
    $("#imgInpUpdate1").change(function(){
        readURLUp(this);
    });

    //image2
    function readURLUp1(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function (e) {
              $('#imagePreviewUpdate2').attr('src', e.target.result);
          }
          
          reader.readAsDataURL(input.files[0]);
      }
    }
    $("#imgInpUpdate2").change(function(){
        readURLUp1(this);
    });
  //image 3
  function readURLUp2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#imagePreviewUpdate3').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
  }
  $("#imgInpUpdate3").change(function(){
    readURLUp2(this);
  });



  /**.***************************************************                           **************************************************************************************.
 * .***************************************************                           **************************************************************************************.
 * .***************************************************       FONCTION ADMIN      **************************************************************************************.
 * .***************************************************                           **************************************************************************************.
 .*****************************************************                           **************************************************************************************.*/


  //****************************************************** UPDATE PASSWORD ************************************************************** */

  $("#reinitMDP").click(function() {
    var valid = true;
      //PASSWORD 1 (non crypter,non proteger de l'injection ?)
      if ($("#Password_update").val() == "") {
        $("#Password_update").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez saisir votre mot de passe");
        valid = false;
      } else if (!$("#Password_update").val().match(/^(?=.*[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-])(?=.*[0-9]).{8,}$/i)) {
        $("#Password_update").css("border-color", "red").next(".erreur_message").fadeIn().text("minimum 8 caractères, au moins 1 Majuscule et 1 chiffres  ca ");
        valid = false;
      } else {
        $("#Password_update").css("border-color", "green").next(".erreur_message").fadeOut();
  
          // PASWWORD 2
          if (!($("#Password_update1").val() == "")  && !($("#Password_update").val() == "")){
            if ($("#Password_update1").val() === $("#Password_update").val()){
              $("#Password_update1").css("border-color", "green").next(".erreur_message").fadeOut();
             valid = true;
            }
          }
        else{
          $("#Password_update1").css("border-color", "red").next(".erreur_message").fadeIn().text("Mot de passe non identique");
          valid= false;
        }
      }
      return valid;
  });
  //****************************************************** UPDATE rubrique ************************************************************** */

  $("#addRubrique").click(function() {
    var valid = true;
 
    if ($("#libelle_rubrique").val() == "") {
      $("#libelle_rubrique").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez saisir votre libellé rubrique");
      valid = false;
    } else if (!$("libelle_rubrique").val().match(/^(?=.*[a-zA-ZÀ-ÖØ-öø-ÿœŒ.0-9._-])(?=.*[0-9]).{2,}$/i)) {
      $("#libelle_rubrique").css("border-color", "red").next(".erreur_message").fadeIn().text("Veuillez vérifier votre libellé, (minimum 2 caractères)");
      valid = false;
    } 
    else {
      $("#libelle_rubrique").css("border-color", "green").next(".erreur_message").fadeOut();
    }
    return valid;
  });


});
