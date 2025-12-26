<?php

    // 03_loan.php
    require_once('./01_book.php');
    require_once('./02_member.php');

    class Loan { // 責務 = 単一の図書貸出記録そのもの

        private Book $book; // Book オブジェクト
        private Member $member; // Member オブジェクト

        public function __construct(Book $book, Member $member) {

            $this->book = $book;
            $this->member = $member;
        }
        
        public function getMember() { // 데이터 로컬 저장 위해 추가.

            return $this->member;
        }

        public function getBook() { // Book オブジェクトの getter

            return $this->book;
        }
    }
    
?>