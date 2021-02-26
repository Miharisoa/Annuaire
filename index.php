<?php

require('controller.php');

$controller = new Controller();

if (isset($_GET['action']) && $_GET['action'] != 'update') {
    if ($_GET['action'] == 'create') {
        $controller->createCardAction();
    } elseif ($_GET['action'] == 'categories') {
        if (isset($_POST['taf_section'])){
            $controller->manageSectionAction();
        }
        else {
            $message = (isset($_GET['remove']) == true)?"Catégorie supprimée avec succès":null;
            $controller->manageCategoryAction($message);
        }
    } elseif ($_GET['action'] == 'delete_category') {
        $controller->removeCategoryAction();
        header("location:?action=categories&remove=true");
    }  elseif ($_GET['action'] == 'add_sheet_category') {
        $controller->addSheetCategoryAction();
        header("location:/annuaire");
    } else {
        echo 'other action';
    }
}
else {
    $message = null;
    if (isset($_GET['action']) && $_GET['action'] == 'update') {
        $message = ($controller->updateSheetAction())?"Fiche mise à jour":null;
    } elseif (isset($_GET['id'])) {
        $message = ($controller->removeSheetAction())?"Fiche supprimée":null;
    } elseif (isset($_GET['sheet_id']) && isset($_GET['cat'])) {
        $message = ($controller->removeSheetCategoryAction())?"Catégorie d'une fiche enlevée":null;
    }
    $controller->homeAction($message);
}