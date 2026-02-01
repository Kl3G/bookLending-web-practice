<?php

    class ViewLoansUsecase {

        public function viewLoans(LoanGateway $loanGateway): array {

            return $loanGateway->fetchAll();
        }
    }

?>