<?php

require ('Connection.php');
require 'Entity/Section.php';
require 'Entity/Category.php';
require 'Entity/Sheet.php';
require 'Model/SectionModel.php';
require 'Model/CategoryModel.php';
require 'Model/SheetModel.php';

function homeAction($message = null)
{
    if (!is_null($message)) {
        $info = $message;
    }
    $sectionModel = new SectionModel();
    $sections = $sectionModel->getSections();
    $categoryModel = new CategoryModel();
    $sectionCategories = $categoryModel->getCategories();
    $sheetModel = new SheetModel();
    $categoryId = null;
    if(isset($_GET['category'])) {
        $categoryId = $_GET['category'];
    }
    $sheets = $sheetModel->getAll($categoryId);
    require('homeView.php');
}

function createCardAction()
{
    $categoryModel = new CategoryModel();
    $categories = $categoryModel->getCategories();
    $allCategories = $categoryModel->getAll();
    $sheetModel = new SheetModel();
    if (isset($_POST['label']) && isset($_POST['description']) && isset($_POST['categories'])) {
        $label = $_POST['label'];
        $description = $_POST['description'];
        $cardCategories = $_POST['categories'];
        $sheet = new Sheet();
        $sheet->setLabel($label);
        $sheet->setDescription($description);
        $sheet->setCategories($cardCategories);
        $rep = $sheetModel->insert($sheet);
        if ($rep == true) {
            $info = "Fiche sauvegardé avec succès";
        } else {
            $error = "Il y a une erreur lors de l'enregistrement";
        }
    }
    require('createCardView.php');
}


function manageSectionAction()
{
    $page = 0;
    $sectionModel = new SectionModel();
    if (isset($_POST['taf_section'])) {
        $action = $_POST['taf_section'];
        if ($action == "createsection" && isset($_POST['label'])) {
            $label = $_POST['label'];
            if (strlen($label) < 1) $error = "Le nom d'une section est obligatoire";
            else {
                $nouvelleSection = new Section();
                $nouvelleSection->setLabel($label);
                $sectionModel->insert($nouvelleSection);
                $info = "Nouvelle section enregistrée";
            }
        } elseif ($action == "updatesection" && isset($_POST['id']) && isset($_POST['label'])) {
            $id = $_POST['id'];
            $label = $_POST['label'];
            $sectionModel->update($id, $label);
            $info = "Section mise à jour";
        }
    }

    $sections = $sectionModel->getSections();
    $categoryModel = new CategoryModel();
    $categories = $categoryModel->getCategories();
    $total = $categoryModel->getTotal();
    //$sectionModel->update(3,"Sports");
    require ('manageCategoryView.php');
}

function manageCategoryAction($message = null)
{
    $page = 0;
    if (!is_null($message)) {
        $info = $message;
    }
    $categoryModel = new CategoryModel();

    if(isset($_POST['taf_category'])) {
        $taf = $_POST['taf_category'];
        if ($taf == "create" && isset($_POST['label']) && isset($_POST['section'])) {
            $label = $_POST['label'];
            $section_id = $_POST['section'];
            $category = new Category();
            $category->setLabel($label);
            $category->setSection($section_id);
            $categoryModel->insert($category);
            $info = "Catégorie enregistrée avec succès";
        } elseif ($taf == "update" && isset($_POST['id']) && isset($_POST['label']) && isset($_POST['section'])) {
            $id = $_POST['id'];
            $label = $_POST['label'];
            $section_id = $_POST['section'];
            $categoryModel->update($id, $label, $section_id);
            $info = "Catégorie mise à jour avec succès";
        }
    }
    $sectionModel = new SectionModel();
    $sections = $sectionModel->getSections();
    $categoryModel = new CategoryModel();
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    $total = $categoryModel->getTotal();
    $categories = $categoryModel->getCategories($page);
    //$sectionModel->update(3,"Sports");
    require ('manageCategoryView.php');

}

function removeCategoryAction()
{
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $categoryModel = new CategoryModel();
        $response = $categoryModel->delete($id);

        if ($response) {
            $info = "Catégorie supprimée";
        } else {
            $error = "Il y a une erreur lors de la suppression";
        }

    } else{
        $error = "Identifiant introuvable";
    }

}

function removeSheetAction()
{
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sheetMod = new SheetModel();
        $response = $sheetMod->delete($id);
        if ($response) {
            return true;
        }
    }
    return false;
}

function updateSheetAction()
{
    if (isset($_POST['id']) && isset($_POST['label']) && isset($_POST['description'])) {
        $id = $_POST['id'];
        $label = $_POST['label'];
        $description = $_POST['description'];
        $sheetM = new SheetModel();
        $sheetM->update($id,$label,$description);
        return true;
    }
    return false;

}

function removeSheetCategoryAction()
{
    $catId = $_GET['cat'];
    $sheetId = $_GET['sheet_id'];
    $sheetMod = new SheetModel();
    $sheetMod->removeCategory($sheetId,$catId);
    return true;
}

function addSheetCategoryAction()
{
    if (isset($_POST['id']) && isset($_POST['categories'])) {
        $categories = $_POST['categories'];
        $sheet = $_POST['id'];
        $sheetMod = new SheetModel();
        foreach ($categories as $catId){
            $sheetMod->addCategory($sheet, $catId);
        }
    }
    else echo ' tsy tafiditra';
}
