<?php

class CMS {
    protected Database $db;
    protected Article $article;
    protected Category $category;
    protected User $user;

    public function __construct(string $dsn, string $user_name, string $password) {
        $this->db = new Databse ( $dsn, $user_name, $password );
    }

    public function getArticle(): Article {
        if ( ! isset($this->article) ) {
            $this->article = new Article( $this->db );
        }
        return $this->article;
    }

    public function getCategory(): Category {
        if ( ! isset( $this->category ) ) {
            $this->category = new Category( $this->db );
        }
        return $this->category;
    }

    public function getUser(): User {
        if ( ! isset($this->user) ) {
            $this->user = new User( $this->db );
        }
        return $this->user;
    }
}