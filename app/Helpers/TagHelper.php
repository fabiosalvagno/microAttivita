<?php

namespace App\Helpers;

class TagHelper
{
    /**
     * Genera l'HTML per visualizzare i tag.
     * (Questa è la stessa funzione che avevi in lista.php)
     *
     * @param string|null $tags_string Una stringa di tag separati da virgola.
     * @return string L'HTML risultante.
     */
    public static function generateTagsHTML($tags_string)
    {
        if (empty($tags_string)) return '<span class="text-gray-500 italic">Nessun tag</span>';
        $html = '';
        $tags_array = explode(',', $tags_string);
        foreach ($tags_array as $tag) {
            $tag = trim($tag);
            if (!empty($tag)) {
                $html .= '<span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">' . htmlspecialchars($tag) . '</span>';
            }
        }
        return $html;
    }
}