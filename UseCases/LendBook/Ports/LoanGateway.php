<?php

    interface LoanGateway {

        public function register(Loan $Loan): void;
        public function deleteByBookNumber(string $bookNumber): bool;
        public function fetchAll(): array;
    }

?>