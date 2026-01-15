<?php

    // 07_jsonStore.php
    require_once('./01_book.php');
    require_once('./02_member.php');

    interface DataStore { // interface 로 추상 의존을 준비

        public function save(array $books, array $loans);
    }
    // interface 사용 이유
    // 1. 두 Store 를 교체하며 사용할 이유가 필요하며 명확하다.
    // 2. SQLStore 와 JSONStore 둘 다 $books 와 $loans 를 받아 그것을 저장한다.
    // 즉, 책임과 그 책임이 향하는 대상(입력의 의미)이 같고, 그것을 구현하는 방식만 다르다.

    class SQLStore implements DataStore { // Details(SQLStore, JSONStore)이 추상에 의존

        public function __construct(private string $path) {}

        public function save(array $books, array $loans): bool {

            $payload = [

                "books" => $books,
                "loans" => $loans
            ];

            // Store 교체 test のため、echo を임시 사용
            echo "SQLStore\n".json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
            if(file_put_contents($this->path, $json, LOCK_EX)) { return true; } // setItem
            // Parameters = 1.path  2.data  3.flags

            return false; // 반환 타입 명시하고, 성공/실패 return
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