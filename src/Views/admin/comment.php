<?php
// comment.php
// Kết nối DB trước đó (ví dụ: include 'pdo.php';)

// Lấy dữ liệu bình luận từ DB (ví dụ)
// $comments = pdo_query("SELECT * FROM comments ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">

<?php include __DIR__ . '/head.php'; ?>
<style>
    :root {
        --black: #0f0f10;
        --white: #fff;
        --text: #222;
        --gold: #d4af37;
        --border: #e8e8e8;
        --bg: #f9f9f9;
        --success: #28a745;
        --danger: #dc3545;
        --pending: #ffc107;
    }

    /* Base */
    body {
        margin: 0;
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
        background: var(--bg);
        color: var(--text);
        line-height: 1.5;
    }

    /* Admin container */
    .admin-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Main content cách sidebar và header */
    main.admin-content {
        margin-left: calc(260px + 50px);
        /* sidebar + khoảng cách */
        margin-top: 100px;
        /* header height */
        padding: 30px;
        width: calc(100% - 260px - 50px);
        box-sizing: border-box;
    }

    /* Tiêu đề */
    h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
    }

    /* Thống kê */
    .stats-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 20px 25px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    /* Bảng bình luận */
    .table-container {
        background: #fff;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    table th,
    table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border);
        text-align: left;
    }

    table th {
        background: var(--black);
        color: #fff;
        font-weight: 600;
    }

    table tr:hover td {
        background: #f1f1f1;
    }

    /* Status badge */
    .status-badge {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        display: inline-block;
    }

    .status-badge.approved {
        background: var(--success);
    }

    .status-badge.pending {
        background: var(--pending);
        color: #111;
    }

    .status-badge.rejected {
        background: var(--danger);
    }

    /* Action buttons */
    .btn-small {
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 13px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: var(--gold);
        color: #111;
    }

    .btn-delete {
        background: var(--danger);
        color: #fff;
    }

    .btn-small:hover {
        opacity: 0.85;
    }

    /* Responsive */
    @media (max-width: 768px) {
        main.admin-content {
            margin-left: 0;
            width: 100%;
            padding: 20px;
        }

        table th,
        table td {
            font-size: 13px;
            padding: 10px;
        }

        .btn-small {
            font-size: 12px;
            padding: 5px 8px;
        }

        table {
            display: block;
            overflow-x: auto;
        }
    }
</style>


<body>

    <?php include __DIR__ . '/aside.php'; ?>
    <div class="admin-container">

        <main class="admin-content">

            <h2>Quản lý bình luận</h2>

            <!-- Thống kê -->
            <div class="stats-box">
                <p><strong>Tổng bình luận:</strong>
                    <?php
                    // echo count($comments); 
                    echo 3; // demo
                    ?>
                </p>
            </div>

            <!-- Bảng bình luận -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Sản phẩm</th>
                            <th>Nội dung</th>
                            <th>Ngày</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // foreach($comments as $c):
                        ?>
                        <tr>
                            <td>1</td>
                            <td>Nguyen Van A</td>
                            <td>Áo thun trắng</td>
                            <td>Bình luận rất hay!</td>
                            <td>2025-11-19</td>
                            <td><span class="status-badge approved">Đã duyệt</span></td>
                            <td>
                                <a href="#" class="btn-small btn-edit">Sửa</a>
                                <a href="#" class="btn-small btn-delete">Xóa</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Tran Thi B</td>
                            <td>Quần jeans</td>
                            <td>Chất lượng tốt</td>
                            <td>2025-11-18</td>
                            <td><span class="status-badge pending">Chờ duyệt</span></td>
                            <td>
                                <a href="#" class="btn-small btn-edit">Sửa</a>
                                <a href="#" class="btn-small btn-delete">Xóa</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Le Van C</td>
                            <td>Áo sơ mi xanh</td>
                            <td>Không vừa size</td>
                            <td>2025-11-17</td>
                            <td><span class="status-badge rejected">Từ chối</span></td>
                            <td>
                                <a href="#" class="btn-small btn-edit">Sửa</a>
                                <a href="#" class="btn-small btn-delete">Xóa</a>
                            </td>
                        </tr>
                        <?php
                        // endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>