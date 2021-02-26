<html>

    <head>
        <title>Annuaire</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <style>
            #description{
                height:100px;
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
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 col-sm-12 pt-4">
                <?php
                if (isset($error)) { ?>
                    <p class="mt-3 alert alert-danger"><?php echo $error;?></p>
                <?php } ?>
                <?php
                if (isset($info)) { ?>
                    <p class="mt-3 alert alert-success"><?php echo $info;?></p>
                <?php } ?>
                <h2 class="pb-2">Nouvelle fiche</h2>
                <form method="POST" action="?action=create">
                    <div class="form-group">
                        <label for="label">Libellé:</label>
                        <input type="text" name="label" class="form-control" id="label" placeholder="Entrer le libellé ici...">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" name="description" class="form-control" id="description" placeholder="Entrer la description ici...">
                    </div>
                    <div class="form-group">
                        <label for="categories">Description:</label>
                        <select name="categories[]" id="categories" class="form-control" multiple>
                            <?php foreach ($allCategories as $category) {?>
                            <option value="<?php echo $category->getId();?>"><?php echo $category->getLabel();?></option>
                            <?php }?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

    </main>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>

<?php
