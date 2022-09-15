function set_document_by_id_value(id,valeur){
    return document.getElementById(id).innerText = valeur
}
var host =window.location.origin
var data = {request :document.getElementById('')};
setInterval(function (){
    $.ajax({
        type:"GET",
        url:host+"/jsonHistory" ,
        dataType: 'json',
        data:data,
        success: function (result){
            set_document_by_id_value('historique',result['historique']);

        }
    })

},60000)