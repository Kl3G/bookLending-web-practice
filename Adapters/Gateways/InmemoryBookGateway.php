<?php

    class InmemoryBookGateway implements BookGateway {

        private array $books = [];

        public function register(Book $book): void {

            $this->books[] = $book;
        }

        public function existsByNumber(string $bookNumber): ?Book {

            foreach($this->books as $book) {

                if($book->hasNumber($bookNumber)) return $book;
            }
            return null;
        }

        public function fetchAll(): array {

            return $this->books;
        }
    }
?>