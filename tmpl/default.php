<?php
/**
 * @package     mod_ollama_chat
 * @author      Joko Supriyanto <joko@sibermu.ac.id>
 * @Institution Universitas Siber Muhammadiyah - SiberMu https://sibermu.ac.id/
 * @copyright   Copyright (C) 2025 JokoVlog. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo Uri::base(); ?>modules/mod_ollama_chat/chatbox.css">
<script src="<?php echo Uri::base(); ?>modules/mod_ollama_chat/chatbox.js"></script>

<!-- Floating Chat Button -->
<div class="chatbot-toggle" onclick="toggleChatbox()">
    <i class="fas fa-robot"></i>
</div>

<!-- Chat Window -->
<div class="chatbot-window" id="chatbotWindow">
    <div class="chatbot-header">
        <i class="fas fa-comments"></i> <?php echo htmlspecialchars($assistantName ?? ''); ?>
        <span class="close-btn" onclick="toggleChatbox()">×</span>
    </div>
    
    <div class="chatbot-body">
        <div class="chat-history" id="chatHistory">
            <?php foreach ($chatHistory as $entry): ?>
                <div class="chat-message <?php echo $entry['role']; ?>">
                    <?php echo ucfirst($entry['role']); ?>:
                    <div class="message-content">
                        <?php 
                        $content = htmlspecialchars($entry['content'] ?? '');
                        $content = preg_replace("/(\r?\n){2,}/", "\n", $content);
                        echo nl2br(trim($content));
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="supported-topics">
    <small>Ask me anything, and I'll answer you.</small>
    <div class="chatbot-footer" style="padding: 5px 10px; font-size: 10px; text-align: center; background: #f5f5f5; border-top: 1px solid #ddd;">
    Chatbot AI Open WebUI
    <a href="https://jokovlog.my.id/produkku/412-modul-open-webui-chat-bot-ai-joomla.html" target="_blank" style="color: #4a90e2;">JokoVlog</a> | 
    <a href="https://youtube.com/jokovlog" target="_blank" style="color: #4a90e2;">YouTube</a>
</div>
</div>
        </div>

        <form method="post" onsubmit="setTimeout(scrollToBottom, 100)">
            <?php echo HTMLHelper::_('form.token'); ?>
            <div class="chat-input-group">
                <input type="text" name="user_input" placeholder="Write a message..." required>
                <button type="submit" name="send">Send</button>
                <button type="submit" name="reset" value="1">Reset</button>
            </div>
        </form>
    </div>
</div>

<script>
// Scroll ke bawah saat ada pesan baru (setelah form submit)
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>