<?php

    // index.php
    require_once('./01_Book.php');
    require_once('./02_Member.php');
    require_once('./04_LendManager.php');
    require_once('./05_BookManager.php');
    require_once('./06_InputValidator.php');
    require_once('./07_JsonStore.php');
    require_once('./SaveDataUseCase.php');
    require_once('./LendBook/LendBookUsecase.php');

    $saveDataUseCase = new SaveDataUseCase();
    $lendBookUsecase = new LendBookUsecase();
    $lendManager = new LendManager();
    $bookManager = new BookManager();
    $loanInputValidator = new LoanInputValidator();
    $bookInputValidator = new BookInputValidator();
    $baseDir = __DIR__;
    $dataPath = $baseDir . DIRECTORY_SEPARATOR . "data.json";
    $store = new SQLStore($dataPath);
    date_default_timezone_set('Asia/Tokyo'); // LendBookUsecase に置かない
    $loanDate = date('Y-m-d'); // LendBookUsecase に置かない

    while(true) {

        // メニュー表示およびユーザー入力取得
        $method = readline(
            "\n Select a number: \n".
            "1. Lend a book.\n".
            "2. Return a book.\n".
            "3. Register a book.\n".
            "4. View all books.\n".
            "5. View all loans.\n".
            "6. Save the data.\n".
            "7. Load the data.\n".
            "8. Delete a book.\n".
            "0. terminate.\n"
        );
        if($method == "0") break; 

        switch($method) {

            case "1" : // 図書貸出機能の呼び出し

                $bookNumber = readline("\n Enter the number of a book.\n");
                // 貸出対象の図書番号を入力
                $memberName = readline("Enter your name.\n");
                // 利用者名を入力

                try {
                    
                    $lendBookUsecase->lendBook($bookNumber, $memberName, $loanInputValidator, $lendManager, $bookManager, $loanDate);
                    echo "\nThe loan has been completed.\n";
                    break;

                } catch(Throwable $e) {

                    error_log($e->getMessage());
                    echo $e->getMessage();
                    break;
                }

            case "2" : // 図書返却機能の呼び出し

                $bookNum = readline("\n Enter the number of a book. \n");
                // 返却対象の図書番号を入力

                $foundLoan = $lendManager->findLoanByBookNum($bookNum);
                // 入力された番号を持つ貸出記録を検索

                if($foundLoan === null) { // 検索失敗時
                 
                    echo "\n No results. \n";
                    break;

                } else {
                    
                    $gotBook = $foundLoan->getBook();
                    
                    if($lendManager->returnFrom($gotBook)){
                    // 検索成功時、該当の貸出記録に紐づく Book オブジェクトを利用して、
                    // 全体の貸出記録を走査し、
                    // この Book オブジェクトを保持している単一の貸出記録を全体の記録から削除する。
                        echo "\n Return completed. \n";

                    }else echo "\n No results. \n"; // 返却可能な図書が見つからなかった場合

                    break;
                }

            case "3" : // 図書登録機能の呼び出し

                $bookNum = readline("\n Enter the number of a book. \n");
                // 登録する図書番号を入力
                if($bookManager->isNumRegistered($bookNum)) {
                // 図書番号の重複検証を実行

                    echo "\n This book is already registered. \n";
                    break;
                }

                $bookName = readline("\n Enter the name of a book. \n");
                // 登録する図書名を入力

                if(!$bookInputValidator->validate($bookNum, $bookName)) {
                    // validate という interface の method にのみ依存

                    echo "\nInvalid input. Enter a 3-digit book number and a book name (English or Japanese letters only).\n";
                    break;
                }

                $book = new Book($bookNum, $bookName);
                // 入力された番号と名前で Book オブジェクトを生成

                $bookManager->register($book);
                // 生成したオブジェクトを図書リストに登録
                echo "\n Registration successful. \n";
                break;

            case "4" : // 登録済み図書一覧表示
                print_r($bookManager->view());
                break;

            case "5" : // 貸出記録一覧表示
                print_r($lendManager->view());
                break;

            case "6" : // データ保存機能の呼び出し
                if($saveDataUseCase->saveData($store, $bookManager, $lendManager)) {

                    echo "\n Data saved. \n";
                } else echo "\n Failed to save data. \n";
                
                break;

            case "7" : // データ読込機能の呼び出し
                $parsedData = $store->load();
                if($parsedData === null) {

                    echo "\n No data to load. \n";

                }else { // load methodの戻り値を mapping methodに渡す

                    $store->mapping($parsedData, $bookManager, $lendManager);
                    echo "\n Data Loaded. \n";
                }
                break;

            case "8" :
                $bookNum = readline("\n Enter the number of a book. \n"); // 削除する書籍番号を入力
                
                if($lendManager->isBookLent($bookNum)) {
                // 書籍が貸出中の場合は switch 文を終了
                
                    echo "\n This book is on loan. \n";
                    break;
                }

                if($bookManager->deleteBook($bookNum) === false) { // 削除対象の書籍が存在しない場合

                    echo "\n No book to delete. \n";

                } else { // 書籍が存在し、削除に成功した場合

                    echo "\n The book with number $bookNum has been deleted. \n";
                }
                break;

            default : echo "\n Invalid method selected. \n";
        }
    }
?>