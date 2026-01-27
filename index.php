<?php

    // index.php
    require_once('./Entities/Book.php');
    require_once('./Entities/Member.php');
    require_once('./LendManager.php');
    require_once('./BookManager.php');
    require_once('./InputValidator.php');
    require_once('./JsonStore.php');
    
    require_once('./UseCases/LendBook/LendBookUsecase.php');
    require_once('./UseCases/LendBook/LendBookRequest.php');

    require_once('./UseCases/ReturnBook/ReturnBookUsecase.php');

    require_once('./UseCases/RegisterBook/RegisterBookUsecase.php');
    require_once('./UseCases/RegisterBook/RegisterBookRequest.php');
    require_once('./UseCases/RegisterBook/Ports/BookGateway.php');
    require_once('./Adapters/Gateways/JsonFileBookGateway.php');

    require_once('./SaveDataUseCase.php');

    

    $lendManager = new LendManager();
    $bookManager = new BookManager();
    $loanInputValidator = new LoanInputValidator();
    $bookInputValidator = new BookInputValidator();
    $baseDir = __DIR__;
    $dataPath = $baseDir . DIRECTORY_SEPARATOR . "data.json";
    $store = new SQLStore($dataPath);
    date_default_timezone_set('Asia/Tokyo'); // LendBookUsecase に置かない

    $lendBookUsecase = new LendBookUsecase($lendManager, $bookManager, $loanInputValidator);

    $returnBookUsecase = new ReturnBookUsecase($lendManager);

    $registerBookUsecase = new RegisterBookUsecase();
    $jsonFileBookGateway = new JsonFileBookGateway();

    $saveDataUseCase = new SaveDataUseCase();

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

                $bookNumber = readline("\n Enter the number of a book.\n"); // 貸出対象の図書番号を入力
                $memberName = readline("Enter your name.\n"); // 利用者名を入力
                $today = date('Y-m-d'); // 現在の日付
                $lendBookRequest = new LendBookRequest($bookNumber, $memberName, $today); // DTO生成
                try {

                    $lendBookUsecase->lendBook($lendBookRequest); // Usecase呼出
                    echo "\nThe loan has been completed.\n";
                    break;

                } catch(Throwable $e) {

                    echo $e->getMessage();
                    break;
                }

            case "2" : // 図書返却機能の呼び出し

                $bookNum = readline("\n Enter the number of a book. \n"); // 返却対象の図書番号を入力
                try {

                    $returnBookUsecase->returnBook($bookNum);
                    echo "\nReturn completed.\n";
                    break;

                } catch(Throwable $e) {

                    echo $e->getMessage();
                    break;
                }

            case "3" : // 図書登録機能の呼び出し

                $bookNumber = readline("\n Enter the number of a book. \n"); // 登録する図書番号を入力
                $bookName = readline("\n Enter the name of a book. \n");// 登録する図書名を入力
                $registerBookRequest = new RegisterBookRequest($bookNumber, $bookName);

                try {

                    $registerBookUsecase->registerBook($registerBookRequest, $jsonFileBookGateway);
                    echo "\n Registration successful. \n";
                    break;

                } catch(throwable $e) {

                    echo $e->getMessage();
                    break;
                }

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