$(document).ready(function(){
    $("form").parsley();

    $("[name=contraente]").on('change', function () {
    // Which option was welected?
    var val = $(this).val();
    console.log(val)
    
    switch (val) {
        case 'exists_contr':
        $("[name=\"id_contraente[]\"]").attr("data-parsley-required", "true");
        $("[name=ragione_sociale_contraente]").removeAttr("data-parsley-required");
        $("[name=nome_contraente]").removeAttr("data-parsley-required");
        $("[name=cognome_contraente]").removeAttr("data-parsley-required");
        $("[name=via_contraente]").removeAttr("data-parsley-required");
        $("[name=citta_contraente]").removeAttr("data-parsley-required");
        $("[name=cap_contraente]").removeAttr("data-parsley-required");
        $("[name=codice_fiscale_contraente]").removeAttr("data-parsley-required");
        $("[name=p_iva_contraente]").removeAttr("data-parsley-required");
        $("[name=telefono_contraente]").removeAttr("data-parsley-required");
        $("[name=cellulare_contraente]").removeAttr("data-parsley-required");
        $("[name=email_contraente]").removeAttr("data-parsley-required");
        break;
        case 'new_contr':
        $("[name=\"id_contraente[]\"").removeAttr("data-parsley-required");
        $("[name=ragione_sociale_contraente]").attr("data-parsley-required","true");
        $("[name=nome_contraente]").attr("data-parsley-required","true");
        $("[name=cognome_contraente]").attr("data-parsley-required","true");
        $("[name=via_contraente]").attr("data-parsley-required","true");
        $("[name=citta_contraente]").attr("data-parsley-required","true");
        $("[name=cap_contraente]").attr("data-parsley-required","true");
        $("[name=codice_fiscale_contraente]").attr("data-parsley-required","true");
        $("[name=p_iva_contraente]").attr("data-parsley-required","true");
        $("[name=telefono_contraente]").attr("data-parsley-required","true");
        $("[name=cellulare_contraente]").attr("data-parsley-required","true");
        $("[name=email_contraente]").attr("data-parsley-required","true");
        break;
    }
    
    $("form").parsley().reset();
    });

    $("[name=beneficiario]").on('change', function () {
        // Which option was welected?
        var val = $(this).val();
        console.log(val)
        switch (val) {
            case 'exists_benef':
            $("[name=\"id_beneficiario[]\"]").attr("data-parsley-required", "true");
            $("[name=ragione_sociale_beneficiario]").removeAttr("data-parsley-required");
            $("[name=via_beneficiario]").removeAttr("data-parsley-required");
            $("[name=citta_beneficiario]").removeAttr("data-parsley-required");
            $("[name=cap_beneficiario]").removeAttr("data-parsley-required");
            $("[name=codice_fiscale_beneficiario]").removeAttr("data-parsley-required");
            $("[name=p_iva_beneficiario]").removeAttr("data-parsley-required");
            break;
            case 'new_benef':
            $("[name=\"id_beneficiario[]\"]").removeAttr("data-parsley-required");
            $("[name=ragione_sociale_beneficiario]").attr("data-parsley-required","true");
            $("[name=via_beneficiario]").attr("data-parsley-required","true");
            $("[name=citta_beneficiario]").attr("data-parsley-required","true");
            $("[name=cap_beneficiario]").attr("data-parsley-required","true");
            $("[name=codice_fiscale_beneficiario]").attr("data-parsley-required","true");
            $("[name=p_iva_beneficiario]").attr("data-parsley-required","true");
            break;
        }
        
        $("form").parsley().reset();
        });
});