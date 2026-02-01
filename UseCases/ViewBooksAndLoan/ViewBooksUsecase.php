<?php

    class ViewBooksUsecase {

        public function viewBooks(BookGateway $bookGateway): array {

            return $bookGateway->fetchAll();
        }
    }

?>