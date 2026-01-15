<?php

    // 08_interfaceTest.php
    require_once('./01_book.php');
    require_once('./02_member.php');
    require_once('./04_lendManager.php');
    require_once('./05_bookManager.php');
    require_once('./07_jsonStore.php');

    $lendManager = new LendManager();
    $bookManager = new BookManager();

    $baseDir = __DIR__;
    $dataPath = $baseDir . DIRECTORY_SEPARATOR . "data.json";
    
    // 1. No abstraction
    echo "\n No abstraction \n";
    // JSONStore.saveJSON(), SQLStore.saveSQL() 이라는 가정.
    $jsonStore = new JSONStore($dataPath);
    $sqlStore = new SQLStore($dataPath);

    // Policy(저장 실행의 결정)가 Details(saveJSON(), saveSQL())에 의존하고 있다.
    $jsonStore->saveJSON($bookManager->toJsonArray(), $lendManager->toJsonArray()); // file1.php
    $sqlStore->saveSQL($bookManager->toJsonArray(), $lendManager->toJsonArray()); // file2.php
    // 때문에, saveJSON 또는 saveSQL 두 method 를 서로 교체해서 사용하거나 method 이름이 변경될 때
    // 두 method 의 이름을 직접 언급하는 모든 곳(file1.php, file2.php)을 수정해야 한다.
    echo "\n---------------\n";


    // 2. Use abstraction
    echo "\n Use abstraction \n";
    // saveJSON(), saveSQL() 를 save() 라는 abstration 으로 선언한 가정.
    $store = new JSONStore($dataPath);

    // Policy(저장 실행의 결정)가 abstraction(save())에 의존하고 있다.
    $store->save($bookManager->toJsonArray(), $lendManager->toJsonArray()); // file1.php
    $store->save($bookManager->toJsonArray(), $lendManager->toJsonArray()); // file2.php

    // 때문에 느낄 수 있는 장점 =
    // 1. method 이름 차이로 인한 수정 확산을 막는다.
    // 2. 두 Store 를 서로 교체해서 사용하고 싶을 때는 모든 file 에 접근하지 않고 생성자만 변경하면 된다.
    $store = new SQLStore($dataPath);
    $store->save($bookManager->toJsonArray(), $lendManager->toJsonArray()); // file1.php
    $store->save($bookManager->toJsonArray(), $lendManager->toJsonArray()); // file2.php
    echo "\n---------------\n";


    // abstraction 에 의존한다는 건, "약속했기 때문에 존재하거나 가능할 거라고 믿는다"라는 말과 같다.
    // interface 를 사용해서 type 으로 강제할 수 있지만, interface 를 사용하지 않을 수도 있다.
    // 여러 class 에 save 라는 method 를 정의하도록 약속하고,
    // 그 약속에 의존해서 save() 를 호출하는 것도 넓은 의미에서 abstraction 의존으로 볼 수 있기 때문이다.
    // 하지만, 구조적으로 안전하게 추상에 의존하기 위해서는 interface 같은 개념이 필요하다.
    // 즉, interface 는 약속한 것은 반드시 존재한다고 선언하는 강력한 구조적 안전 장치다.
?>