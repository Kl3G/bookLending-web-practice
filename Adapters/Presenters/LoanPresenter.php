<?php

    class LoanPresenter {

        public function present(array $loans): array {

            $loanList = [];

            foreach($loans as $loan) {

                $loanList[] = [

                    'book_no' => $loan->bookNumber(),
                    'member_name' => $loan->memberName(),
                    'loan_date' => $loan->date()
                ];
            }
            return $loanList;
        }
    }

?>