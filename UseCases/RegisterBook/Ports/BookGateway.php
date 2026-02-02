<?php

    interface BookGateway {
        
        public function register(Book $book): void;
        public function findByNumber(string $bookNumber): ?Book;
        public function fetchAll(): array;
        public function deleteByNumber(string $bookNumber): bool;
    }

?>