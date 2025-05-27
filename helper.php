<?php
/**
 * @package     mod_ollama_chat
 * @author      Joko Supriyanto <joko@sibermu.ac.id>
 * @Institution Universitas Siber Muhammadiyah - SiberMu https://sibermu.ac.id/
 * @copyright   Copyright (C) 2025 JokoVlog. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class ModOllamaChatHelper {
    public static function getResponse($apiUrl, $model, $input) {
        // Validasi input kosong
        if (empty(trim($input))) {
            return 'Pesan tidak boleh kosong';
        }

        $url = rtrim($apiUrl, '/') . '/v1/chat/completions';
        $data = json_encode([
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $input]
            ],
            'temperature' => 0.7
        ]);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => $data
            ]
        ];

        $context = stream_context_create($opts);
        $result = @file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return 'Gagal menghubungi API di ' . htmlspecialchars($url);
        }

        $json = json_decode($result, true);

        if (!isset($json['choices'][0]['message']['content'])) {
            return 'Tidak ada balasan dari model.';
        }

        // Normalisasi newline pada respons
        $response = $json['choices'][0]['message']['content'];
        $response = preg_replace("/(\r?\n){2,}/", "\n", $response);
        return trim($response);
    }
}