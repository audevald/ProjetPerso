<?php

class Client implements ORM {
    
    public $id_client;
    public $nom;
    public $prenom;
    public $tel;
    
    public function __construct($id_client = null, $nom = null, $prenom = null, $tel = null) {
        $this->id_client = $id_client;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->tel = $tel;
    }
    
    function charger() {
        // Hydrate $this en se basant sur sa PK.
        if (!$this->id_client)
            return false;
        $req = "SELECT * FROM client WHERE id_client={$this->id_client}";
        return DBMySQL::getInstance()->xeq($req)->ins($this);
    }

    function sauver() {
        // Persister $this en se basant sur sa PK.
        $db = DBMySQL::getInstance();
        $id_client = $this->id_client ?: 'DEFAULT';
        $prenom = $this->prenom ?: NULL;
        $req = "INSERT INTO client VALUES({$id_client}, {$db->esc($this->nom)}, {$db->esc($prenom)}, {$db->esc($this->tel)}) ON DUPLICATE KEY UPDATE nom = {$db->esc($this->nom)}, prenom = {$db->esc($prenom)}, tel = {$db->esc($this->tel)}";
        $db->xeq($req);
        $this->id_client = $this->id_client ?: $db->pk();
    }
    
    function supprimer() {
        // Supprimer l'enregistrement correspondant à $this.
        if (!$this->id_client)
            return false;
        $req = "DELETE FROM client WHERE id_client = {$this->id_client}";
        return (bool) DBMySQL::getInstance()->xeq($req)->nb();
    }
    
    static function tab($where = 1, $orderBy = 1, $limit = null) {
        // Retourne un tableau d'enregistrements sous la forme d'instances.
        $req = "SELECT * FROM client WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return DBMySQL::getInstance()->xeq($req)->tab(self::class);
    }
}
