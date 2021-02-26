<html>

    <head>
        <title>Annuaire</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
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
            if (isset($error)) { ?>
                <p class="mt-3 alert alert-danger"><?php echo $error;?></p>
        <?php } ?>
        <?php
            if (isset($info)) { ?>
            <p class="mt-3 alert alert-success"><?php echo $info;?></p>
        <?php } ?>
        <div class="row pt-3">

            <div class="col-md-4">
                <div class="row"><h4>Section</h4></div>
                <div class="row">
                    <ul class="nav">
                        <li class="nav-item">
                            <a data-toggle="collapse" data-target="#section_form" class="nav-link" href="#">
                                <i class="fa fa-plus-square"></i> Ajouter une nouvelle section</a>
                        </li>
                    </ul>
                    <div id="section_form" class="collapse">
                        <form class="form-inline" action="?action=categories" method="POST">
                            <input type="hidden" name="taf_section" value="createsection">
                            <label for="label" class="px-2">Libellé:</label>
                            <input type="text" class="form-control form-control-sm mx-2" id="label"
                                   name="label" placeholder="Nom de la section...">

                            <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                        </form>

                    </div>
                </div>
                <div class="row">
                    <?php foreach ($sections as $section) {?>
                        <div class="col-sm-12 pt-3">
                            <h6><?php echo $section->getLabel(); ?>&nbsp;
                                <a href="#" data-toggle="modal" data-target="#myModalSection"
                                         onclick="hydraterModalSection(<?php echo $section->getId();?>,'<?php echo $section->getLabel();?>');">
                                    <i class="fa fa-pencil"></i></a>
                            </h6>
                        </div>

                    <?php }?>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row"><h4>Catégories</h4></div>
                <div class="row bg-light p-2">
                    <ul class="nav">
                        <li class="nav-item">
                            <a data-toggle="modal" data-target="#category_form" class="nav-link" href="#">
                                <i class="fa fa-plus-square"></i> Ajouter une nouvelle catégorie</a>
                        </li>

                    </ul>
                </div>

                <div class="row">
                    <table class="table">
                        <thead>
                        <th>Libellé</th>
                        <th>Section</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                        </thead>
                        <tbody>
                        <?php foreach ($categories as $category) {?>
                            <tr>
                                <td><?php echo $category->getLabel();?></td>
                                <td><?php echo $category->getSection()->getLabel();?></td>
                                <td>
                                    <a href="#" class="text-success" data-toggle="modal" data-target="#update_category_form"
                                       onclick="hydraterModalCategory(<?php echo $category->getId();?>,'<?php echo $category->getLabel();?>','<?php echo $category->getSection()->getId();?>');">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td><a onClick="javascript: return confirm('Voulez-vous vraiment supprimer cette catégorie?')"
                                href="?action=delete_category&id=<?php echo $category->getId();?>" class="text-danger">
                                        <i class="fa fa-trash"></i></a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <ul class="pagination">
                        <?php if ($page>0) {?>
                        <li class="page-item"><a class="page-link" href="?action=categories&page=<?php echo $page-1;?>">
                                <i class="fa fa-arrow-left"></i> Précédent </a></li>
                        <?php } ?>
                        
                        <?php if (($page+1)<ceil($total/5)) {?>
                        <li class="page-item"><a class="page-link" href="?action=categories&page=<?php echo $page+1;?>">
                                Suivant <i class="fa fa-arrow-right"></i>
                            </a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

    </main>

    <!-- The Modal -->
    <div class="modal" id="myModalSection">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <h4>Mise à jour section</h4>
                    <form method="post" action="?action=categories">
                        <input type="hidden" name="taf_section" value="updatesection">
                        <input type="hidden" id="modal_section_id" name="id">
                        <label for="label3">Libellé</label>
                        <input type="text" class="form-control" id="modal_section_label" name="label">
                        <button type="submit" class="mt-2 btn-sm btn btn-primary">Mettre à jour</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="category_form" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <h4>Ajout nouvelle catégorie</h4>
                    <form action="?action=categories" method="POST">
                        <input type="hidden" name="taf_category" value="create">
                        <label for="label2" class="px-2">Libellé :</label>
                        <input type="text" class="form-control form-control" id="label2" name="label"
                               placeholder="Libellé de la catégorie...">
                        <label for="create_category_section_label">Section :</label>
                        <select name="section" id="create_category_section_label" class="form-control">
                            <?php foreach ($sections as $section) { ?>
                                <option
                                    value="<?php echo $section->getId(); ?>"><?php echo $section->getLabel(); ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="mt-2 btn btn-primary btn-sm">Enregistrer</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="update_category_form" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <h4>Modification catégorie</h4>
                    <form action="?action=categories" method="POST">
                        <input type="hidden" id="update_category_id" name="id">
                        <input type="hidden" name="taf_category" value="update">
                        <label for="update_category_label" class="px-2">Libellé :</label>
                        <input type="text" class="form-control form-control" id="update_category_label" name="label" />
                        <label for="update_category_section_label">Section :</label>
                        <select name="section" id="update_category_section_label" class="form-control">
                            <?php foreach ($sections as $section) { ?>
                                <option
                                    value="<?php echo $section->getId(); ?>"><?php echo $section->getLabel(); ?></option>
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
        function hydraterModalSection(id,label) {
            $('#modal_section_id').val(id);
            $('#modal_section_label').val(label);
        }

        function hydraterModalCategory(id,label, section) {
            $('#update_category_id').val(id);
            $('#update_category_label').val(label);
            $('#update_category_section_label').val(section);
        }
    </script>
</body>
</html>

