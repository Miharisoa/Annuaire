<html>

<head>
    <title>Annuaire</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .listing {
            overflow-y:  auto;
            max-height:500px;
        }
        .listing::-webkit-scrollbar-track {
            border: 1px solid lightgray;
            border-radius: 2px;
            padding: 2px 0;
            background-color: lightgray;
        }

        .listing::-webkit-scrollbar {
            width: 10px;
        }

        .listing::-webkit-scrollbar-thumb {
            border-radius: 10px;
            box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: darkgray;
            border: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-info text-white">
    <h1><a href="/" class="text-white">Annuaire</a></h1>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link text-white" href="/">Accueil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="?action=categories">Catégorie</a>
        </li>

    </ul>
</nav>
<main class="container">
    <?php
    if (isset($info)) { ?>
        <p class="mt-3 alert alert-success"><?php echo $info;?></p>
    <?php } ?>
    <div class="row">
        <div class="col-md-8 pt-5">
            <a href="?action=create"><i class="fa fa-plus-square"></i> Ajouter une nouvelle fiche</a>
            <?php if (count($sheets) > 0) { ?>
                <div class="listing">
                    <ul class="mt-2 list-group">
                        <?php foreach ($sheets as $sheet) { ?>
                            <li class="list-group-item mt-3">
                                <h4><?php echo $sheet->getLabel(); ?></h4>
                                <ul class="nav small">
                                    <li class="nav-item">
                                        <a href="#" onclick="hydraterModalAddCategory(<?php echo $sheet->getId(); ?>)"
                                           data-toggle="modal" data-target="#addcategory_sheet_form">
                                            <i class="fa fa-plus-square"></i> Catégories</a>
                                    </li>
                                    <?php foreach ($sheet->getCategories() as $cat) { ?>
                                        <li class="nav-item text-grey">
                                            &nbsp; | <?php echo $cat->getLabel();
                                            if (count($sheet->getCategories()) > 1) {
                                                ?>
                                                <a href="?sheet_id=<?php echo $sheet->getId(); ?>&cat=<?php echo $cat->getId(); ?>"
                                                   class="text-danger">
                                                    <i class="fa fa-remove"></i></a>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <p>
                                    <?php echo $sheet->getDescription(); ?>
                                </p>
                                <span class="float-right">
                            <a data-toggle="modal" data-target="#update_sheet_form" href="#"
                               class="text-white btn btn-sm btn-primary"
                               onclick="hydraterModalSheet({id:<?php echo $sheet->getId(); ?>,label:'<?php echo $sheet->getLabel(); ?>',description:'<?php echo addslashes($sheet->getDescription()); ?>'});">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a onClick="javascript: return confirm('Voulez-vous vraiment supprimer cette fiche?');"
                               href="?id=<?php echo $sheet->getId(); ?>" class="btn btn-sm btn-danger"><i
                                        class="fa fa-trash"></i></a>
                        </span>
                            </li>
                        <?php } ?>
            </ul>
                </div>
                    <?php } else {?>
                <p class="alert alert-light"><h4>Aucun résultat</h4></p>
            <?php }?>
        </div>
        <div class="col-md-4 p-5">
            <h2><a href="?action=categories">Catégories</a></h2>
            <ul class="list-group list-group-flush mt-4">
                <li class="list-group-item"><a href="/annuaire">Afficher Tout</a></li>
                <?php foreach ($sections as $section) {?>
                    <li class="list-group-item">
                        <a href="#" data-toggle="collapse" data-target="#demo<?php echo $section->getId();?>">
                            <?php echo $section->getLabel();?>
                            <i class="fa fa-caret-down"></i></a>
                        <!--<span class="float-right"><a href="#"><i class="fa fa-pencil"></i></a></span>-->
                        <div id="demo<?php echo $section->getId();?>" class="collapse">
                            <ul class="list-group px-5">
                                <?php foreach ($section->getCategories() as $category) {?>
                                <li ><a href="?category=<?php echo $category->getId();?>" onclick="changeTitle('<?php echo $category->getLabel();?>');"><?php echo $category->getLabel();?></a></li>
                                <?php }?>
                            </ul>
                        </div>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>
</main>

<div id="update_sheet_form" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h4>Modification fiche</h4>
                <form action="?action=update" method="POST">
                    <input type="hidden" id="update_sheet_id" name="id">
                    <label for="update_sheet_label" class="px-2">Libellé :</label>
                    <input type="text" class="form-control form-control" id="update_sheet_label" name="label" />
                    <br />
                    <label for="update_sheet_description" class="px-2">Description :</label>
                    <textarea class="form-control" name="description" id="update_sheet_description" cols="30" rows="5"></textarea>

                    <button type="submit" class="mt-2 btn btn-primary btn-sm">Enregistrer</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div id="addcategory_sheet_form" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h4>Ajout catégorie fiche</h4>
                <form action="?action=add_sheet_category" method="POST">
                    <input type="hidden" id="sheet_id" name="id">
                    <label for="categories" class="px-2">Catégories :</label>
                    <select name="categories[]" id="categories" class="form-control" multiple>
                        <?php foreach ($sectionCategories as $cat) {?>
                        <option value="<?php echo $cat->getId()?>"><?php echo $cat->getLabel()?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="mt-2 btn btn-primary btn-sm">Enregistrer</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script>
    function hydraterModalSheet(objet) {
        $('#update_sheet_id').val(objet.id);
        $('#update_sheet_label').val(objet.label);
        $('#update_sheet_description').val(objet.description);
    }

    function hydraterModalAddCategory(id){
        $('#sheet_id').val(id);
    }

    function changeTitle(titre) {
        $('#listing_titre').text(titre);
    }
</script>
</body>
</html>

<?php
