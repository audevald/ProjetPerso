<?php

class User extends AbstractUser {
    
    public function __construct($id_user = null, $log = null, $mdp = null) {
        $this->id_user = $id_user;
        $this->log = $log;
        $this->mdp = $mdp;
    }
    
    public function sauver() {
        // Persister $this en se basant sur sa PK.
        $db = DBMySQL::getInstance();
        $id_user = $this->id_user ?: 'DEFAULT';
        $req = "INSERT INTO user VALUES ({$id_user}, {$db->esc($this->log)}, {$db->esc($this->mdp)}, {$db->esc($this->nom)}, {$db->esc($this->prenom)}) ON DUPLICATE KEY UPDATE log = {$db->esc($this->log)}, mdp = {$db->esc($this->mdp)}, nom = {$db->esc($this->nom)}, prenom = {$db->esc($this->prenom)}";
        $db->xeq($req);
        $this->id_user = $this->id_user ?: $db->pk();
    }
    
    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        // Retourne un tableau d'enregistrements sous la forme d'instances.
        $req = "SELECT * FROM user WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return DBMySQL::getInstance()->xeq($req)->tab(self::class);
    }
}
