$(document).ready(function(){

    
    $("#rubrique").change(function(){
        $.ajax({

            type: "GET",
            url: "main.php",
            data: {"action":"listerAnnonceAjax1", "ID_RUBRIQUE":$("#rubrique").val()},
            dataType: "json",
            mimetype:"application/json",
            success: function (data) {
     
                $.each(data, function (k, v) {
                   
                    var output = `<div class="contenair-fluid">
                    <div class="container fluid list_annonce" >
                    <div class="row">
                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 ml-auto mr-auto Entete " a href="#">`;
                    output += v.EN_TETE+`</div>`;
                    output += `</div>
                        <div class="row">
                            <article class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                <img scr="./../upload/no-img.png">
                                
                            </article>
                    
                            <article class="col-md-9 col-lg-9 col-xs-12 col-sm-12 ml-auto mr-auto">
                                <div>
                                    <br>
                                    <p>`
                    output += v.CORP + '</p>';
                    output += `<br>
                    </div>
                    <div class="row">
                        <b><p id="dd" > Date de dépot :`

                    output += v.DATE_DEPOT + '</p> </b>';
                    output += ` <br>
                                 </article>	
                                     </div>
            
                                         </div>
                                     <br></br>
                                       </div> </div> `               
                
           
                $("#retourServeur").html(output,) })
                
            },
            error: function (data){
                successmessage = "erreur de la function success";
                $("#retourServeur").text(successmessage)
            }
        })
    })
})
