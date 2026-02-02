<?php

    class BookPresenter {

        public function present(array $books): array {

            $booklist = [];

            foreach($books as $book) {

                $status = $book->isLent() ? 'On loan' : 'Available';

                $booklist[] = [

                    'book_no' => $book->number(),
                    'book_title' => $book->name(),
                    'loan_status' => $status
                ];
            }
            return $booklist;
        }
    }

?>