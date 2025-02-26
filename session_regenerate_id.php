<?php
//必ずsession_startは最初に記載
session_start();

//現在のセッションIDを取得
$old_session_id = session_id();

// 以下追加
//　新しいセッションIDを発行（前のSESSION IDは無効）
session_regenerate_id(true);

//新しいセッションIDを取得
$new_session_id = session_id();

//旧セッションIDと新セッションIDを表示
echo '古いセッション:' . $old_session_id . '<br />';
echo '新しいセッション:' . $new_session_id . '<br />';
