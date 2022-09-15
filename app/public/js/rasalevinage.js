function set_document_by_id_value(id,valeur){
    return document.getElementById(id).innerText = valeur
}

var data = {request :document.getElementById('')};
setInterval(function (){
    $.ajax({
        type:"GET",
        url:"/JsonValue" ,
        dataType: 'json',
        data:data,
        success: function (result){

            set_document_by_id_value('sonde_oxygen_ext',result['oxygen_ext']);
            set_document_by_id_value('sonde_niveau',result['niveau_alv']);
            set_document_by_id_value('sonde_temperature ',result['temperature']);



        }
    })

},4000)
setInterval(function (){
    $.ajax({
        type:"GET",
        url:"/JsonValue" ,
        dataType: 'json',
        data:data,
        success: function (result){


            if (result['pompe1'] === true){
                set_document_by_id_value('alarm_pompe_1','Allumé');
            }else{
                set_document_by_id_value('alarm_pompe_1','Eteint');
            }

            if (result['aoxy_ext'] === true){
                set_document_by_id_value('alarm_oxy_ext','Allumé')
            }else {
                set_document_by_id_value('alarm_oxy_ext','Eteint')
            }

            if (result['aoxy_aval'] === true){
                set_document_by_id_value('alarm_oxy_aval','Allumé')
            }else{
                set_document_by_id_value('alarm_oxy_aval','Eteint')
            }

            document.getElementById('message_pompe1').hidden = result['pompe1'] === true;

            document.getElementById('message_oxy_ext').hidden = result['aoxy_ext'] === true;

            document.getElementById('message_oxy_aval').hidden = result['aoxy_aval'] === true;

        }
    })

},4000)
