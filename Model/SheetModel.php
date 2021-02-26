<?php


class SheetModel
{
    protected $table;

    function __construct()
    {
        $this->table = "fiche";
    }
    
    function insert(Sheet $sheet)
    {
        $db = Connection::getInstance();
        $sql = "INSERT INTO `".$this->table."`(`label`,`description`) VALUES (\"".$sheet->getLabel()."\",\"".$sheet->getDescription()."\")";
        if ($db->query($sql) == TRUE) {
            $last_id = $db->lastInsertId();
            $subquery = "INSERT INTO `fiche_categorie`(`categorie_id`,`fiche_id`) VALUES ";
            $i = 1;
            foreach ($sheet->getCategories() as $category) {
                $subquery .= "('".$category."','".$last_id."')";
                if ($i == count($sheet->getCategories())) {
                    $subquery.=";";
                } else {
                    $subquery.=",";
                }
                $i++;
            }
            $db->query($subquery);
            return true;
        }
        else {
            return false;
        }
    }

    function getAll($categoryId=null)
    {
        $db = Connection::getInstance();
        if(is_null($categoryId)){
            $sql = "
                    SELECT f.id AS ficheID,f.label AS ficheLabel,f.description,c.id AS catID,c.label AS catLabel
                    FROM `fiche` AS f JOIN fiche_categorie AS fc ON f.id=fc.fiche_id 
                    JOIN categorie AS c ON fc.categorie_id=c.id 
                    ORDER BY f.id DESC";
        } else{
            $sql = "SELECT f.id AS ficheID,f.label AS ficheLabel,f.description,c.id AS catID,c.label AS catLabel
                FROM `fiche` AS f JOIN fiche_categorie AS fc ON f.id=fc.fiche_id 
                JOIN categorie as c ON c.id=fc.categorie_id WHERE c.id=$categoryId;";
        }
        $result = $db->query($sql);
        $sheets = [];
        while ($data = $result->fetch()) {
            $category = new Category();
            $category->setLabel($data['catLabel']);
            $category->setId($data['catID']);
            if (!array_key_exists($data['ficheID'],$sheets)) {
                $sheet = new Sheet();
                $sheet->setId($data['ficheID']);
                $sheet->setLabel($data['ficheLabel']);
                $sheet->setDescription($data['description']);
                $sheets[$data['ficheID']] = $sheet;
            }
            $sheets[$data['ficheID']]->addCategory($category);
        }
        return $sheets;
    }

    function delete($id) {
        $db = Connection::getInstance();
        $sql = "DELETE FROM `fiche_categorie` WHERE fiche_id = $id";
        $db->exec($sql);
        $subquery = "DELETE FROM `$this->table` WHERE id=$id";
        $db->exec($subquery);
        return true;
    }

    function update($id, $label, $description)
    {
        $db = Connection::getInstance();
        $sql = "UPDATE `$this->table` SET `label`='$label', `description`=\"$description\"  WHERE id=$id;";
        if ($db->query($sql) == TRUE) return true;
        else return false;
    }

    function removeCategory($ficheId, $categorieId)
    {
        $db = Connection::getInstance();
        $sql = "DELETE FROM `fiche_categorie` WHERE categorie_id=$categorieId AND fiche_id=$ficheId";
        $db->exec($sql);
        return true;
    }

    function addCategory($ficheId, $categorieId)
    {
        $db = Connection::getInstance();
        $sql = "
                INSERT INTO `fiche_categorie` (`categorie_id`, `fiche_id`) 
                SELECT '$categorieId', '$ficheId' FROM DUAL 
                WHERE NOT EXISTS (SELECT * FROM `fiche_categorie` 
                WHERE `categorie_id`='$categorieId' AND `fiche_id`='$ficheId' LIMIT 1);
        ";
        if ($db->query($sql) == TRUE) {
            return true;
        }
        return false;
    }

}