<?php

    class InmemoryBookGateway implements BookGateway {

        private array $books = []; // in-memory storage

        public function register(Book $book): void {

            $this->books[] = $book;
        }

        public function findByNumber(string $bookNumber): ?Book {

            foreach($this->books as $book) {

                if($book->hasNumber($bookNumber)) return $book;
            }
            return null;
        }

        public function fetchAll(): array {

            return $this->books;
        }

        public function deleteByNumber(string $bookNumber): bool {

            foreach($this->books as $index=>$book) {

                if($book->hasNumber($bookNumber)) {

                    unset($this->books[$index]);
                    return true;
                }
            }
            return false;
        }
    }
?>