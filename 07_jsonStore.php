<?php

    // 07_JsonStore.php
    require_once('./01_Book.php');
    require_once('./02_Member.php');

    interface DataStore { // interface 로 추상 의존을 준비

        public function save(array $books, array $loans): bool;
    }

    class SQLStore implements DataStore { // Details(SQLStore, JSONStore)이 추상에 의존

        public function __construct(private string $path) {}

        public function save(array $books, array $loans): bool {

            $payload = [

                "books" => $books,
                "loans" => $loans
            ];

            if(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) === false ) return false;
            
            return true;
        }
    }
    
    class JSONStore implements DataStore { // 責務：データの保存・読込

        // 図書一覧と貸出記録をまとめて JSON 形式で保存
        // Implement local JSON file–based storage and add save/load functionality.

        public function __construct(private string $path) {}

        public function save(array $books, array $loans): bool {

            $payload = [

                "books" => $books,
                "loans" => $loans
            ];

            $json = json_encode($payload, // == JSON.stringify()
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            // Parameters = 1.value  2.flags 
            if(file_put_contents($this->path, $json, LOCK_EX) === false) return false; // setItem
            // Parameters = 1.path  2.data  3.flags

            return true;
        }

        public function load() {

            if (!file_exists($this->path)) return null; // ファイル存在チェック

            $json = file_get_contents($this->path); // ファイル読込 = getItem
            if ($json === false) return null; // 読込失敗チェック
            
            $parsedData = json_decode($json, true); // == JSON.parse()
            // json_decode parameter = 1.JSON data 2.객체 연관배열 변환 기능
            if (!is_array($parsedData)) return null; // データ形式チェック

            return $parsedData; // mapping methodへ渡す
        }

        public function mapping( // load に含めると責務が曖昧になるため分離
            array $parsedData, BookManager $bookManager, LendManager $lendManager) {

            foreach($parsedData['books'] as $book) { 
                // データから図書を復元
                $bookManager->register(new Book($book['num'], $book['name']));
            }

            foreach($parsedData['loans'] as $loan) { // 

                $book = $bookManager->findBook($loan['bookNum']);
                // 二重ループを避け、findBook メソッドを再利用
                if($book !== null){
                    // データから貸出記録を復元
                    $lendManager->lendTo($book, new Member($loan['memberName']));
                }
            }
        }

        function saveData() {

            
        }
    }

?>