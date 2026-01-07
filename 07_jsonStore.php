<?php

    // 07_jsonStore.php
    require_once('./01_book.php');
    require_once('./02_member.php');
    
    class JsonStore { // 責務：データの保存・読込

        // 図書一覧と貸出記録をまとめて JSON 形式で保存
        // Implement local JSON file–based storage and add save/load functionality.

        private string $path;

        public function __construct(string $path) {

            $this->path = $path;
        }

        public function save(BookManager $bookManager, LendManager $lendManager) {

            $booksArr = []; // 登録済み図書を格納する配列

            foreach ($bookManager->getBooks() as $book) {
            // Manager から図書一覧を取得し、連想配列として保存

                $booksArr[] = [

                    "num" => $book->getNum(),
                    "name"=> $book->getName()
                ];
            }

            $loansArr = []; // 貸出記録を格納する配列

            foreach ($lendManager->getLoans() as $loan) {
            // Manager から貸出一覧を取得し、連想配列として保存

                $loansArr[] = [
                // If the data to be stored is an object,
                // store only the information needed to retrieve it later.

                    "bookNum"   => $loan->getBook()->getNum(),
                    "memberName"=> $loan->getMember()->getName()
                ];
            }

            $payload = [
            // JSON として保存する最終データ構造
                "books"=> $booksArr,
                "loans"=> $loansArr,
            ];

            $json = json_encode($payload, // == JSON.stringify()
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            // Parameters = 1.value  2.flags 
            file_put_contents($this->path, $json, LOCK_EX); // setItem
            // Parameters = 1.path  2.data  3.flags
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

        /* findBook methodを再利用しない場合の実装例
        public function mapping(array $data,
            BookManager $bookManager, LendManager $lendManager) { 
            
            foreach($data['books'] as $book) { 
            
                $bookManager->register(new Book($book['num'], $book['name'])); 
            } 
                
            $books = $bookManager->getBooks(); 
            
            foreach($data['loans'] as $loan) { 

                foreach($books as $book) { 

                    if($book->getNum() === $loan['bookNum']) { 

                        $lendManager->lendTo($book, new Member($loan['memberName'])); 
                    } 
                } 
            } 
        }
        */
    }

?>