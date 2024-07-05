<?php

namespace EdvGraz\CMS;

class User {
    protected Database $db;

    public function __construct( Database $db) {
        $this->db = $db;
    }

    public function fetch(int $id): array {
        $sql = "SELECT a.forename, a.surname, a.email FROM user WHERE id = $id";

        return $this->db->sql_execute($sql, ['id' => $id] )->fetch();
    }

    public function getAll(int $user_id = null, int $limit = 10): array {
        $sql = "SELECT a.forename, a.surname, a.email FROM user";

        $sql .= " ORDER BY a.id DESC LIMIT :limit;";

        return $this->db->sql_execute( $sql, [ 'user_id' => $user_id, 'limit' => $limit ] )->fetchAll();
    }
}