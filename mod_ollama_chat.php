<?php
/**
 * @package     mod_ollama_chat
 * @author      Joko Supriyanto <joko@sibermu.ac.id>
 * @Institution Universitas Siber Muhammadiyah - SiberMu https://sibermu.ac.id/
 * @copyright   Copyright (C) 2025 JokoVlog. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;

class ModOllamaChat
{
    protected $session;
    protected $input;
    protected $params;
    
    public function __construct($params)
    {
        $this->session = Factory::getSession();
        $this->input = Factory::getApplication()->input;
        $this->params = $params;
    }
    
    public function execute()
    {
        // Handle reset request
        if ($this->input->get('reset', '', 'CMD') === '1') {
            $this->session->set('chat_history', []);
            Factory::getApplication()->redirect($_SERVER['PHP_SELF']);
            return ['chat_history' => []];
        }

        // Get all parameters
        $assistantName = $this->params->get('assistant_name', 'AI JokoVlog');
        $model = $this->params->get('model', 'llama3');
        $apiUrl = $this->params->get('api_url', 'http://localhost:11434');

        // Initialize chat history
        $chatHistory = $this->session->get('chat_history', []);
        
        if (!is_array($chatHistory)) {
            $chatHistory = [];
            $this->session->set('chat_history', $chatHistory);
        }

        // Process user input
        if ($this->input->getMethod() === 'POST' 
            && !empty($this->input->post->get('user_input', '', 'STRING')) 
            && !$this->session->has('last_input')) {
            
            $userInput = strip_tags($this->input->post->get('user_input', '', 'STRING'));
            $this->session->set('last_input', $userInput);
            
            require_once dirname(__FILE__) . '/helper.php';
            $chatResponse = ModOllamaChatHelper::getResponse($apiUrl, $model, $userInput);
            
            $chatHistory[] = ['role' => 'user', 'content' => $userInput];
            $chatHistory[] = ['role' => 'assistant', 'content' => $chatResponse];
            $this->session->set('chat_history', $chatHistory);
            
            Factory::getApplication()->redirect($_SERVER['PHP_SELF']);
            return [
                'assistant_name' => $assistantName,
                'chat_history' => $chatHistory
            ];
        } elseif ($this->session->has('last_input')) {
            $this->session->clear('last_input');
        }

        return [
            'assistant_name' => $assistantName,
            'chat_history' => $chatHistory
        ];
    }
}

// Instantiate and execute the module
$modOllamaChat = new ModOllamaChat($params);
$data = $modOllamaChat->execute();

// Extract variables for template
$assistantName = $data['assistant_name'];
$chatHistory = is_array($data['chat_history']) ? $data['chat_history'] : [];

require ModuleHelper::getLayoutPath('mod_ollama_chat');