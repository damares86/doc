<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $allmenu_header ?></h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= $common_dashboard ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $allmenu_header ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<br>

<section class="section">
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class='wrapper'>
                        <div id="alert-placeholder"></div>

                        <!-- Blocco Parent -->
                        <div id='parent-block' class='container-pages p-3'>
                            <h4 dragula-ignore><?= $allmenu_inmenu ?></h4>
                            <?php
                            $pages_json = file_get_contents('inc/menu/menu.json');
                            $pages_data = json_decode($pages_json, true);

                            // Iteriamo sui parent
                            foreach ($pages_data['inmenu'] as $parent) {
                                $mc->table = 'mc_pages';
                                $mc->id = $parent['id'];

                                $stmt = $mc->showAllWhere('id', ['id']);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($row);
                                $page_name = str_replace('_', ' ', ucfirst($row['page_name']));
                            ?>
                                <div id="<?= $row['id'] ?>" class='container-pages parent_item px-5 rounded m-2' draggable="true">
                                    <b><?= $page_name ?></b>
                                    <!-- Freccia visibile per i parent -->
                                    <a class="btn icon btn-sm btn-info mx-2 shadow collapse-toggle" data-bs-toggle="collapse" href="#child_<?= $row['id'] ?>" role="button" aria-expanded="false" aria-controls="child_<?= $row['id'] ?>">
                                        <i class="bi bi-chevron-down"></i>
                                    </a>

                                    <!-- Blocco per i child, visibile solo per i parent -->
                                    <div id="child_<?= $row['id'] ?>" class='collapse container-pages child_block p-2 rounded m-2'>
                                        <?php
                                        if (isset($parent['child'])) {
                                            foreach ($parent['child'] as $childId) {
                                                $mc->table = 'mc_pages';
                                                $mc->id = $childId;
                                                $stmt1 = $mc->showAllWhere('id', ['id']);
                                                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                                                extract($row1);
                                        ?>
                                                <div id="<?= $row1['id'] ?>" class="child_item rounded m-2" draggable="true">
                                                    <b><?= $row1['page_name'] ?></b>
                                                    <!-- Freccia nascosta perché è un child -->
                                                    <a class="btn icon btn-sm btn-info mx-2 shadow collapse-toggle d-none" data-bs-toggle="collapse" href="#child_<?= $row1['id'] ?>" role="button" aria-expanded="false" aria-controls="child_<?= $row1['id'] ?>">
                                                        <i class="bi bi-chevron-down"></i>
                                                    </a>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <!-- Blocco NoMenu -->
                            <div id='nomenu-block' class='container-pages p-3 bg-warning'>
                                <h4 dragula-ignore><?= $allmenu_nomenu ?></h4>
                                <?php
                                foreach ($pages_data['nomenu'] as $nomenu) {
                                    $mc->table = 'mc_pages';
                                    $mc->id = $nomenu;
                                    $stmt = $mc->showAllWhere('id', ['id']);
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    extract($row);
                                    $page_name = str_replace('_', ' ', ucfirst($row['page_name']));
                                ?>
                                    <div id="<?= $row['id'] ?>" class='container-pages nomenu_item rounded m-2' draggable="true">
                                        <b><?= $page_name ?></b>
                                        <!-- Freccia nascosta perché è nel nomenu -->
                                        <a class="btn icon btn-sm btn-info mx-2 shadow collapse-toggle d-none" data-bs-toggle="collapse" href="#child_<?= $row['id'] ?>" role="button" aria-expanded="false" aria-controls="child_<?= $row['id'] ?>">
                                            <i class="bi bi-chevron-down"></i>
                                        </a>

                                        <!-- Blocca child nascosto per gli elementi in nomenu -->
                                        <div id="child_<?= $row['id'] ?>" class='collapse container-pages child_block p-2 rounded m-2 d-none'></div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <button id="save" class="btn btn-success m-3 w-25">Save</button> -->
            </div>
        </div>
    <div class="col-md-3 col-12">
        <div class="card shadow">
            <h4 class="card-title px-4 pt-3"><?= $common_info ?></h4>
            <div class="card-content px-5 pb-4">
                <ul>
                    <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&parent=1&page=8" target="_blank"><?= $common_see_guide ?></a></li>
                </ul>
            </div>
        </div>
    </div>
    
</section>

<script src='script/dragula.js'></script>

<script>
    var drake; // Definisci 'drake' a livello globale

    // Funzione per inizializzare o reinizializzare Dragula
    function initDragula() {
        if (drake) {
            drake.destroy(); // Distruggi l'istanza precedente, se esiste
        }

        // Inizializza Dragula con i contenitori esistenti
        drake = dragula([document.getElementById('parent-block'), document.getElementById('nomenu-block')], {
            moves: function(el, container, handle) {
                // Impedisci il drag sugli elementi <h4>
                return el.tagName.toLowerCase() !== 'h4';
            }
        });

        // Aggiungi tutti i blocchi child già esistenti
        document.querySelectorAll('.child_block').forEach(function(container) {
            drake.containers.push(container);
        });

        // Gestione del drop
        drake.on('drop', function(el, target, source, sibling) {
            // Evita che gli h4 vengano spostati
            if (el.tagName.toLowerCase() === 'h4') {
                console.error("Errore: Non è possibile spostare le intestazioni.");
                drake.cancel(true); // Annulla il drop per gli h4
                return;
            }

            // Verifica se si sta tentando di spostare un elemento sopra un <h4>
            if (sibling && sibling.tagName.toLowerCase() === 'h4') {
                console.error("Errore: Non è possibile inserire elementi sopra l'intestazione.");
                drake.cancel(true); // Annulla il drop
                return;
            }

            // Verifica se si sta tentando di spostare un elemento dentro uno dei suoi discendenti
            if (isAncestor(el, target)) {
                console.error("Errore: impossibile spostare un nodo dentro uno dei suoi discendenti.");
                return;
            }

            if ($(target).hasClass('child_block')) {
                // Se viene rilasciato in un child_block, è un child
                var parentId = $(target).closest('.parent_item').attr('id');
                $(el).removeClass('parent_item nomenu_item').addClass('child_item');
                updateCollapseIconAndChildBlock(el, false);
            } else if ($(target).attr('id') === 'parent-block') {
                // Se viene rilasciato nel parent-block, diventa un parent
                $(el).removeClass('child_item nomenu_item').addClass('parent_item');
                updateCollapseIconAndChildBlock(el, true);
            } else if ($(target).attr('id') === 'nomenu-block') {
                // Se viene rilasciato nel nomenu-block, diventa un elemento di nomenu
                $(el).removeClass('parent_item child_item').addClass('nomenu_item');
                updateCollapseIconAndChildBlock(el, false);
            }

            // Aggiungi l'elemento al contenitore di destinazione
            $(el).detach().appendTo(target);
            if (sibling) {
                $(el).insertBefore(sibling); // Se c'è un sibling valido, inserisci prima di esso
            }

            // Aggiorna il JSON dopo il drop
            updateJSON();
        });

        drake.on('remove', function(el, container) {
            console.log("Element removed:", el.id);
            updateJSON(); // Aggiorna JSON quando un elemento viene rimosso
        });
    }

    // Funzione per aggiornare la visibilità dell'icona di espansione e del blocco child
    function updateCollapseIconAndChildBlock(el, show) {
        const childBlockId = 'child_' + el.id;
        let childBlock = $(el).find('.child_block');

        if (show) {
            $(el).find('.collapse-toggle').removeClass('d-none');

            if (childBlock.length === 0) {
                // Crea dinamicamente il child_block se non esiste già
                $(el).append(`
            <div id="${childBlockId}" class="collapse container-pages child_block p-2 rounded m-2"></div>
        `);
                childBlock = $(el).find('.child_block');
            }

            // Aggiungi il nuovo child_block ai contenitori Dragula
            drake.containers.push(childBlock[0]);

            // Aggiorna l'attributo 'data-bs-target' della freccia
            $(el).find('.collapse-toggle').attr('data-bs-target', `#${childBlockId}`).attr('aria-controls', childBlockId);

            // Mostra il child_block (se non già visibile)
            if (!childBlock.hasClass('show')) {
                childBlock.collapse('show');
            }
        } else {
            // Nascondi la freccia e il child_block se non deve essere visibile
            $(el).find('.collapse-toggle').addClass('d-none');
            childBlock.collapse('hide');
        }
    }

    // Funzione per verificare se il target è un discendente dell'elemento spostato
    function isAncestor(el, target) {
        return el.contains(target);
    }

    // Chiamata per inizializzare Dragula
    initDragula();

    function getInMenuItems() {
        let items = [];

        // Itera su tutti i parent nel blocco parent
        $('#parent-block').children('.parent_item').each(function() {
            let parentId = this.id; // Ottieni l'ID del parent
            let children = [];

            // Cerca i child dentro il blocco collapse relativo al parent
            $(this).find('.child_block .child_item').each(function() {
                children.push(this.id); // Aggiungi l'ID del child
            });

            // Se ci sono child, includili nel JSON come array
            if (children.length > 0) {
                items.push({
                    id: parentId,
                    child: children
                });
            } else {
                // Se non ci sono child, aggiungi solo l'ID del parent
                items.push({
                    id: parentId
                });
            }
        });

        return items;
    }

    function getNoMenuItems() {
        let noMenuItems = [];

        // Itera su tutti gli elementi nel blocco nomenu
        $('#nomenu-block').children('.nomenu_item').each(function() {
            noMenuItems.push(this.id); // Aggiungi l'ID a nomenu
        });

        return noMenuItems;
    }

    // Funzione per aggiornare il JSON
    function updateJSON() {
        let inMenuItems = getInMenuItems();
        let noMenuItems = getNoMenuItems();

        // Costruisci l'oggetto per il JSON
        let jsonData = {
            inmenu: inMenuItems,
            nomenu: noMenuItems
        };

        // AJAX per salvare i dati
        $.ajax({
            url: 'core/mngMenu.php',
            method: 'POST',
            data: {
                menuData: JSON.stringify(jsonData) // Invia il JSON come stringa
            },
            success: function(response) {
                console.log("Risposta ricevuta:", response); // Log risposta
                if (response.success) {
                    showAlert(response.message, 'success');
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.error('Errore AJAX:', error); // Log errori AJAX
                showAlert('Si è verificato un errore durante la richiesta AJAX.', 'danger');
            }
        });
    }

    // Funzione per il salvataggio
    $("#save").click(function() {
        updateJSON(); // Aggiorna JSON quando si clicca su Salva
    });

    // Funzione per mostrare un alert di successo o errore
    function showAlert(message, type) {
        $('#alert-placeholder').html(
            '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>' +
            '</div>'
        );
    }
</script>