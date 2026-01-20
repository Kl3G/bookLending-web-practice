<?php

    // LendBookUsecase.php

    class LendBookUsecase { // business rule が変わる時に限り、use case も変更される

        public function lendBook(string $bookNumber, string $memberName, LoanInputValidator $loanInputValidator,
        LendManager $lendManager, BookManager $bookManager, string $loanDate): void {

            if(!($loanInputValidator->validate($bookNumber, $memberName))) { // 入力値 validate
                throw new Exception("Enter a 3-digit book number and a member name (English or Japanese letters only).");
            }

            if($lendManager->isBookLent($bookNumber)) { // 本の重複貸出の検査
                throw new Exception("This book is already on loan.");
            }

            $foundBook = $bookManager->findBook($bookNumber);

            if($foundBook === null) { // 本の存在の検査
                throw new Exception("No results.");
            }

            $member = new Member($memberName); // 入力値で object 生成

            $lendManager->lendTo($foundBook, $member, $loanDate); // 貸出実行
        }
    }
?>