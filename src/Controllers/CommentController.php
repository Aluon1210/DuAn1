<?php
// File: src/Controllers/CommentController.php
namespace Controllers;

use Core\Controller;

class CommentController extends Controller {

    /**
     * AJAX: Lấy danh sách bình luận cho một product và trả về HTML partial
     * URL: /comment/ajaxList/{productId}
     */
    public function ajaxList($productId)
    {
        header('Content-Type: application/json');
        try {
            $commentModel = new \Models\Comment();
            $comments = $commentModel->getByProductId($productId);

            ob_start();
            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    ?>
                    <div class="comment-item">
                        <div class="comment-header">
                            <span class="comment-author">👤 <?php echo htmlspecialchars($comment['user_name'] ?? 'Ẩn danh'); ?></span>
                            <span class="comment-date"><?php echo htmlspecialchars($comment['Create_at'] ?? date('d/m/Y')); ?></span>
                        </div>
                        <div class="comment-content"><?php echo htmlspecialchars($comment['Content']); ?></div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="empty-comments">
                    <div style="font-size: 48px; margin-bottom: 12px;">💭</div>
                    <p>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                </div>
                <?php
            }
            $html = ob_get_clean();

            echo json_encode(['ok' => true, 'html' => $html, 'count' => count($comments)]);
        } catch (\Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}
?>