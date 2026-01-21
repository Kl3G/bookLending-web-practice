<?php

    // LendBookUsecase.php

    class LendBookUsecase { // business rule が変わる時に限り、use case も変更される

        public function __construct(private LendManager $lendManager, 
            private BookManager $bookManager, private LoanInputValidator $loanInputValidator,) { }

        public function lendBook(LendBookRequest $lendBookRequest): void {

            if(!($this->loanInputValidator->validate($lendBookRequest->bookNumber, $lendBookRequest->memberName))) { // 入力値 validate
                throw new Exception("Enter a 3-digit book number and a member name (English or Japanese letters only).");
            }

            if($this->lendManager->isBookLent($lendBookRequest->bookNumber)) { // 本の重複貸出の検査
                throw new Exception("This book is already on loan.");
            }

            $foundBook = $this->bookManager->findBook($lendBookRequest->bookNumber);

            if($foundBook === null) { // 本の存在の検査
                throw new Exception("No results.");
            }

            $member = new Member($lendBookRequest->memberName); // 入力値で object 生成

            $this->lendManager->lendTo($foundBook, $member, $lendBookRequest->loanDate); // 貸出実行
        }
    }
?>