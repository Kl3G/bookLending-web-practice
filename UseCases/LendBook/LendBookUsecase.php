<?php

    // LendBookUsecase.php

    class LendBookUsecase { // business rule が変わる時に限り、use case も変更される

        public function lendBook(
            
            LendBookRequest $lendBookRequest,
            BookGateway $bookGateway,
            LoanGateway $loanGateway,
        ): void {

            $book = $bookGateway->existsByNumber($lendBookRequest->bookNumber);

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



// if(!($this->loanInputValidator->validate($lendBookRequest->bookNumber, $lendBookRequest->memberName))) { // 入力値 validate
//     throw new Exception("Enter a 3-digit book number and a member name (English or Japanese letters only).");
// }

// if($this->lendManager->isBookLent($lendBookRequest->bookNumber)) { // 本の重複貸出の検査
//     throw new Exception("This book is already on loan.");
// }
?>