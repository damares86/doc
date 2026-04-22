function initializeSummernote() {
    $(".summernote").summernote({
        tabsize: 2,
        height: 400,
        lang:"it-IT",
        toolbar: [
        ['misc',['undo','redo']],
        ['style', ['style']],
        ['font', ['bold','italic','underline', 'clear']],
        ['fontname', ['fontname','fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
        ],
    })
    $("#hint").summernote({
        height: 100,
        toolbar: false,
        placeholder: "type with apple, orange, watermelon and lemon",
        hint: {
        words: ["apple", "orange", "watermelon", "lemon"],
        match: /\b(\w{1,})$/,
        search: function (keyword, callback) {
            callback(
            $.grep(this.words, function (item) {
                return item.indexOf(keyword) === 0
            })
            )
        },
        },
    })
}

function initializeTiny(){
     const themeOptions = document.body.classList.contains("theme-dark")
    ? {
        skin: "oxide-dark",
        content_css: "dark",
      }
    : {
        skin: "oxide",
        content_css: "default",
      }

    tinymce.init({
        selector: ".tiny",
        toolbar:
          "undo redo styleselect bold italic alignleft aligncenter alignright bullist numlist outdent indent link code",
        plugins: "code lists link",
        ...themeOptions,
        height: 400, // Imposta l'altezza dell'editor
      })
}

$(document).ready(function(){    
    //var i=1;
    $('#add').click(function(){
        i++;

        var colorOptionsBg = '';
        var colorOptionsText = '';

        // Usa la variabile `colors` passata dalla pagina PHP
        colors.forEach(function(row) {
            colorOptionsBg +=
                '<input type="radio" class="btn-check" name="bg_color_' + i + '" value="' + row.color + '" autocomplete="off" id="bg_' + row.color + '_' + i + '" hidden>' +
                '<label class="color-label shadow my-1" for="bg_' + row.color + '_' + i + '" style="background-color: ' + row.color + ';">' +
                '<span class="checkmark">✔</span>' +
                '&nbsp;' +
                '</label>';

            colorOptionsText +=
                '<input type="radio" class="btn-check" name="text_color_' + i + '" value="' + row.color + '" autocomplete="off" id="text_' + row.color + '_' + i + '" hidden>' +
                '<label class="color-label shadow my-1" for="text_' + row.color + '_' + i + '" style="background-color: ' + row.color + ';">' +
                '<span class="checkmark">✔</span>' +
                '&nbsp;' +
                '</label>';
        });

    
        var postOptions = '';
        if(postExist==true){
            postOptions = '<option value="post_' + i + '">' + block_type_post + '</option>';
        }

        var postBlock = '' ;
        if(postExist==true){
            postBlock =  '<div class="row page post_'+i+'">'+
            '<div class="col-12">'+
                '<p>'+block_post_text+'</p>'+
            '</div>'+
            '<div class="col-md-3 pb-3">'+
                '<label>'+block_post_cat+'</label>'+
            '</div>'+
            '<div class="col-md-9 pb-3">'+
                '<div class="form-group has-icon-left">'+
                    '<div class="position-relative">'+
                        '<fieldset class="form-group">'+
                            '<select class="form-select w-50" id="theme" name="post_cat_'+i+'">'+
                                catOptions+
                            '</select>'+
                        '</fieldset>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<input type="hidden" name="post_'+i+'" value="p">'+
        '</div>';
        }

        $('#dynamic_field').append('<div class="row" id="block_'+i+'">'+
            '<div class="col-md-3 mt-3 p-3">'+
                '<label><b>'+block_title+' <span>'+i+'</span></b></label>'+
            '</div>'+
            '<div class="col-md-5 mt-3 p-3">'+
                '<div class="form-group">'+
                    '<div class="form-check mandatory">'+
                        '<div class="position-relative">'+
                            '<fieldset class="form-group">'+
                                '<select class="form-select" id="block_'+i+'_type" name="block_'+i+'_type">'+
                                    '<option value="text_'+i+'">'+block_type_text+'</option>'+
                                    '<option value="img_'+i+'">'+block_type_image+'</option>'+
                                    '<option value="info_'+i+'">'+block_type_info+'</option>'+
                                    '<option value="gallery_'+i+'">'+block_type_gallery+'</option>'+
                                    '<option value="quote_'+i+'">'+block_type_quotes+'</option>'+
                                    '<option value="script_'+i+'">'+block_type_scripts+'</option>'+
                                    postOptions+
                                '</select>'+
                            '</fieldset>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="col-md-4 mt-3 p-3">'+
                '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>'+
            '</div>'+
            '<div class="col-md-3 p-3">'+
                '<label>'+block_bootstrap+'</label>'+
            '</div>'+
            '<div class="col-md-9 mt-3  px-5">'+
                '<input type="text" class="form-control" placeholder="" name="bootstrap_'+i+'" />'+
            '</div>'+
            '<div class="col-12 mt-3 mb-3 px-5 pb-3 border-bottom">'+
                '<div class="row page text_'+i+'">'+
                    '<textarea class="tiny" name="text_content_'+i+'"></textarea>'+
                '</div>'+
                '<div class="row page img_'+i+'">'+
                    '<label>'+block_image_upload+'</label>'+
                    '<div class="form-group">'+
                        '<div class="form-check">'+
                            '<div class="position-relative">'+
                                '<input class="form-control" type="file" name="img_'+i+'" />'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<span>'+block_image_default+': <img src="../uploads/img/visual.jpg" class="d-inline w-25"></span>'+
                '</div>'+
                '<div class="row page info_'+i+'">'+
                    '<label>'+block_image_upload+' <span class="text-danger">*</span></label>'+
                    '<div class="form-group">'+
                        '<div class="form-check mandatory">'+
                            '<div class="position-relative">'+
                                '<input class="form-control" type="file" name="info_img_'+i+'" />'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<span>'+block_image_default+': <img src="../uploads/img/visual.jpg" class="d-inline m-3 w-25"></span>'+
                    '<textarea class="tiny" class="mt-5" name="info_content_'+i+'"></textarea>'+
                '</div>'+
                '<div class="row page gallery_'+i+'">'+
                    '<div class="col-7">'+
                        '<label class="mb-3">'+block_gallery_choose+' <span class="text-danger">*</span></label>'+
                        '<div class="form-group">'+
                            '<div class="form-check mandatory">'+
                                '<div class="position-relative">'+
                                    '<fieldset class="form-group">'+
                                        '<select class="form-select" name="gallery_name_'+i+'">'+
                                            galleryOptions+
                                        '</select>'+
                                    '</fieldset>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-5">&nbsp;</div>'+
                '</div>'+
                '<div class="row page quote_'+i+'">'+
                    '<p>'+block_quotes_text+'</p>'+
                    '<input type="hidden" name="quote_'+i+'" value="q">'+
                '</div>'+
                '<div class="row page script_'+i+'">'+
                    '<div class="col-12">'+
                        '<b>'+block_script_title+'</b><br>'+
                        block_script_warn+'<br>'+block_script_desc+
                    '</div>'+
                    '<div class="col-md-3 pb-3 mt-3">'+
                        '<label>'+block_script_file+'</label>'+
                    '</div>'+
                    '<div class="col-md-9 pb-3">'+
                        '<div class="form-group">'+
                            '<div class="position-relative">'+
                                '<input type="text" class="form-control" placeholder="" name="script_'+i+'" />'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                postBlock+
            '</div>'+
            '<div class="row colors mb-5">'+
                '<div class="col-md-3 mt-3 px-3">'+
                    '<label>'+block_bg_color+'</label>'+
                '</div>'+
                '<div class="col-md-9 mt-3 px-3">'+
                    '<div class="form-group">'+
                        '<div class="form-check mandatory">'+
                            '<div class="position-relative">'+
                                '<div class="form-group">'+
                                    '<input type="radio" class="btn-check" name="bg_color_'+i+'" value="none" autocomplete="off" id="bg_none_'+i+'" hidden checked>'+
                                    '<label class="color-label bg shadow my-1" for="bg_none_'+i+'" style="background-color: #e5e5e5;"> '+
                                        block_color_none+
                                        '<span class="checkmark"></span>'+
                                    '</label>'+
                                    colorOptionsBg+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-3 mt-3 px-3">'+
                    '<label>'+block_bg_text+'</label>'+
                '</div>'+
                '<div class="col-md-9 mt-3 px-3">'+
                    '<div class="form-group">'+
                        '<div class="form-check mandatory">'+
                            '<div class="position-relative">'+
                                '<div class="form-group">'+
                                    '<input type="radio" class="btn-check" name="text_color_'+i+'" value="none" autocomplete="off" id="text_none_'+i+'" hidden checked>'+
                                    '<label class="color-label text shadow my-1" for="text_none_'+i+'" style="background-color: #e5e5e5;">'+
                                        block_color_none+
                                        '<span class="checkmark"></span>'+
                                    '</label>'+
                                    colorOptionsText+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'); 
        

            $('#block_' + i + '_type').val('text_' + i);

            $('#block_' + i + '_type').on('change', function() {
                const selectedValue = $(this).val();
                const blockId = $(this).attr('id').replace('_type', '');
            
                // Nascondi tutte le righe relative al blocco corrente
                $('#' + blockId).find('.row.page').hide();
                $('#' + blockId).find('.row.page input').removeAttr('data-parsley-required'); 
            
                // Mostra la riga corrispondente al valore selezionato
                $('#' + blockId).find('.' + selectedValue).show();
                $('#' + blockId).find('.' + selectedValue + ' input, .summernote').attr('data-parsley-required', 'true');
            });
            
            $('#block_' + i + '_type').trigger('change');
    
            $('#counter').val(i);

            // Inizializza Summernote sulla nuova textarea aggiunta
            // initializeSummernote();
            initializeTiny();
    });
    
    $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr("id"); 
        // tinymce.get('text_content_'+button_id).remove();  // Assicurati di rimuovere TinyMCE
        $('#block_'+button_id+'').remove();
        // Aggiorna il valore dell'input nascosto counter
        var currentCounter = parseInt($('#counter').val(), 10); // Ottieni il valore corrente
        currentCounter--; // Decrementa il contatore
        $('#counter').val(currentCounter); // Aggiorna il valore nell'input hidden

        // Rinomina tutti i blocchi successivi    
        updateBlockNumbers();
    });

    
    function updateBlockNumbers() {
        // Prendi tutti i blocchi esistenti
        $('#dynamic_field .row[id^="block_"]').each(function(index) {
            var newIndex = index + 1; // Calcola il nuovo indice (1-based)
            var oldIndex = $(this).attr('id').split('_')[1]; // Ottieni l'indice attuale
    
            // Aggiorna l'ID del blocco
            $(this).attr('id', 'block_' + newIndex);
            
            // Aggiorna l'etichetta del blocco
            $(this).find('label b span').text(newIndex);
    
            // Aggiorna il select box (tipo di blocco) mantenendo i valori con il suffisso appropriato
            var selectBox = $(this).find('select');
            var currentValue = selectBox.val();  // Valore attuale del select (es: text_1, img_1)
    
            // Aggiorna l'ID e il name del select
            selectBox.attr('id', 'block_' + newIndex + '_type');
            selectBox.attr('name', 'block_' + newIndex + '_type');
    
            // Aggiorna i valori delle opzioni, mantenendo il suffisso corretto
            selectBox.find('option').each(function() {
                var optionValue = $(this).val();
                // Sostituisci il vecchio indice con il nuovo
                var newValue = optionValue.replace('_' + oldIndex, '_' + newIndex);
    
                // Se l'opzione è "post", cambia anche il valore specifico per post
                if (optionValue.startsWith("post_")) {
                    newValue = "post_" + newIndex;
                }
    
                $(this).val(newValue);
            });
    
            // Reimposta il valore corrente del select in base al nuovo indice
            var newCurrentValue = currentValue.replace('_' + oldIndex, '_' + newIndex);
            selectBox.val(newCurrentValue);
    
            // Aggiorna i nomi e gli ID di tutti i campi che includono il vecchio indice
            $(this).find('textarea, input[type="file"], input[type="radio"], input[type="hidden"], input[type="text"]').each(function() {
                var name = $(this).attr('name');
                var id = $(this).attr('id');
                
                if (name) {
                    // Sostituisci il vecchio indice con il nuovo
                    name = name.replace(oldIndex, newIndex);
                    $(this).attr('name', name);
                }
    
                if (id) {
                    // Sostituisci il vecchio indice con il nuovo
                    id = id.replace(oldIndex, newIndex);
                    $(this).attr('id', id);
                }
            });
    
            // Aggiorna i label associati ai campi (per esempio per i radio button)
            $(this).find('label').each(function() {
                var labelFor = $(this).attr('for');
                if (labelFor) {
                    labelFor = labelFor.replace(oldIndex, newIndex);
                    $(this).attr('for', labelFor);
                }
            });
    
            // Aggiorna campi specifici come old_img_X, old_img_info_X, quote_X, post_X
            $(this).find('input[name*="old_img_"], input[name*="old_img_info_"], input[name*="quote_"], input[name*="post_"]').each(function() {
                var name = $(this).attr('name');
                var id = $(this).attr('id');
                
                if (name) {
                    name = name.replace(oldIndex, newIndex); // Aggiorna il nome con il nuovo indice
                    $(this).attr('name', name);
                }
    
                if (id) {
                    id = id.replace(oldIndex, newIndex); // Aggiorna l'ID con il nuovo indice
                    $(this).attr('id', id);
                }
            });
        });
    }
    
  });