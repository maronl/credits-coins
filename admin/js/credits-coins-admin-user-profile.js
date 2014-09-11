jQuery(function() {
    jQuery('#btn-visualizza-movimenti').click(function(event){
        event.preventDefault();
        if(!jQuery(this).hasClass('button-disabled')){
            jQuery(this).addClass('button-disabled');
            jQuery.post(
                ajaxurl,
                {
                    'action':'user_credits_movements',
                    'user':jQuery(this).attr('href')
                },
                function(response){
                    if(response.status == 'ok'){
                        htmlLatestRecharges = buildHTMLForLatestRecharges(response.data);
                        jQuery('#wrapper-latest-recharges').html('');
                        jQuery('#wrapper-latest-recharges').append(htmlLatestRecharges);
                        jQuery('#btn-visualizza-movimenti').removeClass('button-disabled');
                        jQuery('#btn-visualizza-movimenti').html('Aggiorna ultimi 15 movimenti');
                    }else{
                        alert("Errore nel caricamento ultimi movimenti");
                    }
                },
                'json'
            );
        }
    });

    jQuery('#btn-scarica-movimenti').click(function(event){
        event.preventDefault();
        alert("to be done!");
    });

    function buildHTMLForLatestRecharges(jsonLatestRecharges){
        countAllElement = jsonLatestRecharges.length;
        html = "<table cellpadding='10'>";
        html = html + "<tr><th>ID</th>";
        html = html + "<th>Operatore</th>";
        html = html + "<th>Destinatario</th>";
        html = html + "<th>Data</th>";
        html = html + "<th>Crediti</th>";
        html = html + "<th>Strumento</th>";
        html = html + "<th>Descrizione</th></tr>";

        for(var i = 0; i<countAllElement; i++) {
            jsonSingleRow = jsonLatestRecharges[i];
            htmlSingleRow = buildHTMLForSingleRow(jsonSingleRow);
            html = html + htmlSingleRow;
        }
        html = html + "</table>";
        return html;
    }

    function buildHTMLForSingleRow(jsonSingleRow){
        htmlSingleItem = "<tr>";
        htmlSingleItem += "<td>" + jsonSingleRow.id + "</td>";
        htmlSingleItem += "<td>" + jsonSingleRow.maker_user + "</td>";
        htmlSingleItem += "<td>" + jsonSingleRow.destination_user + "</td>";
        htmlSingleItem += "<td>" + jsonSingleRow.time + "</td>";
        htmlSingleItem += "<td>" + jsonSingleRow.value + "</td>";
        htmlSingleItem += "<td>" + jsonSingleRow.tools + "</td>";
        htmlSingleItem += "<td>" + jsonSingleRow.description + "</td>";
        htmlSingleItem += "</tr>";
        return htmlSingleItem;
    }

});