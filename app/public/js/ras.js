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

            set_document_by_id_value('sonde_oxygen',result['oxygen']);
            set_document_by_id_value('sonde_niveau',result['niveau']);
            set_document_by_id_value('sonde_pump',result['pression']);


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


            set_document_by_id_value('sonde_oxygen_ras2p',result['oxygen_ras2p']);
            set_document_by_id_value('sonde_niveau_ras2p',result['niveau_ras2p']);
            set_document_by_id_value('sonde_pump_ras2p',result['pression_ras2p']);

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

            if (result['f_on'] === true){
                set_document_by_id_value('filtre_ras1','Allumé');
            }else{
                set_document_by_id_value('filtre_ras1','Eteint');
            }

            if (result['a_off'] === true){
                set_document_by_id_value('alarm_ras1','Allumé')
            }else {
                set_document_by_id_value('alarm_ras1','Eteint')
            }

            if (result['p_on'] === true){
                set_document_by_id_value('pump_ras1','Allumé')
            }else{
                set_document_by_id_value('pump_ras1','Eteint')
            }

            document.getElementById('message_filtre').hidden = result['f_on'] === true;

            document.getElementById('message_alarm').hidden = result['a_off'] === true;

            document.getElementById('message_pompe').hidden = result['p_on'] === true;
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

            if (result['f_on_ras2'] === true){
                set_document_by_id_value('filtre_ras2','Allumé');
            }else{
                set_document_by_id_value('filtre_ras2','Eteint');
            }

            if (result['a_off_ras2'] === true){
                set_document_by_id_value('alarm_ras2','Allumé')
            }else {
                set_document_by_id_value('alarm_ras2','Eteint')
            }

            if (result['p_on_ras2'] === true){
                set_document_by_id_value('pump_ras2','Allumé')
            }else{
                set_document_by_id_value('pump_ras2','Eteint')
            }

            document.getElementById('message_filtre_ras2').hidden = result['f_on_ras2'] === true;

            document.getElementById('message_alarm_ras2').hidden = result['a_off_ras2'] === true;

            document.getElementById('message_pompe_ras2').hidden = result['p_on_ras2'] === true;
        }
    })

},4000)

