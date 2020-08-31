<?php

class Reservation implements ORM {

    public $id_reservation;
    public $id_client;
    public $nb_personne;
    public $date_resa;
    public $heure;
    public $info;
    public $confirme;
    public $annuler;

    public function __construct($id_reservation = null, $id_client = null, $nb_personne = null, $date_resa = null, $heure = null, $info = null, $confirme = null, $annuler = null) {
        $this->id_reservation = $id_reservation;
        $this->id_client = $id_client;
        $this->nb_personne = $nb_personne;
        $this->date_resa = $date_resa;
        $this->heure = $heure;
        $this->info = $info;
        $this->confirme = $confirme;
        $this->annuler = $annuler;
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
        $confirme = $this->confirme ?: 'NULL';
        $annuler = $this->annuler ?: 'NULL';
        $req = "INSERT INTO reservation VALUES ({$id_reservation}, {$this->id_client}, {$this->nb_personne}, {$db->esc($this->date_resa)}, {$db->esc($this->heure)}, {$db->esc($info)}, {$confirme}, {$annuler}) ON DUPLICATE KEY UPDATE id_client = {$this->id_client}, nb_personne = {$this->nb_personne}, date_resa = {$db->esc($this->date_resa)}, heure = {$db->esc($this->heure)}, info = {$db->esc($info)}, confirme = {$confirme}, annuler = {$annuler}";
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

    static function updateConfirme($id_reservation) {
        $req = "UPDATE reservation SET confirme = 1 WHERE id_reservation = {$id_reservation}";
        DBMySQL::getInstance()->xeq($req);
    }

    static function updateConfirmeClient($id_reservation) {
        $req = "UPDATE reservation SET confirme = 2 WHERE id_reservation = {$id_reservation}";
        DBMySQL::getInstance()->xeq($req);
    }

    static function annuler($id_reservation) {
        $req = "UPDATE reservation SET confirme = NULL, annuler = 1 WHERE id_reservation = {$id_reservation}";
        DBMySQL::getInstance()->xeq($req);
    }

    static function tab($where = 1, $orderBy = 1, $limit = null) {
        // Retourne un tableau d'enregistrements sous la forme d'instances.
        $req = "SELECT * FROM reservation WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return DBMySQL::getInstance()->xeq($req)->tab(self::class);
    }

    static function tabJoin($where = 1, $orderBy = 1, $limit = null) {
        // Retourne un tableau d'enregistrement avec une jointure entre les tables réservation et client
        $req = "SELECT * FROM reservation JOIN client USING(id_client) WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return DBMySQL::getInstance()->xeq($req)->tab(self::class);
    }

    static function tabStat($dateMin, $dateMax) {
        // Retourne un tableau d'enregistrement pour comptabiliser le nombre de réservations confirmées et la somme des clients sur un intervalle de dates.
        $db = DBMySQL::getInstance();
        $req = "SELECT DISTINCT date_resa, count(confirme) resa, SUM(nb_personne) couverts from reservation WHERE confirme IS NOT NULL AND date_resa BETWEEN {$db->esc($dateMin)} AND {$db->esc($dateMax)} GROUP BY date_resa";
        return $db->xeq($req)->tab();
    }

    static function midiStat($date_resa) {
        // Retourne l'enregistrement pour afficher le décompte de réservations et de client sur la date renseignées uniquement le Midi.
        $db = DBMySQL::getInstance();
        $req = "SELECT count(confirme) resa_midi, SUM(nb_personne) couverts_midi from reservation WHERE confirme IS NOT NULL AND date_resa = {$db->esc($date_resa)} AND heure BETWEEN '11:00' AND '15:00'";
        return $db->xeq($req)->prem();
    }

    static function soirStat($date_resa) {
        // Retourne l'enregistrement pour afficher le décompte de réservations et de client sur la date renseignées uniquement le Soir.
        $db = DBMySQL::getInstance();
        $req = "SELECT count(confirme) resa_soir, SUM(nb_personne) couverts_soir from reservation WHERE confirme IS NOT NULL AND date_resa = {$db->esc($date_resa)} AND heure BETWEEN '18:00' AND '23:00'";
        return $db->xeq($req)->prem();
    }

    static function jourStatMax($dateMin, $dateMax) {
        // Retourne l'enregistrement comptabilisant le plus de couverts à la journée sur un intervalle de dates renseignées.
        $db = DBMySQL::getInstance();
        $req = "SELECT date_resa, SUM(nb_personne) couverts from reservation WHERE confirme IS NOT NULL AND date_resa BETWEEN {$db->esc($dateMin)} AND {$db->esc($dateMax)} group by date_resa ORDER BY couverts DESC LIMIT 1";
        return $db->xeq($req)->prem();
    }

    static function midiStatMax($dateMin, $dateMax) {
        // Retourne l'enregistrement comptabilisant le plus de couverts le Midi sur un intervalle de dates renseignées.
        $db = DBMySQL::getInstance();
        $req = "SELECT date_resa, SUM(nb_personne) couverts from reservation WHERE confirme IS NOT NULL AND date_resa BETWEEN {$db->esc($dateMin)} AND {$db->esc($dateMax)} AND heure BETWEEN '11:00' AND '15:00' group by date_resa ORDER BY couverts DESC LIMIT 1";
        return $db->xeq($req)->prem();
    }

    static function soirStatMax($dateMin, $dateMax) {
        // Retourne l'enregistrement comptabilisant le plus de couverts le Soir sur un intervalle de dates renseignées.
        $db = DBMySQL::getInstance();
        $req = "SELECT date_resa, SUM(nb_personne) couverts from reservation WHERE confirme IS NOT NULL AND date_resa BETWEEN {$db->esc($dateMin)} AND {$db->esc($dateMax)} AND heure BETWEEN '18:00' AND '23:00' group by date_resa ORDER BY couverts DESC LIMIT 1";
        return $db->xeq($req)->prem();
    }

    static function countResa() {
        // Retourne l'enregistrement comptabilisant le nombre de réservations en attente de traitement (non confirmées, non annulées).
        $db = DBMySQL::getInstance();
        $today = date("Y-m-d");
        $req = "SELECT count(*) total FROM reservation WHERE confirme IS NULL AND annuler IS NULL AND date_resa >= {$db->esc($today)}";
        return $db->xeq($req)->prem();
    }

}
