<?php

    class SaveDataUseCase {

        public function saveData(DataStore $store, BookManager $bookManager, LendManager $lendManager) {
        // use case の中に policy を置くことで、
        // object($store) の type が abstraction に依存できるようになった。
        // その結果、DataStore type ではない value が渡された場合、
        // error の理由を type 不一致として明確に把握できる。
        // type 依存をしない場合、method 呼び出し前に error が発生せず、
        // より遅い段階で別の形の error として発生する可能性がある。
        // これは、error message だけから error の原因を把握しにくくする。

            return $store->save($bookManager->toJsonArray(), $lendManager->toJsonArray());
            // これで object($store) の type と method(save()) の両方が abstraction に依存する。
            // つまり、policy が details ではなく abstraction に依存している状態になった。
            // これは JSONStore ↔ SQLStore のような具体 class の交換範囲を圧縮し、
            // object の method を、error の発生とその理由が
            // 比較的予測可能な構造の上で呼び出せるようにする。
        }
    }

?>