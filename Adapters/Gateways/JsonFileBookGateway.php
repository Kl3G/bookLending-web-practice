<?php

    class JsonFileBookGateway implements BookGateway {

        private array $books = [];

        public function register(Book $book): void {

            $this->books[] = $book;
        }

        public function existsByNumber(string $bookNumber): bool {

            foreach($this->books as $book) {

                if($book->hasNumber($bookNumber)) return true;
            }
            return false;
        }

        public function findByNumber(string $bookNumber): ?Book {

            foreach($this->books as $book) {

                if($book->hasNumber($bookNumber)) return $book;
            }
            return null;
        }
    }
?>