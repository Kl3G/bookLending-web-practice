<?php

    class ViewBooksUsecase {

        public function viewBooks(
            BookGateway $bookGateway,
            BookPresenter $bookPresenter
        ): array {

            return $bookPresenter->present($bookGateway->fetchAll());
        }
    }

?>