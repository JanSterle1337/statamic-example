<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Yaml\Yaml;

use function Safe\file_get_contents;

class ArticleController extends Controller
{
    /**
     * Update the specified article in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $title
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, $title) 
     {
        
        $filePath =  base_path('content/collections/articles/' . $title . '.md');
        $newFilePath = base_path('content/collections/articles/' .  'new-title' . '.md');
    
        if (file_exists($filePath)) {

            $markdownFile = file_get_contents($filePath);

            $markdownDocument = YamlFrontMatter::parse($markdownFile);

            $attributes = $markdownDocument->matter();
            $attributes['title'] = 'New title';

            $yaml = Yaml::dump($attributes);

            $newMarkdownFile = "---\n{$yaml}---\n{$markdownDocument->body()}";
            file_put_contents($filePath, $newMarkdownFile);
            rename($filePath, $newFilePath);

            return response()->json(['message' => 'Path was found', 'data' => $filePath]);
        } else {
            return response()->json(['error' => 'Article was not found', 'log' => $filePath]);
        }
     }
}
