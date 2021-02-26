<?php

require('controller.php');

if (isset($_GET['action']) && $_GET['action'] != 'update') {
    if ($_GET['action'] == 'create') {
        createCardAction();
    } elseif ($_GET['action'] == 'categories') {
        if (isset($_POST['taf_section'])){
            manageSectionAction();
        }
        else {
            $message = (isset($_GET['remove']) == true)?"Catégorie supprimée avec succès":null;
            manageCategoryAction($message);
        }
    } elseif ($_GET['action'] == 'delete_category') {
        removeCategoryAction();
        header("location:?action=categories&remove=true");
    }  elseif ($_GET['action'] == 'add_sheet_category') {
        addSheetCategoryAction();
        header("location:/");
    } else {
        echo 'other action';
    }
}
else {
    $message = null;
    if (isset($_GET['action']) && $_GET['action'] == 'update') {
        $message = (updateSheetAction())?"Fiche mise à jour":null;
    } elseif (isset($_GET['id'])) {
        $message = (removeSheetAction())?"Fiche supprimée":null;
    } elseif (isset($_GET['sheet_id']) && isset($_GET['cat'])) {
        $message = (removeSheetCategoryAction())?"Catégorie d'une fiche enlevée":null;
    }
    homeAction($message);
}