<?php

    class InmemoryLoanGateway implements LoanGateway {

        private array $loans = []; // in-memory storage

        public function register(Loan $loan): void {

            $this->loans[] = $loan;
        }

        public function deleteByBookNumber(string $bookNumber): bool {

            foreach($this->loans as $index => $loan) {

                if($loan->hasBookNumber($bookNumber)) {
                    
                    unset($this->loans[$index]);
                    $this->loans = array_values($this->loans);
                    return true;
                }
            }
            return false;
        }

        public function fetchAll(): array {

            return $this->loans;
        }
    }

?>