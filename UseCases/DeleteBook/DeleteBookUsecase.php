<?php

    class DeleteBookUsecase {

        public function deleteBook(
            
            string $bookNumber,
            BookGateway $bookGateway
        ) {

            $book = $bookGateway->findByNumber($bookNumber);
            if($book === null) {

                throw new Exception("No book to delete.");
            }

            if($book->isLent()) {

                throw new Exception("This book is on loan.");
            }

            if(!($bookGateway->deleteByNumber($bookNumber))) {

                throw new Exception("Delete failed.");
            }
        }
    }

?>