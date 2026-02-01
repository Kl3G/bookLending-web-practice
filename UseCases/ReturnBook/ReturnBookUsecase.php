<?php

    class ReturnBookUsecase {

        function returnBook(
            
            string $bookNumber,
            BookGateway $bookGateway,
            LoanGateway $loanGateway,
        ): void {

            if(($loanGateway->deleteByBookNumber($bookNumber))) {

                $book = $bookGateway->existsByNumber($bookNumber);
                $book->endLoan();
                
            }else throw new Exception("There is no such book.");
        }
    }

?>