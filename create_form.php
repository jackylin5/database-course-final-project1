<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>新增表單 - 揪團趣</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <?php include "includes/auth_check.php"; ?> <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">📝 建立新表單</h4>
            </div>
            <div class="card-body">
                <form action="save_form.php" method="POST">
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label fw-bold">表單標題</label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="例如：週五羽球內戰報名表" required>
                        <label class="form-label mt-3">活動描述</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div id="questions-container">
                    </div>

                    <div class="text-center mt-4">
                        <div class="text-center mt-4">
                            <button type="button" id="add-question" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> ＋ 新增題目
                            </button>
                            <hr>
                            <a href="index.php" class="btn btn-outline-secondary btn-lg px-4 me-2">取消並回首頁</a>
                            <button type="submit" class="btn btn-success btn-lg px-5">發布表單</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let qCount = 0;

        // 1. 新增題目
        $('#add-question').click(function() {
            qCount++;
            let questionHtml = `
        <div class="card mb-4 border-start border-primary border-4 question-item shadow-sm" id="q-row-${qCount}">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-7">
                        <label class="form-label fw-bold text-primary">題目 ${qCount}</label>
                        <input type="text" name="q_text[${qCount}]" class="form-control" placeholder="請輸入問題內容" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">題型</label>
                        <select name="q_type[${qCount}]" class="form-select q-type-select" data-id="${qCount}">
                            <option value="text">簡答</option>
                            <option value="textarea">詳答</option>
                            <option value="radio">單選</option>
                            <option value="checkbox">多選</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger remove-q" data-id="${qCount}">❌</button>
                    </div>
                </div>

                <div id="options-container-${qCount}" class="options-area ps-4 d-none">
                    <label class="form-label small fw-bold text-secondary">選項設定</label>
                    <div class="option-list" id="opt-list-${qCount}">
                        </div>
                    <button type="button" class="btn btn-sm btn-link text-decoration-none add-option" data-qid="${qCount}">
                        ➕ 新增選項
                    </button>
                </div>
            </div>
        </div>`;
            $('#questions-container').append(questionHtml);
        });

        // 2. 監聽題型切換：如果是單選或多選，才顯示選項區
        $(document).on('change', '.q-type-select', function() {
            let id = $(this).data('id');
            let type = $(this).val();
            if (type === 'radio' || type === 'checkbox') {
                $(`#options-container-${id}`).removeClass('d-none');
            } else {
                $(`#options-container-${id}`).addClass('d-none');
            }
        });

        // 3. 新增選項 (Nested Dynamic)
        $(document).on('click', '.add-option', function() {
            let qid = $(this).data('qid');
            let optHtml = `
        <div class="input-group mb-2 w-75">
            <span class="input-group-text">●</span>
            <input type="text" name="q_options[${qid}][]" class="form-control form-control-sm" placeholder="選項內容" required>
            <button class="btn btn-outline-secondary btn-sm remove-opt" type="button">🗑️</button>
        </div>`;
            $(`#opt-list-${qid}`).append(optHtml);
        });

        // 4. 刪除選項
        $(document).on('click', '.remove-opt', function() {
            $(this).closest('.input-group').remove();
        });

        // 5. 刪除題目
        $(document).on('click', '.remove-q', function() {
            $(`#q-row-${$(this).data('id')}`).remove();
        });
    </script>
</body>

</html>