<?php

    interface BookGateway {
        
        public function register(Book $book): void;
        public function existsByNumber(string $bookNumber): ?Book;
        public function fetchAll(): array;
    }

?>