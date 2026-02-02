<?php

    // LendBookUsecase.php

    class LendBookUsecase { // business rule が変わる時に限り、use case も変更される

        public function lendBook(
            
            LendBookRequest $lendBookRequest,
            BookGateway $bookGateway,
            LoanGateway $loanGateway,
        ): void {

            $book = $bookGateway->findByNumber($lendBookRequest->bookNumber);

            if($book !== null) {

                $book->lend();

                $loan = new Loan(
                    $lendBookRequest->bookNumber,
                    $lendBookRequest->memberName,
                    $lendBookRequest->loanDate,    
                );
                $loanGateway->register($loan);
            } else throw new Exception("There is no such book.");
        }
    }
?>