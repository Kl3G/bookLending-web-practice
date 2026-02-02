<?php

    class ViewLoansUsecase {

        public function viewLoans(
            LoanGateway $loanGateway,
            LoanPresenter $loanPresenter
        ): array {

            return $loanPresenter->present($loanGateway->fetchAll());
        }
    }

?>