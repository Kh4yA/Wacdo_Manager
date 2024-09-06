<?php

namespace App\Utils;

// class _Model qui va contenir toutes les methods generiques

class _Model
{
    protected $table;
    protected $fields = [];
    protected $values = []; // tableau indexé cle valeur au format ["nom"=>"valeur","prenom"=>"valeur",....]
    protected $id = 0; // On defini l'id a 0 par defaut

    //definir le __constructor pour que quand l'id est passé en parametre il charge l'objet null par defaut
    public function __construct($id = null)
    {
        if ($id) {
            $this->load($id);
        }
    }

    //GETTER
    /**
     * role : reccuperer la valeur d'un atribut
     * @param string 
     * @return string (valeur du champ)
     */
    function get($fieldName)
    {
        if (isset($this->values[$fieldName])) {
            return $this->values[$fieldName];
        } else {
            return null;
        }
    }
    /**
     * recupere la valeur de l'id
     *  @return int (valeur de l'id)
     */
    function getId(): int
    {
        return $this->id;
    }
    //SETTER
    /**
     * role : valoriser un attribut
     * @param string|int $fieldName(valeur a valoriser)
     * @return true (true si accepter false sinon)
     */
    function set($fieldName, $value)
    {
        $this->values[$fieldName] = $value;
        return true;
    }
    /**
     * construit une liste de champs pour la requete
     * @return string retourne une chaine de caractere au format "`nom`, `prenom`, ..."
     */
    function listField(): string
    {
        $tab = array();
        foreach ($this->fields as $field) {
            $tab[] = "`$field`";
        }
        return implode(', ', $tab);
    }
    /**
     * loadFromTab charge un tableau de données a partir d'un autre tableau
     * @param array ($tab)
     */
    function loadFromTab($tab): bool
    {
        foreach ($this->fields as $fieldName) {
            if (isset($tab[$fieldName])) {
                $this->values[$fieldName] = htmlentities($tab[$fieldName]);
            }
        }
        return true;
    }
    /**
     * charge un objet de la classe courante
     * @param $id de l'objet a charger
     * @return  $this ou false
     */
    function load($id)
    {
        global $bdd;
        $sql = " SELECT `id`," . $this->listField() . " FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $id];
        $obj = $bdd->fetch($sql, $param);
        if ($obj) {
            $this->id = $obj["id"];
            foreach ($this->fields as $data) {
            $this->values[$data] = $obj[$data];
            }
            return $this;
        }
        return false;
    }
    /**
     * role : inserer des donnée en bdd
     * @param neant
     * @return int id de la derniere pizza inserer
     */
    function insert()
    {
        global $bdd;
        $bdd->insertBDD($this->table, $this->values);
        return $bdd->lastInsertId();
    }
    /**
     * role : modifer des donnée en bdd
     * @param neant
     * @return bool true si ok
     */
    function update(): bool
    {
        global $bdd;
        $bdd->updateBDD($this->table, $this->values, $this->id);
        return true;
    }
    /**
     * Rôle : extraire une liste d'objet avec des critères de tri et de filtrage en option
     * @param array $filtres permet de filtrer par nom de champ ['nomChamp' => valeur]
     * @param array $tris liste de tri ['+/-nomChamp']
     * @param string $tableJoin table de la jointure à utiliser
     * @param string $joinKey champ dans la table principale qui est utilisé pour faire la jointure avec l’ID de la table associée
     * @param string $fieldSelected champ spécifique à sélectionner dans la table de jointure
     * @param string $allias allias a donner au champs specifique pour le cibler
     * @return array tableau d'objet de la classe courante indexé par l'id
     */
    function listEtendue(array $filtres = [], array $tris = [], string $tableJoin = null, string $joinKey = null, string $fieldSelected = null,string $allias = null): array
    {
        // Construction de la requête de base
        $sql = "SELECT `$this->table`.`id`";

        // Ajout conditionnel du champ de la table de jointure
        if (!empty($tableJoin) && !empty($fieldSelected)) {
            $sql .= ", `$tableJoin`.`$fieldSelected` as $allias";
        }
        $sql .= ", " . $this->listfield() . " FROM `$this->table`";
        $param = [];
        $tabFiltre = [];
        // Gestion des filtres
        foreach ($filtres as $fieldName => $valeur) {
            $tabFiltre[] = "`$fieldName` = :$fieldName";
            $param[":$fieldName"] = $valeur;
        }
        // Ajout de la jointure si elle existe
        if (!empty($tableJoin) && !empty($joinKey)) {
            $sql .= " LEFT JOIN `$tableJoin` ON `$this->table`.`$joinKey` = `$tableJoin`.`id`";
        }
        // Ajout des conditions de filtre
        if (!empty($tabFiltre)) {
            $sql .= " WHERE " . implode(" AND ", $tabFiltre);
        }
        // Construction de la liste des critères de tri
        $tabOrder = [];
        foreach ($tris as $tri) {
            $car1 = substr($tri, 0, 1);
            if ($car1 === "-") {
                $ordre = "DESC";
                $nomField = substr($tri, 1);
            } elseif ($car1 === "+") {
                $ordre = "ASC";
                $nomField = substr($tri, 1);
            } else {
                $ordre = "ASC";
                $nomField = $tri;
            }
            $tabOrder[] = "`$nomField` $ordre";
        }
        if (!empty($tabOrder)) {
            $sql .= " ORDER BY " . implode(", ", $tabOrder);
        }

        // Affichage de la requête pour débogage
        // Exécution de la requête
        global $bdd;
        $req = $bdd->sqlExecute($sql, $param);
        $result = [];
        // Traitement des résultats
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $class = get_class($this);
            $obj = new $class();
            $obj->loadFromTab($data);
            $allias !== null ? $obj->set($allias, $data[$allias]) : "";
            $obj->id = $data["id"];
            $result[$obj->id] = $obj;
        }
        return $result;
    }
    /**
     * role : supprime un champ selectionner par l'id
     * @param neant
     * @return true
     */
    function delete(): bool
    {
        $sql = "DELETE FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $this->id];
        global $bdd;
        $bdd->sqlExecute($sql, $param);
        return true;
    }
}
