<?php

class Reservation implements ORM {

    public $id_reservation;
    public $id_client;
    public $nb_personne;
    public $date_resa;
    public $heure;
    public $info;
    public $table_salle;


            
    public function __construct($id_reservation = null, $id_client = null, $nb_personne = null, $date_resa = null, $heure = null, $info = null, $table_salle = null) {
        $this->id_reservation = $id_reservation;
        $this->id_client = $id_client;
        $this->nb_personne = $nb_personne;
        $this->date_resa = $date_resa;  
        $this->heure = $heure;
        $this->info = $info;
        $this->table_salle = $table_salle;
    }
    
    function charger() {
        // Hydrate $this en se basant sur sa PK.
        if (!$this->id_reservation)
            return false;
        $req = "SELECT * FROM reservation WHERE id_reservation={$this->id_reservation}";
        return DBMySQL::getInstance()->xeq($req)->ins($this);
    }
    function sauver() {
        // Persister $this en se basant sur sa PK.
        $db = DBMySQL::getInstance();
        $id_reservation = $this->id_reservation ?: 'DEFAULT';
        $info = $this->info ?: NULL;
        $table_salle = $this->table_salle ?: 'NULL';
        $req = "INSERT INTO reservation VALUES ({$id_reservation}, {$this->id_client}, {$this->nb_personne}, {$db->esc($this->date_resa)}, {$db->esc($this->heure)}, {$db->esc($info)}, {$table_salle}) ON DUPLICATE KEY UPDATE id_client = {$this->id_client}, nb_personne = {$this->nb_personne}, date_resa = {$db->esc($this->date_resa)}, heure = {$db->esc($this->heure)}, info = {$db->esc($info)}, table_salle = {$table_salle}";
        $db->xeq($req);
        $this->id_reservation = $this->id_reservation ?: $db->pk();
    }

    function supprimer() {
        // Supprimer l'enregistrement correspondant à $this.
        if (!$this->id_reservation)
            return false;
        $req = "DELETE FROM reservation WHERE id_reservation = {$this->id_reservation}";
        return (bool) DBMySQL::getInstance()->xeq($req)->nb();
    }

    static function tab($where = 1, $orderBy = 1, $limit = null) {
        // Retourne un tableau d'enregistrements sous la forme d'instances.
        $req = "SELECT * FROM reservation WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return DBMySQL::getInstance()->xeq($req)->tab(self::class);
    }
    
    

}
